<?php

namespace App\Http\Resources;

use App\Enum\AppointmentStatusEnum;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public bool $withPatientResource;
    public bool $gonnaEditAppoint;
    public function __construct(Appointment $appointment, bool $withPatientResource = true, bool $gonnaEditAppoint = false)
    {
        parent::__construct($appointment);
        $this->withPatientResource = $withPatientResource;
        $this->gonnaEditAppoint = $gonnaEditAppoint;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->status);
        return [
            'id' => $this->id,
            'status' => $this->status,
            'status_label' => AppointmentStatusEnum::translateByValue($this->status),
            $this->mergeWhen($this->gonnaEditAppoint, [
                'patient_id' => $this->patient->id,
            ]),
            $this->mergeWhen($this->withPatientResource, [
                'patient' => new PatientResource($this->patient, false),
            ]),
            'date' => $this->date_time->format('D, d M Y'), // format Mon, 01 Jan 2023, // format Mon, 01 Jan 2023 
            'time' => $this->date_time->format('H:i'),
            'duration_minutes' => $this->duration_minutes,
            'date_time' => $this->date_time->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'reason' => $this->reason,
            $this->mergeWhen($this->medicalRecord?->id, [
                'medical_record' => new MedicalRecordResource($this->medicalRecord)
            ]),
            $this->mergeWhen($this->prescription?->id, [
                'prescription' => new PrescriptionResource($this->prescription)
            ]),
        ];
    }
}
