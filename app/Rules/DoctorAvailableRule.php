<?php

namespace App\Rules;

use App\Enum\DaysWeekEnum;
use App\Models\DoctorAvailability;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DoctorAvailableRule implements ValidationRule
{

    protected int $doctorId;
    protected Carbon $dateTime;

    public function __construct($doctorId, $dateTime)
    {
        $this->doctorId = $doctorId;
        $this->dateTime = Carbon::parse($dateTime);
    }


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $dayOfWeek = DaysWeekEnum::getKeyByIndex($this->dateTime->dayOfWeek); // 0: Sunday, 1: Monday, 2: Tuesday, 3: Wednesday, 4: Thursday, 5: Friday, 6: Saturday
        $hour = $this->dateTime->format('H:i:s');
        // Verifica si el doctor tiene disponibilidad en el día seleccionado
        if (
            !DoctorAvailability::where('doctor_id', $this->doctorId)
                ->where('day_of_week', $dayOfWeek)
                ->where('start_time', '<=', $hour)
                ->where('end_time', '>=', $hour)
                ->exists()
        ) {
            $fail(__('app.appointments.doctor_not_available'));
        }

    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('app.appointments.doctor_not_available');
    }
}
