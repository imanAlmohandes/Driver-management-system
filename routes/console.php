<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Schedule;



Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// يتفقد رخصات القيادة للسائقين عالساعة 8 صباحا
// مش هيتغشل تلقائي لاني ع سيرفر محلي لازمه امر عشان اتفقده
// php artisan app:check-license-expiries
Schedule::command('app:check-license-expiries')->dailyAt('08:00');
