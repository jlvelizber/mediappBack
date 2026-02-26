<?php

namespace App\Repositories\Eloquent;

use App\Models\MedicalRecord;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\MedicalRecordRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MedicalRecordRepository extends BaseRepository implements MedicalRecordRepositoryInterface
{
    public function __construct(MedicalRecord $model)
    {
        parent::__construct($model);
    }

    public function getByDoctorId(int $doctorId): Collection
    {
        return $this->model
            ->whereHas('appointment', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->get();
    }

    public function getByIdAndDoctorId(int $medicalRecordId, int $doctorId): ?MedicalRecord
    {
        return $this->model
            ->where('id', $medicalRecordId)
            ->whereHas('appointment', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->first();
    }


    public function getByPatient(int $patientId): ?Collection
    {
        return $this->model->where('patient_id', $patientId)->get();
    }


}
