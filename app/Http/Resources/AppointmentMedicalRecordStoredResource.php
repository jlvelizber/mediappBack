<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentMedicalRecordStoredResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            ...parent::toArray($request),
            'prescription' => new PrescriptionResource($this->appointment?->prescription),
        ];
    }
}
