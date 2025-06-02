<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentChangeStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ];
    }

    /**
     * Attributes for the request.
     */
    public function attributes(): array
    {
        return [
            'status' => __('app.appointments.status_field'),
        ];
    }

}
