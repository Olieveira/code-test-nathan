<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

    use HasFactory;

    protected $fillable = [
        'patient_id',
        'scheduled_time',
        'doctor_id',
        'closed_by',
        'closed_at',
        'notes',
        'status_id'
    ];

    protected $dates = [
        'scheduled_time',
        'closed_at'
    ];

    // Retorna os horarios disponíveis baseado no dia informado
    public function getAvailableTimes($date)
    {
        $allowedTimes = [
            '08:00',
            '09:00',
            '10:00',
            '11:00',
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00',
            '17:00'
        ];

        $formatted = \DateTime::createFromFormat('d/m/Y', $date);
        if (!$formatted) {
            return $allowedTimes;
        }
        $formattedDate = $formatted->format('Y-m-d');

        // Busca horários cadastrados para a data informada
        $scheduledTimes = self::whereDate('scheduled_time', $formattedDate)
            ->pluck('scheduled_time')
            ->map(function ($date) {
                return date('H:i', strtotime($date));
            })->toArray();

        // Apenas horarios disponiveis
        return array_values(array_diff($allowedTimes, $scheduledTimes));
    }

    public function Status()
    {
        return $this->belongsTo('App\Models\Status');
    }

    public function Patient()
    {
        return $this->belongsTo('App\Models\Patient');
    }
    public function Doctor()
    {
        return $this->belongsTo('App\Models\User', 'doctor_id'); // vet
    }
    public function closedBy()
    {
        return $this->belongsTo('App\Models\User', 'doctor_id'); // resp. fechamento
    }
}
