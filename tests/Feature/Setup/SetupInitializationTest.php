<?php

namespace Tests\Feature\Setup;

use App\Enum\UserRoleEnum;
use App\Models\DoctorConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetupInitializationTest extends TestCase
{
    use RefreshDatabase;

    public function test_setup_status_is_false_when_app_is_not_initialized(): void
    {
        $this->getJson('/api/setup/status')
            ->assertOk()
            ->assertJsonPath('data.installed', false);
    }

    public function test_setup_initialize_creates_initial_doctor_and_configuration(): void
    {
        $payload = [
            'admin_name' => 'Carlos',
            'admin_lastname' => 'Ruiz',
            'admin_email' => 'doctor@example.com',
            'admin_phone' => '5551234567',
            'admin_password' => 'Password123!',
            'admin_password_confirmation' => 'Password123!',
            'doctor_specialization' => 'Cardiologia',
            'medical_center_name' => 'Clinica Central',
            'medical_center_address' => 'Av. Salud 123',
            'medical_center_phone' => '5550001111',
            'medical_center_email' => 'contacto@clinicacentral.com',
            'default_appointment_duration' => 45,
            'default_appointment_price' => 800,
            'default_appointment_currency' => 'MXN',
            'default_appointment_currency_symbol' => '$',
            'notification_way' => 'email',
            'reminder_hour_appointment' => 12,
        ];

        $this->postJson('/api/setup/initialize', $payload)
            ->assertCreated()
            ->assertJsonPath('data.installed', true);

        $this->assertDatabaseHas('users', [
            'email' => 'doctor@example.com',
            'role' => UserRoleEnum::DOCTOR->value,
            'lastname' => 'Ruiz',
        ]);

        $this->assertDatabaseHas('doctors', [
            'specialization' => 'Cardiologia',
        ]);

        $configuration = DoctorConfiguration::query()->first();

        $this->assertNotNull($configuration);
        $this->assertNotNull($configuration->setup_completed_at);
        $this->assertSame('45', $configuration->default_appointment_duration);
        $this->assertSame('800', $configuration->default_appointment_price);
        $this->assertSame('MXN', $configuration->default_appointment_currency);
        $this->assertSame('$', $configuration->default_appointment_currency_symbol);
        $this->assertSame('email', $configuration->notification_way);
        $this->assertSame(12, $configuration->reminder_hour_appointment);

        $this->getJson('/api/setup/status')
            ->assertOk()
            ->assertJsonPath('data.installed', true)
            ->assertJsonStructure([
                'data' => [
                    'installed',
                    'defaults' => [
                        'default_appointment_duration',
                        'default_appointment_price',
                        'default_appointment_currency',
                        'default_appointment_currency_symbol',
                        'notification_way',
                        'reminder_hour_appointment',
                    ],
                ],
            ]);
    }

    public function test_setup_initialize_is_blocked_after_completion(): void
    {
        $payload = [
            'admin_name' => 'Carlos',
            'admin_lastname' => 'Ruiz',
            'admin_email' => 'doctor@example.com',
            'admin_password' => 'Password123!',
            'admin_password_confirmation' => 'Password123!',
            'medical_center_name' => 'Clinica Central',
        ];

        $this->postJson('/api/setup/initialize', $payload)->assertCreated();

        $this->postJson('/api/setup/initialize', [
            ...$payload,
            'admin_email' => 'otro@example.com',
        ])->assertStatus(409);
    }
}
