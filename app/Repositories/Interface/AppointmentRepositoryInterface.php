<?php

namespace App\Repositories\Interface;
use Carbon\Carbon;
use \Illuminate\Database\Eloquent\Collection;

interface AppointmentRepositoryInterface
{
    /**
     * @param int $doctorId
     * @return Collection
     */
    public function findFutureAppointments(int $doctorId): ?Collection;


    /**
     * Summary of getDoctorAppointments
     * @param int $doctorId
     * @return Collection<Model>
     */
    public function getDoctorAppointmentsByDate(int $doctorId, Carbon $date): ?Collection;
}
