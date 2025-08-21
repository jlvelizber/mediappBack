<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorConfiguration extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorConfigurationFactory> */
    use HasFactory;

    protected $fillable = [
        'default_appointment_duration',
        'default_appointment_price',
        'default_appointment_currency',
        'default_appointment_currency_symbol',
        'medical_center_name',
        'medical_center_address',
        'medical_center_phone',
        'medical_center_email',
        'medical_center_logo',
        'medical_center_website',
        'medical_center_social_media',
        'medical_center_tax_id',
        'notification_way',
        'reminder_hour_appointment'
    ];
}
