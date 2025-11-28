<?php

namespace App\Console\Commands;

use App\Events\DailyAnswersThresholdReached;
use Illuminate\Console\Command;
use App\Models\Survey;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendSurveyDailyReports extends Command
{
    protected $signature = 'surveys:send-daily-reports';

    protected $description = 'Send daily survey report to survey owners if more than 10 answers were received yesterday';

    public function handle(): int
    {
        $yesterday = Carbon::yesterday();

        $surveys = Survey::withCount(['responses' => function ($query) use ($yesterday) {
            $query->whereDate('created_at', '<', $yesterday);
        }])->get();
        foreach ($surveys as $survey) {
            if ($survey->responses_count >= 0) {
                DailyAnswersThresholdReached::dispatch($survey);
                $this->info('DailyAnswersThresholdReached dispatched for survey ID ' . $survey->id);
                Log::info("ok");
            }
        }
        Log::info("Running full daily report job…");
        return Command::SUCCESS;
    }
}
