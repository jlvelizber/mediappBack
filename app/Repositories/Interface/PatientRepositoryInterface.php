<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Patient;


interface PatientRepositoryInterface
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

}
