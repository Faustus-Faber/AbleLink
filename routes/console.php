<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// F17 - Doctor Appointment Reminders - Roza Akter
\Illuminate\Support\Facades\Schedule::command('appointments:check-reminders')->everyMinute();

// F19 - Missed Medication Check - Evan Yuvraj Munshi
\Illuminate\Support\Facades\Schedule::command('health:check-missed')->hourly();
