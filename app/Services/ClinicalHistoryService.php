<?php
namespace App\Services;

use App\Models\ClinicalHistory;
use App\Repositories\Interface\ClinicalHistoryRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClinicalHistoryService
{
    protected ClinicalHistoryRepositoryInterface $clinicalHistoryRepositoryInterface;

    public function __construct(ClinicalHistoryRepositoryInterface $clinicalHistoryRepositoryInterface)
    {
        $this->clinicalHistoryRepositoryInterface = $clinicalHistoryRepositoryInterface;
    }

    /**
     * Get all clinical histories
     */
    public function getAllClinicalHistories()
    {
        return $this->clinicalHistoryRepositoryInterface->all();
    }

    /**
     * Get clinical history by appointment ID
     * @param int $appointmentId
     */
    public function getClinicalHistoryByAppointmentId(int $appointmentId): ClinicalHistory
    {
        $clinicalHistory = $this->clinicalHistoryRepositoryInterface->find($appointmentId);
        if (!$clinicalHistory) {
            throw new NotFoundHttpException("Clinical history not found", null, Response::HTTP_NOT_FOUND);
        }
        return $clinicalHistory;
    }

    /**
     * Create a new clinical history entry
     * @param array $data
     */
    public function createClinicalHistory(array $data)
    {
        return $this->clinicalHistoryRepositoryInterface->create($data);
    }


    /**
     * Update an existing clinical history entry
     * @param int $id
     * @param array $data
     */
    public function updateClinicalHistory(int $id, array $data)
    {
        $this->getClinicalHistoryByAppointmentId($id);
        $wasUpdated = $this->clinicalHistoryRepositoryInterface->update($id, $data);
        if (!$wasUpdated) {
            throw ValidationException::withMessages(['clinical-history' => 'Clinical History was not updated']);
        }
        return $this->getClinicalHistoryByAppointmentId($id);
    }

    /**
     * Delete a clinical history entry
     * @param int $id
     */
    public function deleteClinicalHistory(int $id)
    {
        $this->getClinicalHistoryByAppointmentId($id);
        return $this->clinicalHistoryRepositoryInterface->delete($id);
    }
}
