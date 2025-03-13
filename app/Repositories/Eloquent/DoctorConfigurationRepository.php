<?php

namespace App\Repositories\Eloquent;

use App\Models\DoctorConfiguration;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\DoctorConfigurationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DoctorConfigurationRepository extends BaseRepository implements DoctorConfigurationRepositoryInterface
{
    public function __construct(DoctorConfiguration $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all configurations by doctor id
     * @param mixed $doctorId
     * @return Collection
     */
    public function getByDoctorId($doctorId): Collection
    {
        return $this->model->where('doctor_id', $doctorId)->get();
    }

    /**
     * Get configuration by doctor id and configuration key
     * @param mixed $doctorId
     * @param string $key
     * @return DoctorConfiguration|null
     */
    public function getByDoctorIdAndKeyValue($doctorId, $key): DoctorConfiguration|null
    {
        return $this->model->select($key)
            ->where('doctor_id', $doctorId)
            ->first();
    }


}
