<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface DoctorConfigurationRepositoryInterface extends RootRepositoryInterface
{

    /**
     * Get all configurations by doctor id
     * @param $doctorId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByDoctorId($doctorId): Collection;

    /**
     * Get configuration by doctor id and configuration key
     */
    public function getByDoctorIdAndKeyValue($doctorId, $key): Model|null;


}
