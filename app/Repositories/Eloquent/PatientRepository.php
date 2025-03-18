<?php

namespace App\Repositories\Eloquent;

use App\Models\Patient;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\PatientRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PatientRepository extends BaseRepository implements PatientRepositoryInterface
{
    public function __construct(Patient $model)
    {
        parent::__construct($model);
    }

    /**
     * Get patients by doctor id
     *
     * @param integer $doctorId
     * @return Collection
     */
    public function paginatePatientsByDoctorId(int $doctorId): LengthAwarePaginator
    {
        return $this->model->where('doctor_id', $doctorId)->paginate();
    }
}
