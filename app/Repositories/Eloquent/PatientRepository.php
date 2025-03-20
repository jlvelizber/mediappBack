<?php

namespace App\Repositories\Eloquent;

use App\Models\Patient;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\PatientRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;

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

    /**
     * Summary of queryPaginatePatientsByDoctorId
     * @param int $doctorId
     * @param string $query
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function queryPaginatePatientsByDoctorId(int $doctorId, string $query): LengthAwarePaginator
    {
        return $this->model->where('doctor_id', $doctorId)
            ->whereAny([
                'name',
                'lastname',
                'document',
                'email'
            ], 'like', "%{$query}%")
            ->paginate();
    }
}
