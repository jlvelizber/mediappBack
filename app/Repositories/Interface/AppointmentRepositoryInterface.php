<?php

namespace App\Repositories\Interface;
use Carbon\Carbon;
use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AppointmentRepositoryInterface extends RootRepositoryInterface
{
    /**
     * @param int $doctorId
     * @return Collection
     */
    public function findFutureAppointments(int $doctorId): ?Collection;


    /**
     * @param int $doctorId
     * @return Collection
     */
    public function getAllFutureAppointments(): ?Collection;


    /**
     * Summary of getDoctorAppointments
     * @param int $doctorId
     * @return Collection<Model>
     */
    public function getDoctorAppointmentsByDate(int $doctorId, Carbon $date): ?Collection;

    /**
     * Get completed appointments between two datetimes
     * @param int $doctorId
     * @param Carbon $startDateTime
     * @param Carbon $endDateTime
     */
    public function getCompletedAppointmentsBetween(int $doctorId, Carbon $startDateTime, Carbon $endDateTime): Collection;



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


    /**
     * @param $startDate
     * @param $endDate
     * @return Collection
     */
    public function queryAppointmentByRangeDate(string|int $doctorId, string $startDate, string $endtDate): Collection;


    /**
     * Weekly resume
     * @param int $doctorId
     * @param string $weekStart
     * @param string $weekEnd
     * @return int
     */
    public function getWeeklyResume(int $doctorId, string $weekStart, string $weekEnd): SupportCollection;

    /**
     * Get completed appointments grouped by day for the last 30 days
     * @param int $doctorId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return Collection
     */
    public function getCompletedAppointmentsByDay(int $doctorId, Carbon $startDate, Carbon $endDate): Collection;



}
