<?php

namespace Tests\Feature\Doctor;

use App\Enum\AppointmentStatusEnum;
use App\Enum\UserRoleEnum;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DoctorOwnershipAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_view_own_appointment(): void
    {
        [$doctorUser, $doctor] = $this->createDoctorUser();
        $appointment = $this->createAppointmentForDoctor($doctor);

        Sanctum::actingAs($doctorUser);

        $response = $this->getJson("/api/appointments/{$appointment->id}");

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $appointment->id);
    }

    public function test_doctor_cannot_view_appointment_from_another_doctor(): void
    {
        [$doctorUser] = $this->createDoctorUser();
        [$otherUser, $otherDoctor] = $this->createDoctorUser();
        $otherAppointment = $this->createAppointmentForDoctor($otherDoctor);

        Sanctum::actingAs($doctorUser);

        $this->getJson("/api/appointments/{$otherAppointment->id}")
            ->assertNotFound();
    }

    public function test_doctor_cannot_delete_appointment_from_another_doctor(): void
    {
        [$doctorUser] = $this->createDoctorUser();
        [$otherUser, $otherDoctor] = $this->createDoctorUser();
        $otherAppointment = $this->createAppointmentForDoctor($otherDoctor);

        Sanctum::actingAs($doctorUser);

        $this->deleteJson("/api/appointments/{$otherAppointment->id}")
            ->assertNotFound();
    }

    public function test_doctor_cannot_update_status_of_appointment_from_another_doctor(): void
    {
        [$doctorUser] = $this->createDoctorUser();
        [$otherUser, $otherDoctor] = $this->createDoctorUser();
        $otherAppointment = $this->createAppointmentForDoctor($otherDoctor);

        Sanctum::actingAs($doctorUser);

        $response = $this->putJson("/api/appointments/{$otherAppointment->id}/status", [
            'status' => AppointmentStatusEnum::CONFIRMED->value,
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'was_success' => false,
            ]);
    }

    public function test_doctor_can_view_own_medical_record(): void
    {
        [$doctorUser, $doctor] = $this->createDoctorUser();
        $appointment = $this->createAppointmentForDoctor($doctor);
        $medicalRecord = $this->createMedicalRecordForAppointment($appointment);

        Sanctum::actingAs($doctorUser);

        $response = $this->getJson("/api/medical-record/{$medicalRecord->id}");

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $medicalRecord->id);
    }

    public function test_doctor_cannot_view_medical_record_from_another_doctor(): void
    {
        [$doctorUser] = $this->createDoctorUser();
        [$otherUser, $otherDoctor] = $this->createDoctorUser();
        $otherAppointment = $this->createAppointmentForDoctor($otherDoctor);
        $otherMedicalRecord = $this->createMedicalRecordForAppointment($otherAppointment);

        Sanctum::actingAs($doctorUser);

        $this->getJson("/api/medical-record/{$otherMedicalRecord->id}")
            ->assertNotFound();
    }

    public function test_doctor_cannot_delete_medical_record_from_another_doctor(): void
    {
        [$doctorUser] = $this->createDoctorUser();
        [$otherUser, $otherDoctor] = $this->createDoctorUser();
        /** @var Doctor $otherDoctor */
        $otherAppointment = $this->createAppointmentForDoctor($otherDoctor);
        $otherMedicalRecord = $this->createMedicalRecordForAppointment($otherAppointment);

        Sanctum::actingAs($doctorUser);

        $this->deleteJson("/api/medical-record/{$otherMedicalRecord->id}")
            ->assertNotFound();
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

    private function createAppointmentForDoctor(Doctor $doctor): Appointment
    {
        $patient = Patient::create([
            'doctor_id' => $doctor->id,
            'document' => (string) fake()->numerify('########'),
            'name' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->safeEmail(),
        ]);

        return Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'date_time' => now()->addDay(),
            'duration_minutes' => 20,
            'status' => AppointmentStatusEnum::PENDING->value,
            'reason' => 'Control general',
        ]);
    }

    private function createMedicalRecordForAppointment(Appointment $appointment): MedicalRecord
    {
        return MedicalRecord::create([
            'appointment_id' => $appointment->id,
            'symptoms' => 'Dolor de cabeza',
            'diagnosis' => 'Migraña',
            'treatment' => 'Analgésico',
            'notes' => 'Seguimiento en 7 días',
        ]);
    }
}
