<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
// Cancela las citas previas no confirmadas a las 3:00 AM cada día
Schedule::command('app:cancel-previus-appointment-not-confirmed')->dailyAt('03:00');
