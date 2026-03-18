<?php

namespace App\Http\Controllers;

use App\Enum\UserRoleEnum;
use App\Enum\WayNotificationEnum;
use App\Http\Requests\Setup\SetupInitializeRequest;
use App\Models\Doctor;
use App\Models\DoctorConfiguration;
use App\Models\User;
use App\Services\InstallationStateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class SetupController extends Controller
{
    public function __construct(
        private readonly InstallationStateService $installationStateService
    ) {
    }

    public function status(): JsonResponse
    {
        return response()->json([
            'data' => [
                'installed' => $this->installationStateService->isInstalled(),
                'defaults' => [
                    'default_appointment_duration' => (int) config('mediapp.doctor_configuration.default_appointment_duration'),
                    'default_appointment_price' => (float) config('mediapp.doctor_configuration.default_appointment_price'),
                    'default_appointment_currency' => config('mediapp.doctor_configuration.default_appointment_currency'),
                    'default_appointment_currency_symbol' => config('mediapp.doctor_configuration.default_appointment_currency_symbol'),
                    'notification_way' => WayNotificationEnum::BOTH->value,
                    'reminder_hour_appointment' => 24,
                ],
            ],
        ]);
    }

    public function initialize(SetupInitializeRequest $request): JsonResponse
    {
        if ($this->installationStateService->isInstalled()) {
            return response()->json([
                'message' => 'La aplicación ya fue inicializada.',
            ], Response::HTTP_CONFLICT);
        }

        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $doctorUser = User::create([
                'name' => $validated['admin_name'],
                'lastname' => $validated['admin_lastname'],
                'email' => $validated['admin_email'],
                'phone' => $validated['admin_phone'] ?? null,
                'password' => Hash::make($validated['admin_password']),
                'role' => UserRoleEnum::DOCTOR->value,
            ]);

            $doctor = Doctor::create([
                'user_id' => $doctorUser->id,
                'specialization' => $validated['doctor_specialization'] ?? 'Medicina general',
            ]);

            DoctorConfiguration::create([
                'doctor_id' => $doctor->id,
                'setup_completed_at' => now(),
                'default_appointment_duration' => $validated['default_appointment_duration']
                    ?? config('mediapp.doctor_configuration.default_appointment_duration'),
                'default_appointment_price' => $validated['default_appointment_price']
                    ?? config('mediapp.doctor_configuration.default_appointment_price'),
                'default_appointment_currency' => $validated['default_appointment_currency']
                    ?? config('mediapp.doctor_configuration.default_appointment_currency'),
                'default_appointment_currency_symbol' => $validated['default_appointment_currency_symbol']
                    ?? config('mediapp.doctor_configuration.default_appointment_currency_symbol'),
                'notification_way' => $validated['notification_way'] ?? WayNotificationEnum::BOTH->value,
                'reminder_hour_appointment' => $validated['reminder_hour_appointment'] ?? 24,
                'medical_center_name' => $validated['medical_center_name'],
                'medical_center_address' => $validated['medical_center_address'] ?? null,
                'medical_center_phone' => $validated['medical_center_phone'] ?? null,
                'medical_center_email' => $validated['medical_center_email'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Instalación inicial completada.',
            'data' => [
                'installed' => true,
            ],
        ], Response::HTTP_CREATED);
    }
}
