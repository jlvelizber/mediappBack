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
        $this->info('Changing status of previous appointments that are not confirmed to cancelled...');

        // Usar Job pero ejecutarlo síncronamente para mejor control
        $appointmentRepository = app(AppointmentRepositoryInterface::class);
        $job = new \App\Jobs\CancelPreviusAppointmentNotConfirmed();
        $job->setDependencies($appointmentRepository);
        
        // Ejecutar síncronamente para evitar problemas de cola
        $job->handle();
        
        $this->info('Previous appointments cancellation job completed successfully.');
    }
}
