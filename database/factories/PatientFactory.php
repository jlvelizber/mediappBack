<?php

namespace Database\Factories;

use App\Enum\PatientGender;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
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
            'document' => $this->faker->unique()->randomNumber(8),
            'name' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'dob' => $this->faker->date(),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'gender' => $this->faker->randomElement(array_column(PatientGender::cases(), 'value'))
        ];
    }
}
