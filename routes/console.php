<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('test:job')->everyThirtyMinutes();

Schedule::command('task:creator --catchup')->everyMinute();   


// Schedule::command('files:backup-public --upload')->dailyAt("21:00");  
Schedule::command('database:backup --upload')->dailyAt("20:00");  