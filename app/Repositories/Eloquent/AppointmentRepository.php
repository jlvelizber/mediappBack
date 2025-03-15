<?php

namespace App\Repositories\Eloquent;

use App\Enum\AppointmentStatusEnum;
use App\Models\Appointment;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\DoctorConfigurationRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AppointmentRepository extends BaseRepository implements AppointmentRepositoryInterface
{
    private DoctorConfigurationRepositoryInterface $doctorConfigurationRepositoryInterface;

    public function __construct(Appointment $model, DoctorConfigurationRepositoryInterface $doctorConfigurationRepositoryInterface)
    {
        parent::__construct($model);
        $this->doctorConfigurationRepositoryInterface = $doctorConfigurationRepositoryInterface;
    }

    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        if (!isset($data['status'])) {
            $data['status'] = AppointmentStatusEnum::PENDING;
        }

        if (!isset($data['duration_minutes'])) {
            $keyColumn = 'default_appointment_duration';
            $duration = $this->doctorConfigurationRepositoryInterface->getByDoctorIdAndKeyValue($data['doctor_id'], $keyColumn)->$keyColumn ?? config('mediapp.doctor_configuration.default_appointment_duration');
            $data['duration_minutes'] = $duration;
        }
        return $this->model->create($data);
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


    /**
     * Summary of getDoctorAppointments
     * @param int $doctorId
     * @param mixed $date
     * @return Collection<Model>
     */
    public function getDoctorAppointmentsByDate(int $doctorId, Carbon $date): ?Collection
    {
        return $this->model->where('doctor_id', $doctorId)
            ->whereDate('date_time', $date)
            ->where('status', AppointmentStatusEnum::PENDING)
            ->get();
    }


}
