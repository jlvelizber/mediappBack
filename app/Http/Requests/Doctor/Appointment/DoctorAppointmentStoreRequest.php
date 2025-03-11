<?php

namespace App\Http\Requests\Doctor\Appointment;

use App\Enum\AppointmentStatusEnum;
use App\Helpers\ValidationHelpers;
use App\Rules\{DoctorAvailableRule, DoctorHasNoConflictRule};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DoctorAppointmentStoreRequest extends FormRequest
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
            'patient_id' => 'required|exists:patients,id',
            'date_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                new DoctorAvailableRule($this->doctor_id, $this->date_time),
                new DoctorHasNoConflictRule($this->doctor_id, $this->date_time),
            ],
            'status' => Rule::in(array_column(AppointmentStatusEnum::cases(), 'value')),
            'reason' => 'string',
        ];
    }
}
