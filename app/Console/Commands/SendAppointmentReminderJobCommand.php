<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use App\Repositories\Interface\DoctorRepositoryInterface;

class SendAppointmentReminderJobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind Appointments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Begin remind appointment");
        // call the job to handle the cancellation
        $appointmentInterface = app(
            AppointmentRepositoryInterface::class
        );

        $doctorInterface = app(DoctorRepositoryInterface::class);

        $job = new \App\Jobs\SendAppointmentReminderJob();

        $job->setDependencies($appointmentInterface, $doctorInterface);
        dispatch($job);


        $this->info('Appointments remind succesfully.');
    }
}
