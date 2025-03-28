<?php

namespace App\Services;

use App\Models\Patient;
use App\Repositories\Interface\PatientRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
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

    public function deletePatient($id)
    {
        return $this->patientRepository->delete($id);
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
}
