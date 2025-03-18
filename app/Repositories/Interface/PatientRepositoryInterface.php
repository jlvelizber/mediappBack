<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


interface PatientRepositoryInterface
{
    /**
     * Undocumented function
     *
     * @param integer $doctorId
     * @return Collection
     */
    public function paginatePatientsByDoctorId(int $doctorId): LengthAwarePaginator;
}
