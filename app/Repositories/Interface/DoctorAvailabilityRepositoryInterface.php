<?php

namespace App\Repositories\Interface;
use \Illuminate\Database\Eloquent\Collection;

interface DoctorAvailabilityRepositoryInterface
{
    /**
     * @param int $doctorId
     * @return Collection
     */
    public function getByDoctor(int $doctorId): ?Collection;
}
