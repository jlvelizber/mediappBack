<?php

namespace App\Repositories\Eloquent;

use App\Enum\AppointmentStatusEnum;
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

        $dateToday = now()->format('Y-m-d H:i:s');
        return $this->model->where('doctor_id', $doctorId)
            ->where('date_time', '>=', $dateToday)
            ->where('status', AppointmentStatusEnum::PENDING)
            ->orderBy('date_time', 'asc')
            ->get();
    }


}
