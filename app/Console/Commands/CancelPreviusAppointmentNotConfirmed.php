<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Interface\AppointmentRepositoryInterface;

class CancelPreviusAppointmentNotConfirmed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:cancel-not-confirmed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the status of previous appointments that are not confirmed to cancelled';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Logic to change the status of previous appointments that are not confirmed to cancelled
        // This is a placeholder for the actual implementation
        $this->info('Changing status of previous appointments that are not confirmed to cancelled...');

        // call the job to handle the cancellation
        $service = app(AppointmentRepositoryInterface::class);
        $job = new \App\Jobs\CancelPreviusAppointmentNotConfirmed();
        $job->setDependencies($service);
        dispatch($job);


        $this->info('Previous appointments that were not confirmed have been cancelled.');
    }
}
