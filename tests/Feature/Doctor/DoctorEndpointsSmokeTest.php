<?php

namespace Tests\Feature\Doctor;

use App\Enum\UserRoleEnum;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DoctorEndpointsSmokeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array<int, string>
     */
    private array $doctorEndpoints = [
        '/api/dashboard',
        '/api/patients',
        '/api/patients/paginate',
        '/api/appointments',
        '/api/appointments/paginate',
        '/api/medical-record',
        '/api/availabilities',
    ];

    public function test_doctor_endpoints_require_authentication(): void
    {
        foreach ($this->doctorEndpoints as $endpoint) {
            $this->getJson($endpoint)->assertUnauthorized();
        }
    }

    public function test_authenticated_doctor_can_access_critical_endpoints(): void
    {
        [$doctorUser] = $this->createDoctorUser();

        Sanctum::actingAs($doctorUser);

        $endpoints = $this->doctorEndpoints;
        if (DB::connection()->getDriverName() === 'sqlite') {
            $endpoints = array_values(array_filter(
                $endpoints,
                fn(string $endpoint): bool => $endpoint !== '/api/dashboard'
            ));
        }

        foreach ($endpoints as $endpoint) {
            $this->getJson($endpoint)->assertOk();
        }
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
