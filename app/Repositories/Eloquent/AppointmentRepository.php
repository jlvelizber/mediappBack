<?php

namespace App\Repositories\Eloquent;

use App\Models\Appointment;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Enum\UserRoleEnum;

class AppointmentRepository extends BaseRepository implements AppointmentRepositoryInterface
{
    public function __construct(AppoAppointment $model)
    {
        parent::__construct($model);
    }

     /**
     * @param int $doctorId
     * @return mixed
     */
    public function findFutureAppointments(int $doctorId)
    {
        return $this->model->where('doctor_id', $doctorId)
            ->where('date', '>=', now())
            ->get();
    }

   
}
