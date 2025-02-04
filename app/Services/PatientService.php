<?php

namespace App\Services;

use App\Models\Patient;
use App\Repositories\Interface\PatientRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PatientService
{
    protected PatientRepositoryInterface $patientRepository;

    public function __construct(patientRepositoryInterface $patientRepository)
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
}
