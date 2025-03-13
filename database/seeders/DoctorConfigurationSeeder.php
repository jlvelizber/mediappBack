<?php

namespace Database\Seeders;

use App\Models\DoctorConfiguration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DoctorConfiguration::factory()->count(10)->create();
    }
}
