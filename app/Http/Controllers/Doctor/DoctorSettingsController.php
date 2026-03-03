<?php

namespace App\Http\Controllers\Doctor;

use App\Enum\WayNotificationEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\Settings\DoctorSettingsUpdateRequest;
use App\Models\DoctorConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorSettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $doctorId = $request->user()->doctor->id;
        $configuration = $this->getOrCreateConfiguration($doctorId);

        return response()->json([
            'data' => $this->mapConfigurationResponse($configuration),
        ]);
    }

    public function update(DoctorSettingsUpdateRequest $request): JsonResponse
    {
        $doctorId = $request->user()->doctor->id;
        $configuration = $this->getOrCreateConfiguration($doctorId);
        $validated = $request->validated();

        $updateData = array_filter([
            'default_appointment_duration' => $validated['default_appointment_duration'] ?? null,
            'default_appointment_price' => $validated['default_appointment_price'] ?? null,
            'default_appointment_currency' => $validated['currency_default_appointment'] ?? null,
            'default_appointment_currency_symbol' => $validated['currency_symbol_default_appointment'] ?? null,
            'notification_way' => $validated['way_notify_appointment'] ?? null,
            'reminder_hour_appointment' => $validated['reminder_hour_appointment'] ?? null,
            'medical_center_name' => $validated['medical_center_name'] ?? null,
            'medical_center_address' => $validated['medical_center_address'] ?? null,
            'medical_center_phone' => $validated['medical_center_phone'] ?? null,
        ], fn($value) => $value !== null);

        if (!empty($updateData)) {
            $configuration->update($updateData);
        }

        $configuration->refresh();

        return response()->json([
            'message' => 'Configuración actualizada correctamente.',
            'data' => $this->mapConfigurationResponse($configuration),
        ]);
    }

    private function getOrCreateConfiguration(int $doctorId): DoctorConfiguration
    {
        return DoctorConfiguration::firstOrCreate(
            ['doctor_id' => $doctorId],
            [
                'default_appointment_duration' => config('mediapp.doctor_configuration.default_appointment_duration'),
                'default_appointment_price' => config('mediapp.doctor_configuration.default_appointment_price'),
                'default_appointment_currency' => config('mediapp.doctor_configuration.default_appointment_currency'),
                'default_appointment_currency_symbol' => config('mediapp.doctor_configuration.default_appointment_currency_symbol'),
                'notification_way' => WayNotificationEnum::BOTH->value,
                'reminder_hour_appointment' => 24,
            ]
        );
    }

    private function mapConfigurationResponse(DoctorConfiguration $configuration): array
    {
        return [
            'default_appointment_duration' => (int) $configuration->default_appointment_duration,
            'default_appointment_price' => (float) $configuration->default_appointment_price,
            'currency_default_appointment' => $configuration->default_appointment_currency,
            'currency_symbol_default_appointment' => $configuration->default_appointment_currency_symbol,
            'way_notify_appointment' => $configuration->notification_way,
            'reminder_hour_appointment' => (int) $configuration->reminder_hour_appointment,
            'medical_center_name' => $configuration->medical_center_name,
            'medical_center_address' => $configuration->medical_center_address,
            'medical_center_phone' => $configuration->medical_center_phone,
        ];
    }
}
