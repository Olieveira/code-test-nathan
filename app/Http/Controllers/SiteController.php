<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

use App\Models\Patient;

use Carbon\Carbon;

class SiteController extends Controller
{

    public function getIndex(Request $request)
    {
        return view('index');
    }

    // ------------------ Cliente ------------------
    public function getClient(Request $request)
    {
        return view('client');
    }

    public function getEditPatient($patient_id = null)
    {
        $user = auth()->User();
        if (!$patient_id) {
            $patient = Patient::where(['user_id' => $user->id, 'name' => null])->first();

            if (!$patient) {
                $patient = Patient::create(['user_id' => $user->id]);
            }

            return redirect()->route('client.edit-patient', $patient->id);
        } else {
            $patient = Patient::where(['id' => $patient_id])->first();
        }

        return view('edit-patient', ['patient' => $patient]);
    }

    public function postEditPatient($patient_id, Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string',
            'gender' => 'required|string',
            'breed' => 'required|string',
            'birthdate' => 'required|date_format:d/m/Y',
            'image_path' => 'required|file|image'
        ]);

        $patient = Patient::find($patient_id);
        $birthdate = Carbon::createFromFormat('d/m/Y', $validated['birthdate']);

        $data = array_merge($request->except('birthdate'), [
            'birthdate' => $birthdate
        ]);

        // Tratamento do arquivo
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $path = $image->store('patients', 'public');
            $data['image_path'] = $path;
        }

        $patient->update($data);

        return redirect()->route('client')->with('toast', 'Paciente salvo com sucesso.');
    }

    public function getRemovePatient($patient_id)
    {
        $patient = Patient::find($patient_id);
        $patient->delete();

        return redirect()->route('client')->with('toast', 'Paciente removido com sucesso.');
    }

    public function getAppointment($appointment_id = null)
    {
        $appointment = Appointment::with(['status', 'patient.user'])->findOrFail($appointment_id);
        return view('appointment', compact('appointment'));
    }

    public function postUpdateNotes($appointment_id, Request $request)
    {
        if (auth()->user()->type !== 'VET') {
            abort(403, 'Acesso não autorizado.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string'
        ]);

        $appointment = Appointment::findOrFail($appointment_id);

        $appointment->update([
            'notes' => $validated['notes'],
            'closed_at' => now(),
            'closed_by' => $appointment->doctor_id,
            'status_id' => 2 // finalizado
        ]);
        return redirect()->route('vet')->with('toast', 'Atendimento finalizado com sucesso!');
    }

    public function getCreateAppointment($appointment_id = null)
    {
        if ($appointment_id) {
            $appointment = Appointment::findOrFail($appointment_id);
            return view('create-appointment', compact('appointment'));
        }

        return view('create-appointment');
    }


    public function postCreateAppointment(Request $request, $appointment_id = null)
    {
        $validated = $request->validate([
            'scheduled_at' => 'required|date_format:d/m/Y',
            'time' => 'required|date_format:H:i',
            'patient' => 'required|integer',
        ]);

        $scheduledDateTime = Carbon::createFromFormat('d/m/Y H:i', $validated['scheduled_at'] . ' ' . $validated['time']);
        $user = auth()->user();

        $data = [
            'scheduled_time' => $scheduledDateTime,
            'patient_id' => $validated['patient'],
            'doctor_id' => $user->type === 'VET' ? $user->id : null, // pendente logica para assumir atendimento
            'closed_by' => $user->type === 'VET' ? $user->id : null,
            'status_id' => 1,
        ];

        if ($appointment_id) {
            // Atualização
            $appointment = Appointment::findOrFail($appointment_id);
            $appointment->update($data);
        } else {
            // Criação
            $appointment = Appointment::create($data);
        }

        return redirect()->route('client')->with('toast', 'Consulta salva com sucesso.');
    }

    public function getAvailableTimesAjax(Request $request)
    {
        $date = $request->query('date');
        $times = (new Appointment)->getAvailableTimes($date);
        return response()->json($times);
    }

    // ------------------ Veterinário ------------------
    public function getVet(Request $request)
    {
        // - TODO: Retornar todos os agendamentos
        $appointments = [];
        return view('vet', ['appointments' => $appointments]);
    }

    public function getEditAppointment($appointment_id)
    {
        // - TODO: Retornar consulta
        $appointment = null;
        return view('edit-appointment', ['appointment' => $appointment]);
    }
}
