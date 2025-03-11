<?php

namespace App\Http\Requests\Admin\Doctor;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class DoctorUpdateRequest extends FormRequest
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
            'user_id' => ['integer', 'exists:' . User::class . ',id'],
            'name' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],
            'email' => ['string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email,' . $this->get('user_id')],
            'password' => ['confirmed', 'min:8'],
            'specialization' => ['string', 'max:255'],
        ];
    }
}
