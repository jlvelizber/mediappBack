<?php

namespace App\Http\Requests\Doctor\Appointment;

use App\Enum\AppointmentStatusEnum;
use App\Helpers\ValidationHelpers;
use App\Models\Appointment;
use App\Repositories\Interface\DoctorConfigurationRepositoryInterface;
use App\Rules\{DoctorAvailableRule, DoctorHasNoConflictRule};
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class DoctorAppointmentStoreRequest extends FormRequest
{
    /**
     * Get the validation data that should be used to validate the request.
     *
     * @return array<string, mixed>
     */
    protected DoctorConfigurationRepositoryInterface $doctorConfigurationRepositoryInterface;
    public function __construct(DoctorConfigurationRepositoryInterface $doctorConfigurationRepositoryInterface)
    {
        $this->doctorConfigurationRepositoryInterface = $doctorConfigurationRepositoryInterface;
    }

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
                'date_format:Y-m-d H:i',
                new DoctorAvailableRule($this->doctor_id, $this->date_time),
                new DoctorHasNoConflictRule($this->doctor_id, $this->date_time),
            ],
            'status' => Rule::in(array_column(AppointmentStatusEnum::cases(), 'value')),
            'reason' => 'required|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $dateTime = $this->input('date_time');
            $doctorId = $this->input('doctor_id');
            $durationMinutes = $this->doctorConfigurationRepositoryInterface->getByDoctorIdAndKeyValue($doctorId, 'default_appointment_duration')->default_appointment_duration ?? config('mediapp.doctor_configuration.default_appointment_duration');
            (int) $durationMinutes--;
            $endTime = Carbon::parse($dateTime)->addMinutes($durationMinutes);

            $overlappingAppointment = Appointment::where('doctor_id', $doctorId)
                ->where(function ($query) use ($dateTime, $endTime, $durationMinutes) {
                    $query->whereBetween('date_time', [$dateTime, $endTime])
                        ->orWhereRaw('? BETWEEN date_time AND DATE_ADD(date_time, INTERVAL ? MINUTE)', [$dateTime, $durationMinutes]);
                })
                ->exists();

            if ($overlappingAppointment) {
                $validator->errors()->add('date_time', __('app.appointments.doctor_is_bussy'));
            }
        });
    }


    public function attributes(): array
    {
        return [
            'patient_id' => __('app.appointments.patient_id'),
            'date_time' => __('app.appointments.date_time'),
            'status' => __('app.appointments.status_field'),
            'doctor_id' => __('app.appointments.doctor_id'),
            'reason' => __('app.appointments.reason'),
        ];
    }
}
