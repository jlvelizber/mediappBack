<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class PatientUpdateRequest extends FormRequest
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
            'name' => 'string',
            'lastname' => 'string',
            'document' => 'string|unique:patients,document,' . $this->route('patient'),
            'email' => 'email|unique:patients,email,' . $this->route('patient'),
            'phone' => 'string',
            'address' => 'string',
            'dob' => 'date',
        ];
    }
}
