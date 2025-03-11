<?php

namespace App\Http\Requests\Appointment;

use App\Enum\AppointmentStatusEnum;
use App\Rules\DoctorAvailableRule;
use App\Rules\DoctorHasNoConflict;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentStoreRequest extends FormRequest
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
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'date_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                new DoctorAvailableRule($this->doctor_id, $this->date_time),
                new DoctorHasNoConflict($this->doctor_id, $this->date_time),
            ],
            'status' => Rule::in(array_column(AppointmentStatusEnum::cases(), 'value')),
            'reason' => 'string',
        ];
    }
}
