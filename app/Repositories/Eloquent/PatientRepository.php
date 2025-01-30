<?php

namespace App\Repositories\Eloquent;

use App\Models\Patient;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\PatientRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PatientRepository extends BaseRepository implements PatientRepositoryInterface
{
    public function __construct(Patient $model)
    {
        parent::__construct($model);
    }
}
