<?php
namespace App\Services;

use App\Events\MedicalRecordCreatedEvent;
use App\Models\MedicalRecord;
use App\Repositories\Interface\MedicalRecordRepositoryInterface;
use App\Repositories\Interface\PrescriptionRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MedicalRecordService
{
    private MedicalRecordRepositoryInterface $medicalRecordRepositoryInterface;
    private PrescriptionRepositoryInterface $prescriptionRepositoryInterface;

    public function __construct(
        MedicalRecordRepositoryInterface $medicalRecordRepositoryInterface,
        PrescriptionRepositoryInterface $prescriptionRepositoryInterface
    ) {
        $this->medicalRecordRepositoryInterface = $medicalRecordRepositoryInterface;
        $this->prescriptionRepositoryInterface = $prescriptionRepositoryInterface;
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
    public function createMedicalRecord(array $data): MedicalRecord
    {
        $medicalRecord = $this->medicalRecordRepositoryInterface->create($data);
        if (!$medicalRecord) {
            throw new ModelNotFoundException(
                "Clinical history could not be created",
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // Prescription creation can be handled here if needed
        $prescription = $this->prescriptionRepositoryInterface->create($data['prescription']);
        if (!$prescription) {
            throw new ModelNotFoundException(
                "Prescription could not be created",
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // Dispatch the event after creating the medical record
        event(new MedicalRecordCreatedEvent($medicalRecord));

        return $medicalRecord;
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
}
