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
    public function handle(DailyAnswersThresholdReached $event)
    {
        $survey = $event->survey;

        Log::info("Running artisan for survey: {$survey->id}");

        Artisan::call('surveys:send-daily-reports', [
            'survey_id' => $survey->id,
        ]);
    }
}
