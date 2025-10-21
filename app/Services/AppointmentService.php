<?php

namespace App\Services;

use App\Enum\AppointmentStatusEnum;
use App\Models\Appointment;
use App\Repositories\Interface\AppointmentRepositoryInterface;
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

    public function getAppointmentById($id)
    {
        $appointment = $this->appointmentRepository->find($id);
        if (!$appointment)
            throw new NotFoundHttpException("Appointment not found", null, Response::HTTP_NOT_FOUND);
        return $appointment;
    }

    public function createAppointment(array $data): Appointment
    {
        return $this->appointmentRepository->create($data);
    }

    public function updateAppointment($id, array $data): Appointment|null
    {
        $this->getAppointmentById($id);
        $wasUpdated = $this->appointmentRepository->update($id, $data);
        if (!$wasUpdated) {
            throw ValidationException::withMessages(['appointment' => 'Appointment was not updated']);
        }
        return $this->getAppointmentById($id);
    }

    public function deleteAppointment($id)
    {
        $this->getAppointmentById($id);

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
    public function updateAppointmentStatus(int $appointmentId, string $status): Appointment
    {
        $appointment = $this->getAppointmentById($appointmentId);

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
}
