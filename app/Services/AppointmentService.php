<?php

namespace App\Services;

use App\Models\Appointment;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
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
}
