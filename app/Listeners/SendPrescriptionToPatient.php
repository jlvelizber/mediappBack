<?php

namespace App\Listeners;

use App\Events\PrescriptionReadyEvent;
use App\Notifications\PrescriptionReadyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPrescriptionToPatient
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PrescriptionReadyEvent $event): void
    {
        $path = $event->path;
        $prescription = $event->prescription;
        $patient = $prescription->appointment->patient;
        // Logic to send the prescription PDF to the patient
        // This could involve sending an email or notification with the PDF attached
        // Example: $patient->notify(new PrescriptionReadyNotification($path));
        $patient->notify(new PrescriptionReadyNotification($path));
    }
}
