<?php

namespace App\Http\Requests\Doctor\DoctorAvailability;

use App\Enum\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;

class DoctorAvailabilityStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role == UserRoleEnum::DOCTOR->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
        ];
    }
}
