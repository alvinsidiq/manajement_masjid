<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule: expire booking holds every 5 minutes (Asia/Jakarta)
Schedule::command('booking:expire-holds')->everyFiveMinutes()->timezone('Asia/Jakarta');
