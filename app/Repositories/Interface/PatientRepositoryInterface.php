<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Patient;


interface PatientRepositoryInterface extends RootRepositoryInterface
{
    /**
     * Undocumented function
     *
     * @param integer $doctorId
     * @return Collection
     */
    public function paginatePatientsByDoctorId(int $doctorId): LengthAwarePaginator;


    /**
     * Paginate and query data 
     */
    public function queryPaginatePatientsByDoctorId(int $doctorId, string $query): LengthAwarePaginator;

    /**
     * Get patient by doctor id and patient id
     *
     * @param [type] $doctorId
     * @param [type] $id
     * @return Patient|null
     */
    public function getPatientByDoctorId($doctorId, $id): Patient|null;

    /**
     * Get all patients by doctor id
     *
     * @param [type] $doctorId
     * @return Collection
     */
    public function getAllPatientsByDoctorId($doctorId): Collection;


    /**
     * Get patient by appointment id
     *
     * @param int $doctorId
     * @param int $appointmentId
     * @return Patient
     */
    public function getPatientByAppointment($doctorId, $appointmentId): Patient|null;


    /**
     * Get total patients by doctorId
     * @param int $doctorId
     */
    public function getTotalPatientsByDoctorId($doctorId): int;

}
