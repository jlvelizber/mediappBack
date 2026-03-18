<?php

namespace App\Http\Requests\Setup;

use App\Enum\WayNotificationEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SetupInitializeRequest extends FormRequest
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
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_lastname' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'admin_phone' => ['nullable', 'string', 'max:25'],
            'admin_password' => ['required', 'confirmed', Password::defaults()],
            'doctor_specialization' => ['nullable', 'string', 'max:255'],
            'medical_center_name' => ['required', 'string', 'max:255'],
            'medical_center_address' => ['nullable', 'string', 'max:255'],
            'medical_center_phone' => ['nullable', 'string', 'max:25'],
            'medical_center_email' => ['nullable', 'string', 'email', 'max:255'],
            'default_appointment_duration' => ['nullable', 'integer', 'min:5', 'max:240'],
            'default_appointment_price' => ['nullable', 'numeric', 'min:0'],
            'default_appointment_currency' => ['nullable', 'string', 'max:10'],
            'default_appointment_currency_symbol' => ['nullable', 'string', 'max:10'],
            'notification_way' => ['nullable', Rule::in(array_column(WayNotificationEnum::cases(), 'value'))],
            'reminder_hour_appointment' => ['nullable', 'integer', 'min:1', 'max:168'],
        ];
    }
}
