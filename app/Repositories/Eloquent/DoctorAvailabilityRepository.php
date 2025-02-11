<?php

namespace App\Repositories\Eloquent;

use App\Models\DoctorAvailability;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\DoctorAvailabilityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;


class DoctorAvailabilityRepository extends BaseRepository implements DoctorAvailabilityRepositoryInterface
{
    public function __construct(DoctorAvailability $model)
    {
        parent::__construct($model);
    }

    public function getByDoctor(int $doctorId): ?Collection
    {
        return $this->model->where('doctor_id', $doctorId)->get();
    }
}
