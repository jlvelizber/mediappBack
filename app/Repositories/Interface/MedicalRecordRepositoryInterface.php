<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Collection;

interface MedicalRecordRepositoryInterface extends RootRepositoryInterface
{
    /**
     * Get all medical records by doctor id
     * @param int $doctorId
     * @return Collection
     */
    public function getByDoctorId(int $doctorId): Collection;

    /**
     * Get a medical record by id and doctor id
     * @param int $medicalRecordId
     * @param int $doctorId
     */
    public function getByIdAndDoctorId(int $medicalRecordId, int $doctorId): ?\App\Models\MedicalRecord;

    /**
     * Summary of getByPatient
     * @param int $patientId
     * @return void
     */
    public function getByPatient(int $patientId): ?Collection;
}
