<?php

namespace App\Http\Resources;

use App\Enum\DaysWeekEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Lang;

class DoctorAvailabilityResource extends JsonResource
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
            'day_of_week' => Lang::get($this->getDayOfWeekTranslated($this->day_of_week)),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ];
    }

    private function getDayOfWeekTranslated($dayOfWeek): string
    {
        $days = DaysWeekEnum::toArray();
        return $days[strtoupper($dayOfWeek)];
    }
}
