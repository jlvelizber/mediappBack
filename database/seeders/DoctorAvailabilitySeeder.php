<?php

namespace Database\Seeders;

use App\Models\DoctorAvailability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DoctorAvailability::factory()->count(10)->create();
    }
}
