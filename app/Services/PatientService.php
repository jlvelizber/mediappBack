<?php

namespace App\Services;

use App\Models\Patient;
use App\Repositories\Interface\PatientRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

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
        return $this->patientRepository->find($id);
    }

    public function createPatient(array $data): Patient
    {

        return $this->patientRepository->create($data);
    }

    public function updatePatient($id, array $data): Patient|null
    {
        $updated = $this->patientRepository->update($id, $data);
        if (!$updated) {
            throw ValidationException::withMessages(['message' => 'Patient not found']);
        }
        return $this->getPatientById($id);
    }

    public function deletePatient($id)
    {
        return $this->patientRepository->delete($id);
    }
}
