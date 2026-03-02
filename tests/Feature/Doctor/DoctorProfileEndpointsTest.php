<?php

namespace Tests\Feature\Doctor;

use App\Enum\UserRoleEnum;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DoctorProfileEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_endpoints_require_authentication(): void
    {
        $this->getJson('/api/profile')->assertUnauthorized();
        $this->putJson('/api/profile', [])->assertUnauthorized();
        $this->putJson('/api/profile/password', [])->assertUnauthorized();
    }

    public function test_doctor_can_get_own_profile(): void
    {
        [$doctorUser, $doctor] = $this->createDoctorUser();

        Sanctum::actingAs($doctorUser);

        $response = $this->getJson('/api/profile');

        $response
            ->assertOk()
            ->assertJsonPath('data.name', $doctorUser->name)
            ->assertJsonPath('data.email', $doctorUser->email)
            ->assertJsonPath('data.specialization', $doctor->specialization);
    }

    public function test_doctor_can_update_own_profile(): void
    {
        [$doctorUser] = $this->createDoctorUser();

        Sanctum::actingAs($doctorUser);

        $response = $this->putJson('/api/profile', [
            'name' => 'Andrea',
            'lastname' => 'Luna',
            'email' => 'andrea.luna@example.com',
            'phone' => '5551234567',
            'specialization' => 'Dermatologia',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Andrea')
            ->assertJsonPath('data.specialization', 'Dermatologia');

        $doctorUser->refresh();
        $this->assertSame('Andrea', $doctorUser->name);
        $this->assertSame('andrea.luna@example.com', $doctorUser->email);
        $this->assertSame('5551234567', $doctorUser->phone);
        $this->assertSame('Dermatologia', $doctorUser->doctor?->specialization);
    }

    public function test_doctor_can_change_password_with_correct_current_password(): void
    {
        [$doctorUser] = $this->createDoctorUser();

        Sanctum::actingAs($doctorUser);

        $response = $this->putJson('/api/profile/password', [
            'current_password' => 'password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertOk();

        $doctorUser->refresh();
        $this->assertTrue(Hash::check('new-password-123', $doctorUser->password));
    }

    public function test_doctor_cannot_change_password_with_invalid_current_password(): void
    {
        [$doctorUser] = $this->createDoctorUser();

        Sanctum::actingAs($doctorUser);

        $this->putJson('/api/profile/password', [
            'current_password' => 'incorrect-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
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
