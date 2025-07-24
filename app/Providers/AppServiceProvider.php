<?php

namespace App\Providers;

use App\Repositories\Eloquent\AppointmentRepository;
use App\Repositories\Eloquent\ClinicalHistoryRepository;
use App\Repositories\Eloquent\DoctorAvailabilityRepository;
use App\Repositories\Eloquent\DoctorConfigurationRepository;
use App\Repositories\Eloquent\DoctorRepository;
use App\Repositories\Eloquent\MedicalRecordRepository;
use App\Repositories\Eloquent\PatientRepository;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use App\Repositories\Interface\ClinicalHistoryRepositoryInterface;
use App\Repositories\Interface\DoctorAvailabilityRepositoryInterface;
use App\Repositories\Interface\DoctorConfigurationRepositoryInterface;
use App\Repositories\Interface\DoctorRepositoryInterface;
use App\Repositories\Interface\MedicalRecordRepositoryInterface;
use App\Repositories\Interface\PatientRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interface\UserRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AppointmentRepositoryInterface::class, AppointmentRepository::class);
        $this->app->bind(MedicalRecordRepositoryInterface::class, MedicalRecordRepository::class);
        $this->app->bind(PatientRepositoryInterface::class, PatientRepository::class);
        $this->app->bind(DoctorRepositoryInterface::class, DoctorRepository::class);
        $this->app->bind(DoctorAvailabilityRepositoryInterface::class, DoctorAvailabilityRepository::class);
        $this->app->bind(DoctorConfigurationRepositoryInterface::class, DoctorConfigurationRepository::class);
        $this->app->bind(ClinicalHistoryRepositoryInterface::class, ClinicalHistoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
