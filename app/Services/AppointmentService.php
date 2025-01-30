<?php

namespace App\Services;

use App\Repositories\Interface\AppointmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

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
        return $this->appointmentRepository->find($id);
    }

    public function createAppointment(array $data)
    {
        // Validaciones personalizadas
        if (!$data['doctor_id'] || !$data['patient_id']) {
            throw ValidationException::withMessages(['error' => 'El doctor y el paciente son obligatorios.']);
        }

        return $this->appointmentRepository->create($data);
    }

    public function updateAppointment($id, array $data)
    {
        return $this->appointmentRepository->update($id, $data);
    }

    public function deleteAppointment($id)
    {
        return $this->appointmentRepository->delete($id);
    }
}
