<?php

namespace App\Models;

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
}
