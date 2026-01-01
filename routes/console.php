<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// F19 - Evan Yuvraj Munshi
Schedule::command('health:check-missed')->everyMinute();

// F17 - Roza Akter
Schedule::command('appointments:check-reminders')->everyFifteenMinutes();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
