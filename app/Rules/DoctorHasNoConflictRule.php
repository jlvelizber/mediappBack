<?php

namespace App\Rules;

use App\Models\Appointment;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DoctorHasNoConflictRule implements ValidationRule
{

    protected $doctorId;
    protected $dateTime;

    public function __construct($doctorId, $dateTime)
    {
        $this->doctorId = $doctorId;
        $this->dateTime = Carbon::parse($dateTime); // Convertimos a Carbon
    }


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Verifica si ya hay una cita en el mismo horario para ese doctor
        if (
            Appointment::where('doctor_id', $this->doctorId)
                ->where('date_time', $this->dateTime)
                ->exists()
        ) {
            $fail(__('app.appointments.doctor_has_conflicting_appointment'));
        }
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('app.appointments.doctor_has_conflicting_appointment');
    }
}
