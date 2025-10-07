<?php

namespace App\Listeners;

use App\Enum\WayNotificationEnum;
use App\Events\PrescriptionReadyEvent;
use App\Notifications\Prescription\PrescriptionReadyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPrescriptionToPatient implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = 10;

    /**
     * The maximum number of times the job may be attempted.
     */
    public $tries = 3;

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
        $doctor = $prescription->appointment->doctor;
        $patient = $prescription->appointment->patient;
        // Logic to send the prescription PDF to the patient
        // This could involve sending an email or notification with the PDF attached
        // Example: $patient->notify(new PrescriptionReadyNotification($path));
        $patient->notify(new PrescriptionReadyNotification($path, $doctor->configuration->notification_way ?? WayNotificationEnum::BOTH->value));
    }
}
