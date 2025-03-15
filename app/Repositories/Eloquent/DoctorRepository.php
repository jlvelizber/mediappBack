<?php

namespace App\Repositories\Eloquent;

use App\Models\Doctor;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\DoctorRepositoryInterface;

class DoctorRepository extends BaseRepository implements DoctorRepositoryInterface
{
    public function __construct(Doctor $model)
    {
        parent::__construct($model);
    }
}
