<?php

namespace App\Jobs;

use App\Enum\AppointmentStatusEnum;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use Illuminate\Container\Attributes\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CancelPreviusAppointmentNotConfirmed implements ShouldQueue
{
    use Queueable;

    /**
     * @var AppointmentRepositoryInterface
     */
    protected $appointmentRepository;

    public function setDependency(AppointmentRepositoryInterface $appointmentRepository): void
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Logic to change the status of previous appointments that are not confirmed to cancelled
        // This is a placeholder for the actual implementation
        $appointments = $this->appointmentRepository->getPreviousAppointmentsNotConfirmed();
        if ($appointments->isEmpty()) {
            \Log::info('No previous appointments found that were not confirmed.');
            return;
        }
        foreach ($appointments as $appointment) {
            \Log::info('Cancelling appointment ID: ' . $appointment->id);
            $appointment->status = AppointmentStatusEnum::CANCELLED; // Assuming 'cancelled' is the status you want to set
            $appointment->save();
        }
        // Optionally, you can log or notify about the cancellation
        \Log::info('Previous appointments that were not confirmed have been cancelled.');
    }
}
