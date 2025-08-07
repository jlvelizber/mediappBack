<?php
namespace App\Repositories\Eloquent;

use App\Models\Prescription;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\PrescriptionRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class PrescriptionRepository extends BaseRepository implements PrescriptionRepositoryInterface
{
    public function __construct(Prescription $model)
    {
        parent::__construct($model);
    }


    public function create(array $data): Prescription
    {
        $prescription = $this->model->create($data);
        if (!$prescription) {
            throw new ModelNotFoundException(
                "Prescription could not be created",
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // save items
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $prescription->items()->create($item);
            }
        }

        return $prescription;
    }


}