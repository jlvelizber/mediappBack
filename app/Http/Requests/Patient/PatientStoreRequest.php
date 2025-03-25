<?php

namespace App\Http\Requests\Patient;

use App\Enum\PatientGender;
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
        return [
            'name' => 'required|string',
            'lastname' => 'required|string',
            'document' => 'required|string|unique:patients,document',
            'email' => 'required|email|unique:patients,email',
            'phone' => 'required|string',
            'address' => 'string',
            'dob' => 'required|date',
            'gender' => Rule::enum(PatientGender::class)
        ];
    }
}
