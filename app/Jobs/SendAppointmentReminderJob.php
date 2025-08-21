<?php

namespace App\Jobs;

use App\Jobs\Interface\JobRepositoryDependencyInterface;
use App\Notifications\Appointment\RemindAppointmentPatientNotification;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use App\Repositories\Interface\DoctorRepositoryInterface;
use App\Repositories\Interface\RootRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminderJob implements ShouldQueue, JobRepositoryDependencyInterface
{
    use Queueable;

    protected AppointmentRepositoryInterface $appointmentRepository;
    protected DoctorRepositoryInterface $doctorRepository;

    /**
     * Create a new job instance.
     */
    public function setDependencies(
        RootRepositoryInterface ...$rootRepositoryInterface
    ) {
        $this->appointmentRepository = $rootRepositoryInterface[0];
        $this->doctorRepository = $rootRepositoryInterface[1];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $allDoctors = $this->doctorRepository->all();
        foreach ($allDoctors as $doctor) {
            $futureAppointments = $this->appointmentRepository->findFutureAppointments($doctor->id);
            foreach ($futureAppointments as $appointment) {

                $reminderHour = $doctor->configuration->reminder_hour_appointment ?? 24;
                $wayNotification = $doctor->configuration->notification_way;
                $patient = $appointment->patient;

                if ($patient) {
                    $reminderTime = $appointment->date_time->subHours($reminderHour);
                    if (now()->greaterThanOrEqualTo($reminderTime)) {
                        // Send notification based on doctor's preferred notification way
                        Log::info("Sending reminder: " . $patient->full_name . " Appointment :" . $appointment->id);
                        $patient->notify(new RemindAppointmentPatientNotification($wayNotification));
                    }
                }
            }
        }
    }
}
