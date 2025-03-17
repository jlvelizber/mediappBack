<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Notifications\Appointment\NewAppointmentDoctorNotification;
use App\Notifications\Appointment\NewAppointmentPatientNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAppointmentNotification implements ShouldQueue
{

    use InteractsWithQueue;


    /**
     * Handle the event.
     */
    public function handle(AppointmentCreated $event): void
    {
        $appointment = $event->appointment;
        $doctor = $appointment->doctor;
        $patient = $appointment->patient;

        $patient->notify(new NewAppointmentPatientNotification($appointment));
        $doctor->user->notify(new NewAppointmentDoctorNotification($appointment));

    }
}
