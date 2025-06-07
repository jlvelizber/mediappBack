<?php

namespace App\Repositories\Interface;
use Carbon\Carbon;
use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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



    /**
     * @param int $doctorId
     * @return LengthAwarePaginator
     */
    public function paginateLastAppointmentsByDoctor(int $doctorId): LengthAwarePaginator;


    /**
     * @param int $doctorId
     * @param string $query
     * @return LengthAwarePaginator
     */
    public function queryPaginateAppointmentByDoctorId(int $doctorId, string $query): LengthAwarePaginator;


    /**
     *
     * @void
     */
    public function getPreviousAppointmentsNotConfirmed(): Collection;


}
