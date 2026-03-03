<?php

namespace App\Http\Requests\Doctor\Settings;

use App\Enum\WayNotificationEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DoctorSettingsUpdateRequest extends FormRequest
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
            'default_appointment_duration' => 'sometimes|integer|min:5|max:240',
            'default_appointment_price' => 'sometimes|numeric|min:0',
            'currency_default_appointment' => 'sometimes|string|max:10',
            'currency_symbol_default_appointment' => 'sometimes|string|max:10',
            'way_notify_appointment' => ['sometimes', Rule::in(array_column(WayNotificationEnum::cases(), 'value'))],
            'reminder_hour_appointment' => 'sometimes|integer|min:1|max:168',
            'medical_center_name' => 'sometimes|nullable|string|max:255',
            'medical_center_address' => 'sometimes|nullable|string|max:255',
            'medical_center_phone' => 'sometimes|nullable|string|max:30',
        ];
    }
}
