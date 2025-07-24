<?php
namespace App\Repositories\Eloquent;

use App\Models\ClinicalHistory;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\ClinicalHistoryRepositoryInterface;

class ClinicalHistoryRepository extends BaseRepository implements ClinicalHistoryRepositoryInterface
{
    public function __construct(ClinicalHistory $model)
    {
        parent::__construct($model);
    }
}