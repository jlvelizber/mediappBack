<?php

namespace App\Http\Requests\Doctor\MedicalRecord;

use App\Enum\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMedicalRecordRequest extends FormRequest
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
            'appointment_id' => ['required', 'exists:appointments,id'],
            'symptoms' => ['required', 'string'],
            'diagnosis' => ['required', 'string'],
            'treatment' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'prescription.items' => ['array'],
            'prescription.items.*.medication_name' => ['required', 'string'],
            'prescription.items.*.dosage' => ['required', 'string'],
            'prescription.items.*.frequency' => ['required', 'string'],
            'prescription.items.*.duration' => ['required', 'string'],
            'prescription.items.*.notes' => ['nullable', 'string'],
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'appointment_id' => __('app.medical_records.appointment_id'),
            'symptoms' => __('app.medical_records.symptoms'),
            'diagnosis' => __('app.medical_records.diagnosis'),
            'treatment' => __('app.medical_records.treatment'),
            'notes' => __('app.medical_records.notes'),
            'prescription.items.*.medication_name' => __('app.medical_records.medication_name'),
            'prescription.items.*.dosage' => __('app.medical_records.dosage'),
            'prescription.items.*.frequency' => __('app.medical_records.frequency'),
            'prescription.items.*.duration' => __('app.medical_records.duration'),
            'prescription.items.*.notes' => __('app.medical_records.notes'),
        ];
    }


    protected function failedValidation(Validator $validator): void
    {
        $formattedErrors = [];
        foreach ($validator->errors()->toArray() as $key => $messages) {
            data_set($formattedErrors, $key, $messages);
        }

        throw new HttpResponseException(
            response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $formattedErrors,
            ], 422)
        );
    }
}
