<?php

namespace App\Repositories\Eloquent;

use App\Models\Appointment;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class AppointmentRepository extends BaseRepository implements AppointmentRepositoryInterface
{
    public function __construct(Appointment $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $doctorId
     * @return mixed
     */
    public function findFutureAppointments(int $doctorId): ?Collection
    {
        return $this->model->where('doctor_id', $doctorId)
            ->where('date_time', '>=', now())
            ->get();
    }


}
