<?php

namespace App\Http\Resources;

use App\Enum\AppointmentStatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentPaginateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'status_label' => AppointmentStatusEnum::translateByValue($this->status),
            'patient' => $this->patient->name . ' ' . $this->patient->lastname,
            'date' => Carbon::createFromFormat('Y-m-d', $this->date)->format('D, d M Y'), // format Mon, 01 Jan 2023, // format Mon, 01 Jan 2023 
            'time' => $this->time,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
