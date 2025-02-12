<?php

namespace App\Http\Requests\Appointment;

use App\Enum\AppointmentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'sometimes|date',
            'date_time' => 'sometimes|date',
            'status' => Rule::in(array_column(AppointmentStatusEnum::cases(), 'value')),
        ];
    }
}
