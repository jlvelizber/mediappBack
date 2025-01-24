<?php

namespace App\Repositories\Interface;

interface MedicalRecordRepositoryInterface
{
    public function getByPatient(int $patientId);
}
