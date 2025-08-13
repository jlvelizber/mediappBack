<?php

namespace App\Listeners;

use App\Enum\AppointmentStatusEnum;
use App\Events\MedicalRecordCreatedEvent;
use App\Services\AppointmentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CompleteAppointmentStatus implements ShouldQueue
{

    public AppointmentService $appointmentService;

    /**
     * Create the event listener.
     */
    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }


    /**
     * Handle the event.
     */
    public function handle(MedicalRecordCreatedEvent $event): void
    {
        $appointment = $event->medicalRecord->appointment;
        if (!$appointment) {
            return; // No appointment associated with the medical record
        }
        // Update the appointment status to completed
        $this->appointmentService->updateAppointmentStatus($appointment->id, AppointmentStatusEnum::COMPLETED->value);
    }
}
