<?php

namespace App\Jobs;

use App\Jobs\Interface\JobRepositoryDependencyInterface;
use App\Enum\AppointmentStatusEnum;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use App\Repositories\Interface\RootRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CancelPreviusAppointmentNotConfirmed implements ShouldQueue, JobRepositoryDependencyInterface
{
    use Queueable;
    
    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;
    
    /**
     * The maximum number of seconds the job can run.
     */
    public $timeout = 300;

    /**
     * @var AppointmentRepositoryInterface
     */
    protected $appointmentRepository;

    public function setDependencies(RootRepositoryInterface ...$rootRepositoryInterface): void
    {
        $this->appointmentRepository = $rootRepositoryInterface[0];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting cancellation of previous appointments not confirmed...');
            
            $appointments = $this->appointmentRepository->getPreviousAppointmentsNotConfirmed();
            
            if ($appointments->isEmpty()) {
                Log::info('No previous appointments found that were not confirmed.');
                return;
            }
            
            Log::info('Found ' . $appointments->count() . ' appointments to cancel.');
            
            $cancelledCount = 0;
            foreach ($appointments as $appointment) {
                try {
                    Log::info('Cancelling appointment ID: ' . $appointment->id . ' - Date: ' . $appointment->date_time);
                    $appointment->status = AppointmentStatusEnum::CANCELLED;
                    $appointment->save();
                    $cancelledCount++;
                } catch (\Exception $e) {
                    Log::error('Failed to cancel appointment ID: ' . $appointment->id . ' - Error: ' . $e->getMessage());
                    // Continuar con el siguiente appointment
                }
            }
            
            Log::info('Successfully cancelled ' . $cancelledCount . ' out of ' . $appointments->count() . ' previous appointments.');
            
        } catch (\Exception $e) {
            Log::error('Job CancelPreviusAppointmentNotConfirmed failed: ' . $e->getMessage());
            throw $e; // Re-lanzar para que Laravel maneje el reintento
        }
    }
    
    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job CancelPreviusAppointmentNotConfirmed failed permanently: ' . $exception->getMessage());
    }
}
