<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoctorConfiguration>
 */
class DoctorConfigurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id' => Factory::factoryForModel('Doctor'),
            'default_appointment_duration' => 20,
            'default_appointment_price' => 0,
            'default_appointment_currency' => 'USD',
            'default_appointment_currency_symbol' => '$',
        ];
    }
}
