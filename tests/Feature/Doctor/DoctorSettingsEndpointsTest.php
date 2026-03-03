<?php

namespace Tests\Feature\Doctor;

use App\Enum\UserRoleEnum;
use App\Enum\WayNotificationEnum;
use App\Models\Doctor;
use App\Models\DoctorConfiguration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DoctorSettingsEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_endpoints_require_authentication(): void
    {
        $this->getJson('/api/doctor/settings')->assertUnauthorized();
        $this->putJson('/api/doctor/settings', [])->assertUnauthorized();
    }

    public function test_doctor_can_get_settings_and_auto_create_defaults(): void
    {
        [$doctorUser, $doctor] = $this->createDoctorUser();

        Sanctum::actingAs($doctorUser);

        $response = $this->getJson('/api/doctor/settings');

        $response
            ->assertOk()
            ->assertJsonPath('data.default_appointment_duration', 20)
            ->assertJsonPath('data.way_notify_appointment', WayNotificationEnum::BOTH->value);

        $this->assertDatabaseHas('doctor_configurations', [
            'doctor_id' => $doctor->id,
        ]);
    }

    public function test_doctor_can_update_own_settings(): void
    {
        [$doctorUser, $doctor] = $this->createDoctorUser();
        DoctorConfiguration::factory()->create([
            'doctor_id' => $doctor->id,
        ]);

        Sanctum::actingAs($doctorUser);

        $response = $this->putJson('/api/doctor/settings', [
            'default_appointment_duration' => 30,
            'default_appointment_price' => 650,
            'currency_default_appointment' => 'MXN',
            'currency_symbol_default_appointment' => '$',
            'way_notify_appointment' => WayNotificationEnum::WHATSAPP->value,
            'reminder_hour_appointment' => 12,
            'medical_center_name' => 'Consultorio Dr. Ruiz',
            'medical_center_address' => 'Av. Reforma 123',
            'medical_center_phone' => '5512345678',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.default_appointment_duration', 30)
            ->assertJsonPath('data.currency_default_appointment', 'MXN')
            ->assertJsonPath('data.way_notify_appointment', WayNotificationEnum::WHATSAPP->value);

        $this->assertDatabaseHas('doctor_configurations', [
            'doctor_id' => $doctor->id,
            'default_appointment_duration' => 30,
            'notification_way' => WayNotificationEnum::WHATSAPP->value,
            'medical_center_name' => 'Consultorio Dr. Ruiz',
        ]);
    }

    public function test_doctor_settings_validation_for_notification_way(): void
    {
        [$doctorUser] = $this->createDoctorUser();
        Sanctum::actingAs($doctorUser);

        $this->putJson('/api/doctor/settings', [
            'way_notify_appointment' => 'telegram',
        ])->assertUnprocessable();
    }

    /**
     * @return array{0: User, 1: Doctor}
     */
    private function createDoctorUser(): array
    {
        $user = User::factory()->create([
            'role' => UserRoleEnum::DOCTOR->value,
        ]);

        $doctor = Doctor::factory()->create([
            'user_id' => $user->id,
            'specialization' => 'Medicina general',
        ]);

        return [$user, $doctor];
    }
}
