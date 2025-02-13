<?php

namespace Database\Seeders;

use App\Enum\UserRoleEnum;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'lastname' => 'Test Lastname',
            'email' => 'jorgeconsalvacion@gmail.com',
            'role' => UserRoleEnum::ADMIN->value,
        ]);

        $this->call([
            PatientSeeder::class,
            DoctorSeeder::class,
            DoctorAvailabilitySeeder::class,
            AppointmentSeeder::class,
        ]);
    }
}
