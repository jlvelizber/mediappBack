<?php

namespace App\Services;

use App\Repositories\Interface\MedicalRecordRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MedicalRecordService
{
    protected MedicalRecordRepositoryInterface $medicalRecordRepository;

    public function __construct(MedicalRecordRepositoryInterface $medicalRecordRepository)
    {
        $this->medicalRecordRepository = $medicalRecordRepository;
    }
    /**
     * Get medical records by patient
     * @param int $patientId
     * @return Collection|null
     */
    public function getMedicalRecordsByPatient(int $patientId): ?Collection
    {
        return $this->medicalRecordRepository->getByPatient($patientId);
    }
}
