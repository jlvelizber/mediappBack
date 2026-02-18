<?php

namespace App\Services;

use App\Enum\AppointmentStatusEnum;
use App\Models\Patient;
use App\Repositories\Interface\PatientRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PatientService
{
    protected PatientRepositoryInterface $patientRepository;

    public function __construct(PatientRepositoryInterface $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function getAllPatients(): Collection
    {
        return $this->patientRepository->all();
    }

    public function getPatientById($id): Patient|null
    {
        $patient = $this->patientRepository->find($id);
        if (!$patient)
            throw new NotFoundHttpException('Patient not found', null, Response::HTTP_NOT_FOUND);

        return $patient;
    }

    public function createPatient(array $data): Patient
    {

        return $this->patientRepository->create($data);
    }

    public function updatePatient($id, array $data): Patient|null
    {
        $this->getPatientById($id);

        $updated = $this->patientRepository->update($id, $data);
        if (!$updated) {
            throw ValidationException::withMessages(['message' => 'Patient was not updated']);
        }
        return $this->getPatientById($id);
    }

    public function deletePatient($id): bool|null
    {
        return $this->patientRepository->delete($id);
    }

    /**
     * Get all patients by doctor id
     *
     * @param [type] $doctorId
     * @return Collection
     */
    public function getAllPatientsByDoctorId($doctorId): Collection
    {
        return $this->patientRepository->getAllPatientsByDoctorId($doctorId);
    }

    /**
     * Summary of paginatePatientByDoctorId
     * @param mixed $doctorId
     * @param mixed $perPage
     * @return LengthAwarePaginator
     */
    public function paginatePatientByDoctorId($doctorId): LengthAwarePaginator
    {
        return $this->patientRepository->paginatePatientsByDoctorId($doctorId);
    }


    public function queryPaginatePatientByDoctorId($doctorId, $query): LengthAwarePaginator
    {
        return $this->patientRepository->queryPaginatePatientsByDoctorId($doctorId, $query);
    }

    /**
     * Get patient by doctor id and patient id
     *
     * @param [type] $doctorId
     * @param [type] $id
     * @return Patient|null
     */
    public function getPatientByDoctorId($doctorId, $id): Patient|null
    {
        $patient = $this->patientRepository->getPatientByDoctorId($doctorId, $id);
        if (!$patient)
            throw new NotFoundHttpException('Patient not found', null, Response::HTTP_NOT_FOUND);

        return $patient;
    }


    /**
     * Update patient by doctor id and patient id
     * 
     */
    public function updatePatientByDoctorId($id, array $data): Patient|null
    {
        $doctorId = $data['doctor_id'];
        $this->getPatientByDoctorId($doctorId, $id);

        $updated = $this->patientRepository->update($id, $data);
        if (!$updated) {
            throw ValidationException::withMessages(['message' => 'Patient was not updated']);
        }
        return $this->getPatientByDoctorId($doctorId, $id);
    }


    /**
     * Delete patient by doctor id and patient id
     */
    public function deletePatientByDoctorId($doctorId, $id): bool|null
    {
        $this->getPatientByDoctorId($doctorId, $id);
        return $this->patientRepository->delete($id);
    }

    /**
     * Get patient by appointment
     */
    public function getPatientByAppointment($doctorId, $patientId): Patient|null
    {
        $patient = $this->patientRepository->getPatientByAppointment($doctorId, $patientId);
        if (!$patient)
            throw new NotFoundHttpException('Patient not found', null, Response::HTTP_NOT_FOUND);

        return $patient;
    }


    /**
     * Get medical records by patient and appointments Completed status
     * @param int $patientId
     * @return Patient|null
     */
    public function getMedicalRecords(int $doctorId, string $patientId, $appointmentStatus = AppointmentStatusEnum::COMPLETED->value): ?Patient
    {
        $patient = $this->patientRepository->getPatientByDoctorId($doctorId, $patientId);
        if (!$patient)
            throw new ModelNotFoundException("Patient doesn't exist", Response::HTTP_NOT_FOUND);
        

        return $patient->with([
            'appointments' => function ($query) use ($appointmentStatus) {
                $query
                    // Seleccionamos solo las columnas necesarias de la cita
                    ->select('id', 'patient_id', 'doctor_id', 'date_time', 'status', 'reason', 'duration_minutes')
                    // Campo calculado: has_prescription = true si existe una prescripción asociada
                    ->withExists(['prescription as has_prescription'])
                    ->with([
                        'medicalRecord' => function($mrQuery) {
                            $mrQuery->select('id','appointment_id');
                        }
                    ])
                    ->where('status', $appointmentStatus)
                    ->orderBy('date_time', 'desc');
            }
        ])->find($patientId);
    }


    /**
     * Get total patients 
     * @param int $doctorId
     * @return int
     */
    public function getTotalPatientsByDoctorId(int $doctorId): int
    {
        return $this->patientRepository->getTotalPatientsByDoctorId($doctorId);
    }
}
