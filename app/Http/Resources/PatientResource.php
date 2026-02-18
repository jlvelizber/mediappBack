<?php

namespace App\Http\Resources;

use App\Enum\AppointmentStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this);
        return [
            ...parent::toArray($request),
            'full_name' => $this->full_name,
            'age' => $this->age,
            'appointments' => $this->appointments->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'date' => $appointment->date_time->format('D, d M Y'), // format Mon, 01 Jan 2023, // format Mon, 01 Jan 2023 
                    'time' => $appointment->date_time->format('H:i'),
                    'reason' => $appointment->reason,
                    'status_label' => AppointmentStatusEnum::translateByValue($appointment->status),
                    'has_prescription' => $appointment->has_prescription,
                    'medicalRecord' => $appointment->medicalRecord
                ];
            })->toArray(),
        ];
    }
}
