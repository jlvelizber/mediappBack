<?php

namespace App\Http\Requests\Doctor\Appointment;

use App\Enum\AppointmentStatusEnum;
use App\Helpers\ValidationHelpers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DoctorAppointmentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ValidationHelpers::valiateIfSameDoctorAndRole($this->user(), $this->doctor_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'sometimes|exists:patients,id',
            'doctor_id' => 'sometimes|exists:doctors,id',
            'date' => 'sometimes|date',
            'date_time' => 'sometimes|date',
            'status' => Rule::in(array_column(AppointmentStatusEnum::cases(), 'value')),
        ];
    }
}
