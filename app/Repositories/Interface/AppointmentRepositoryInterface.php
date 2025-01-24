<?php

namespace App\Repositories\Interface;

interface AppointmentRepositoryInterface
{
    /**
     * @param int $doctorId
     * @return mixed
     */
    public function findFutureAppointments(int $doctorId);
}
