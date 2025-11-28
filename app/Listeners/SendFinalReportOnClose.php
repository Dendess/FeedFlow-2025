<?php

namespace App\Listeners;

use App\Events\SurveyClosed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\FinalSurveyReportMail;

class SendFinalReportOnClose
{
    use InteractsWithQueue;

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
    public function handle(SurveyClosed $event): void
    {
        $survey = $event->survey;


        Log::info("Sending final report for survey ID: {$survey->id}");

        try {
            // Send email to the survey owner
            Mail::to($survey->owner->email)->send(
                new FinalSurveyReportMail($survey, $survey->responses()->count())
            );

            Log::info("Final report sent successfully for survey ID: {$survey->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send final report for survey ID: {$survey->id}. Error: {$e->getMessage()}");
        }
    }
}
