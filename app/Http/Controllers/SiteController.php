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
        if ($patient_id) {
            $patient = Patient::findOrFail($patient_id);
            return view('edit-patient', compact('patient'));
        } else {
            $patient = null;
        }

        return view('edit-patient', compact('patient'));
    }

    public function postEditPatient(Request $request, $patient_id = null)
    {
        $rules = [
            'name' => 'required|string',
            'gender' => 'required|string',
            'breed' => 'required|string',
            'birthdate' => 'required|date_format:d/m/Y',
            'image_path' => $patient_id ? 'nullable|file|image' : 'required|file|image',
        ];

        $validated = $request->validate($rules);

        $data = array_merge($request->except('birthdate'), [
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'breed' => $validated['breed'],
            'birthdate' => Carbon::createFromFormat('d/m/Y', $validated['birthdate'])
        ]);

        // Tratamento do arquivo
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $path = $image->store('patients', 'public');
            $data['image_path'] = $path;
        }

        if ($patient_id) {
            // Atualiza
            $patient = Patient::findOrFail($patient_id);
            $patient->update($data);
        } else {
            // Cria
            $data['user_id'] = auth()->user()->id;
            $patient = Patient::create($data);
        }

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
            'doctor_id' => $user->type === 'VET' ? $user->id : null,
            'closed_by' => $user->type === 'VET' ? $user->id : null,
            'status_id' => 1
        ];

        if ($appointment_id) {
            $appointment = Appointment::findOrFail($appointment_id);
            $appointment->update($data);
        } else {
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
        $user = auth()->user();

        if ($user->type !== 'VET') {
            abort(403, 'Acesso não autorizado.');
        }

        $appointment = Appointment::findOrFail($appointment_id);

        $appointment->update([
            'notes' => $appointment->notes,
            'closed_at' => now(),
            'doctor_id' => $user->id,
            'closed_by' => $user->id,
            'status_id' => 2 // finalizado
        ]);

        return redirect()->route('vet')->with('toast', 'Atendimento finalizado com sucesso!');
    }
}
