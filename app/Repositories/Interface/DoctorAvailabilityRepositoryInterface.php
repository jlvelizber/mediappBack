<?php

namespace App\Repositories\Interface;
use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface DoctorAvailabilityRepositoryInterface
{
    /**
     * @param int $doctorId
     * @return Collection
     */
    public function getByDoctor(int $doctorId): ?Collection;


    public function getFirstByDoctorAndDay(int $doctorId, string $dayOfWeek): Model|null;
}
