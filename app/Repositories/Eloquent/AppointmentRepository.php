<?php

namespace App\Repositories\Eloquent;

use App\Enum\AppointmentStatusEnum;
use App\Models\Appointment;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\DoctorConfigurationRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Log;

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

    /**
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function paginateLastAppointmentsByDoctor(int $doctorId): LengthAwarePaginator
    {
        return $this->model->where('doctor_id', $doctorId)
            ->with([
                'patient' => function ($query) {
                    $query->select('id', 'name', 'lastname');
                }
            ])
            ->select(
                'id',
                'status',
                'patient_id',
                'created_at',
                'duration_minutes',
                'date_time'
            )
            ->selectRaw('DATE_FORMAT(date_time, "%H:%i") as time')
            ->selectRaw('DATE_FORMAT(date_time, "%Y-%m-%d") as date')
            ->orderByRaw("FIELD(status, '" . AppointmentStatusEnum::CONFIRMED->value . "', '" . AppointmentStatusEnum::PENDING->value . "', '" . AppointmentStatusEnum::CANCELLED->value . "') ASC")
            ->orderByRaw('ABS(TIMESTAMPDIFF(SECOND, date_time, NOW()))')
            ->paginate(config('mediapp.appointment.paginate'));
    }

    /**
     * @param int $doctorId
     * @param string $query
     * @return LengthAwarePaginator
     */
    public function queryPaginateAppointmentByDoctorId(int $doctorId, string $query): LengthAwarePaginator
    {
        return $this->model->where('doctor_id', $doctorId)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('id', 'like', "%$query%")
                    ->orWhereHas('patient', function ($q) use ($query) {
                        $q->where('name', 'like', "%$query%")
                            ->orWhere('lastname', 'like', "%$query%");
                    });
            })
            ->orderByNearby()
            ->orderBy('status', 'asc')
            ->with([
                'patient' => function ($query) {
                    $query->select('id', 'name', 'lastname');
                }
            ])
            ->select(
                'id',
                'status',
                'patient_id',
                'created_at',
            )
            ->selectRaw('DATE_FORMAT(date_time, "%H:%i") as time')
            ->selectRaw('DATE_FORMAT(date_time, "%Y-%m-%d") as date')
            ->paginate(config('mediapp.appointment.paginate'));
    }


    /**
     * Change the status of previous appointments that are not confirmed to cancelled
     *
     * @return Collection
     */
    public function getPreviousAppointmentsNotConfirmed(): Collection
    {
        $dateToday = now()->startOfDay()->format('Y-m-d 00:00:00');
        Log::info('Cancelling previous appointments not confirmed before: ' . $dateToday);
        return $this->model->where('status', AppointmentStatusEnum::PENDING)
            ->where('date_time', '<', $dateToday)
            ->get();

    }

    /**
     * Get appointments by date range
     * @param string|int $doctorId
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function queryAppointmentByRangeDate(string|int $doctorId, string $startDate, string $endDate): Collection
    {
        return $this->model->whereBetween('date_time', [$startDate, $endDate])
            ->with([
                'patient' => function ($query) {
                    $query->select('id', 'name', 'lastname');
                }
            ])
            ->select(
                'id',
                'status',
                'patient_id',
                'created_at',
                'duration_minutes',
                'date_time'
            )
            ->where('doctor_id', $doctorId)
            ->whereBetween('date_time', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ])
            ->selectRaw('DATE_FORMAT(date_time, "%H:%i") as time')
            ->selectRaw('DATE_FORMAT(date_time, "%Y-%m-%d") as date')
            ->orderBy('date_time', 'asc')
            ->get();
    }


}
