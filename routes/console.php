<?php

use App\Events\DailyAnswersThresholdReached;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;



//Schedule::command('surveys:send-daily-reports')->dailyAt('08:00');
Schedule::command('surveys:send-daily-reports')->everyMinute();
Schedule::command('surveys:check-for-survey-to-close')->everyMinute();

