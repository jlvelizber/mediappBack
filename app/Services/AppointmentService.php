<?php

namespace App\Services;

use App\Enum\AppointmentStatusEnum;
use App\Models\Appointment;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppointmentService
{
    protected AppointmentRepositoryInterface $appointmentRepository;

    public function __construct(appointmentRepositoryInterface $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * Get future appointments
     * @param int $doctorId
     */
    public function getFutureAppointments(int $doctorId): ?Collection
    {
        return $this->appointmentRepository->findFutureAppointments($doctorId);
    }

    public function getAllAppointments(): Collection
    {
        return $this->appointmentRepository->all();
    }

    public function getAllAppointmentsByDoctorId(int $doctorId): Collection
    {
        return $this->appointmentRepository->getAllByDoctorId($doctorId);
    }

    public function getAppointmentById($id, ?int $doctorId = null)
    {
        $appointment = $this->appointmentRepository->find($id);
        if (!$appointment) {
            throw new NotFoundHttpException("Appointment not found", null, Response::HTTP_NOT_FOUND);
        }

        if ($doctorId !== null && (int) $appointment->doctor_id !== $doctorId) {
            throw new NotFoundHttpException("Appointment not found", null, Response::HTTP_NOT_FOUND);
        }

        return $appointment;
    }

    public function createAppointment(array $data): Appointment
    {
        return $this->appointmentRepository->create($data);
    }

    public function updateAppointment($id, array $data, ?int $doctorId = null): Appointment|null
    {
        $this->getAppointmentById($id, $doctorId);
        $wasUpdated = $this->appointmentRepository->update($id, $data);
        if (!$wasUpdated) {
            throw ValidationException::withMessages(['appointment' => 'Appointment was not updated']);
        }
        return $this->getAppointmentById($id, $doctorId);
    }

    public function deleteAppointment($id, ?int $doctorId = null)
    {
        $this->getAppointmentById($id, $doctorId);

        return $this->appointmentRepository->delete($id);
    }

    /**
     * Get all appointments by doctor id
     * @param mixed $doctorId
     * @return LengthAwarePaginator
     */
    public function paginateAppointmentsByDoctor($doctorId): LengthAwarePaginator
    {
        return $this->appointmentRepository->paginateLastAppointmentsByDoctor($doctorId);
    }



    /**
     * Get all appointments by patient id
     * @param mixed $doctorId
     * @param string $query
     * @return LengthAwarePaginator
     */
    public function queryPaginateAppointmentByDoctorId($doctorId, string $query): LengthAwarePaginator
    {
        return $this->appointmentRepository->queryPaginateAppointmentByDoctorId($doctorId, $query);
    }

    /**
     * Update the status of an appointment
     * @param int $appointmentId
     * @param string $status
     */
    public function updateAppointmentStatus(int $appointmentId, string $status, ?int $doctorId = null): Appointment
    {
        $appointment = $this->getAppointmentById($appointmentId, $doctorId);

        $appointment->status = $status;
        if (!$appointment->save()) {
            throw ValidationException::withMessages(['appointment' => 'Appointment status was not updated']);
        }

        return $appointment;
    }

    /**
     * Get appointments by date range
     * @param string| int $doctorId
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getAppointmentsByDateRange(string|int $doctorId, string $startDate, string $endDate): Collection
    {
        return $this->appointmentRepository->queryAppointmentByRangeDate($doctorId, $startDate, $endDate);
    }


    /**
     * Get completed appointments from the start of the day until now
     * @param string|int $doctorId
     */
    public function getTodayCompletedAppointments(string|int $doctorId): Collection
    {
        $startOfDay = Carbon::now()->startOfDay();
        $now = Carbon::now();

        return $this->appointmentRepository->getCompletedAppointmentsBetween(
            (int) $doctorId,
            $startOfDay,
            $now
        );
    }


      /**
     * Weekly resume
     * @param int $doctorId
     * @param string $weekStart
     * @param string $weekEnd
     * @return int
     */
    public function getWeeklyResume(int $doctorId, string $weekStart, string $weekEnd): ?SupportCollection
    {
        return $this->appointmentRepository->getWeeklyResume($doctorId, $weekStart, $weekEnd);
    }

    /**
     * Get completed appointments grouped by day for the last 30 days
     * @param int $doctorId
     * @return Collection
     */
    public function getCompletedAppointmentsLast30Days(int $doctorId): Collection
    {
        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        return $this->appointmentRepository->getCompletedAppointmentsByDay($doctorId, $startDate, $endDate);
    }
}
