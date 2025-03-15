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

    /**
     * Summary of getFirstByDoctorAndDay
     * @param int $doctorId
     * @param string $dayOfWeek
     * @return TModel|null
     */
    public function getFirstByDoctorAndDay(int $doctorId, string $dayOfWeek): DoctorAvailability|null
    {
        return $this->model->where('doctor_id', $doctorId)->where('day_of_week', $dayOfWeek)->first();
    }


}
