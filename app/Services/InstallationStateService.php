<?php

namespace App\Services;

use App\Models\DoctorConfiguration;

class InstallationStateService
{
    public function isInstalled(): bool
    {
        return DoctorConfiguration::query()
            ->whereNotNull('setup_completed_at')
            ->exists();
    }
}
