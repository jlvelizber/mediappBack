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


    public function getByPatient(int $patientId): ?Collection
    {
        return $this->model->where('patient_id', $patientId)->get();
    }


}
