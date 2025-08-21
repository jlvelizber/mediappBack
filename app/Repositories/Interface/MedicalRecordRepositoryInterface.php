<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Collection;

interface MedicalRecordRepositoryInterface extends RootRepositoryInterface
{
    /**
     * Summary of getByPatient
     * @param int $patientId
     * @return void
     */
    public function getByPatient(int $patientId): ?Collection;
}
