<?php

namespace App\Http\Resources;

use App\Enum\AppointmentStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
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
            'patient' => $this->patient->name . ' ' . $this->patient->lastname,
            'date' => $this->date_time->format('D, d M Y'), // format Mon, 01 Jan 2023, // format Mon, 01 Jan 2023 
            'time' => $this->date_time->format('H:i'),
            'duration_minutes' => $this->duration_minutes,
            'date_time' => $this->date_time->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'medical_record_id' => $this->medicalRecord?->id,
        ];
    }
}
