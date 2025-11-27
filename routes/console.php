<?php

use App\Events\DailyAnswersThresholdReached;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Schedule::call(function () {
    logger('TEST LOG FROM SCHEDULE');
});
Schedule::call(function () {
    DailyAnswersThresholdReached::dispatch();
})->dailyAt('09:44');
