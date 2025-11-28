<?php

namespace App\Listeners;

use App\Events\DailyAnswersThresholdReached;
use App\Models\Survey;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        Mail::to($survey->owner->email)->send(
            new \App\Mail\DailySurveyReportMail(
                $survey,
                $survey->responses()->count()
            )
        );
    }
}
