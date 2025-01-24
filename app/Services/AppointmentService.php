<?php

namespace App\Services;

use App\Repositories\Interface\AppointmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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
}
