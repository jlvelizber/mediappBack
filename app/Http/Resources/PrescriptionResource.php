<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [...parent::toArray($request), 
        $this->mergeWhen($this->items, [
                'items' => $this->items->map(function ($item) {
                    return [
                        // 'id' => $item->id,
                        'medication_name' => $item->medication_name,
                        'dosage' => $item->dosage,
                        'frequency' => $item->frequency,
                        'duration' => $item->duration,
                        'notes' => $item->notes,
                    ];
                }),
            ])];
    }
}
