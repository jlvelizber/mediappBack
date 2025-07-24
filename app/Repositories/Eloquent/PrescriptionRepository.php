<?php
namespace App\Repositories\Eloquent;

use App\Models\Prescription;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\PrescriptionRepositoryInterface;

class PrescriptionRepository extends BaseRepository implements PrescriptionRepositoryInterface
{
    public function __construct(Prescription $model)
    {
        parent::__construct($model);
    }


}