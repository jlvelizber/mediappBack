<?php

namespace App\Repositories\Interface;
use \Illuminate\Database\Eloquent\Collection;

interface AppointmentRepositoryInterface
{
    /**
     * @param int $doctorId
     * @return Collection
     */
    public function findFutureAppointments(int $doctorId): ?Collection;
}
