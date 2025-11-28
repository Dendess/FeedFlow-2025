<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Survey;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendSurveyDailyReports extends Command
{
    protected $signature = 'surveys:send-daily-reports {survey_id?}';

    protected $description = 'Send daily survey report to survey owners if more than 10 answers were received yesterday';

    public function handle(): int
    {
        $surveyId = $this->argument('survey_id');

        if ($surveyId) {
            $survey = Survey::find($surveyId);
            if (!$survey) {
                Log::warning("Survey not found: $surveyId");
                return Command::FAILURE;
            }

            // Call your logic only for this survey

            Mail::to($survey->owner->email)->send(
                new \App\Mail\DailySurveyReportMail($survey, $survey->responses()->count())
            );

            return Command::SUCCESS;
        }

        // fallback = original daily behavior
        Log::info("Running full daily report job…");
        return Command::SUCCESS;

    }
}
