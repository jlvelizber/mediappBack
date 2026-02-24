<?php

namespace App\Http\Resources;

use App\Enum\AppointmentStatusEnum;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    
    public function __construct(Patient $patient, bool $withAppointments = true)
    {
        parent::__construct($patient);
        $this->withAppointments = $withAppointments;
    }
    
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
            $this->mergeWhen($this->withAppointments, [
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
            ])
        ];
    }
}
