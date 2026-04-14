<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ResetDemoDatabaseCommand extends Command
{
    protected $signature = 'demo:reset-database {--no-seed : Only run migrate:fresh without seeders}
                            {--force : Skip confirmation and allow running in production}';

    protected $description = 'Drop all tables, re-run migrations, and optionally seed (for client demos)';

    public function handle(): int
    {
        if (app()->environment('production') && ! $this->option('force')) {
            $this->error('Refusing to run in production without --force.');

            return self::FAILURE;
        }

        if (! $this->option('force')) {
            $this->warn('This will destroy all data in the configured database.');
            if (! $this->confirm('Do you want to continue?')) {
                $this->info('Aborted.');

                return self::FAILURE;
            }
        }

        $this->info('Running migrate:fresh...');

        $migrateOptions = [
            '--force' => true,
        ];

        if (! $this->option('no-seed')) {
            $migrateOptions['--seed'] = true;
        }

        $exitCode = Artisan::call('migrate:fresh', $migrateOptions, $this->output);

        if ($exitCode !== 0) {
            return self::FAILURE;
        }

        $this->info('Database reset completed successfully.');

        return self::SUCCESS;
    }
}
