<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalHistory extends Model
{
    protected $fillable = [
        'appointment_id',
        'appointments',
        'cascade',
        'symptoms',
        'diagnosis',
        'treatment',
        'notes',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
