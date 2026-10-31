<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('instagram:refresh-tokens')->dailyAt('02:00');

// Pembersihan otomatis berkas gambar konversi dan temp yang berusia lebih dari 14 hari (2 minggu)
Schedule::command('tools:clean-temp-images --days=14')->dailyAt('03:30');


