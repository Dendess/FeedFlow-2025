<?php

namespace App\Listeners;

use App\Events\DailyAnswersThresholdReached;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SendDailyReport
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DailyAnswersThresholdReached $event): void
    {
        Log::info('ggg');

        Artisan::call('surveys:send-daily-reports');
    }

}
