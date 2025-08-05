<?php
namespace App\Services;

use App\Models\MedicalRecord;
use App\Repositories\Interface\MedicalRecordRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MedicalRecordService
{
    protected MedicalRecordRepositoryInterface $medicalRecordRepositoryInterface;

    public function __construct(MedicalRecordRepositoryInterface $medicalRecordRepositoryInterface)
    {
        $this->medicalRecordRepositoryInterface = $medicalRecordRepositoryInterface;
    }

    /**
     * Get all clinical histories
     */
    public function getAllClinicalHistories()
    {
        return $this->medicalRecordRepositoryInterface->all();
    }

    /**
     * Get clinical history by appointment ID
     * @param int $appointmentId
     */
    public function getClinicalHistoryByAppointmentId(int $appointmentId): MedicalRecord
    {
        $clinicalHistory = $this->medicalRecordRepositoryInterface->find($appointmentId);
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
        return $this->medicalRecordRepositoryInterface->create($data);
    }


    /**
     * Update an existing clinical history entry
     * @param int $id
     * @param array $data
     */
    public function updateClinicalHistory(int $id, array $data)
    {
        $this->getClinicalHistoryByAppointmentId($id);
        $wasUpdated = $this->medicalRecordRepositoryInterface->update($id, $data);
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
        return $this->medicalRecordRepositoryInterface->delete($id);
    }

    /**
     * Get medical records by patient
     * @param int $patientId
     * @return Collection|null
     */
    public function getMedicalRecordsByPatient(int $patientId): ?Collection
    {
        return $this->medicalRecordRepository->getByPatient($patientId);
    }
}
