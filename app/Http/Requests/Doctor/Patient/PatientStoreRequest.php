<?php

namespace App\Http\Requests\Doctor\Patient;

use App\Enum\PatientGender;
use App\Models\Doctor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientStoreRequest extends FormRequest
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
        $doctorId = $this->user()->doctor->id;
        return [
            'name' => 'required|string|min:3|max:255',
            'lastname' => 'required|string|min:3|max:255',
            'document' => ['required', 'string', 'max:10', Rule::unique('patients')->where('doctor_id', $doctorId)],
            'email' => ['required', 'email'],
            'phone' => 'required|string|max:10',
            'address' => 'string|max:255',
            'dob' => 'required|date',
            'gender' => Rule::enum(PatientGender::class)
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('app.patients.name'),
            'lastname' => __('app.patients.lastname'),
            'document' => __('app.patients.document'),
            'email' => __('app.patients.email'),
            'phone' => __('app.patients.phone'),
            'address' => __('app.patients.address'),
            'dob' => __('app.patients.dob'),
            'gender' => __('app.patients.gender')
        ];
    }
}
