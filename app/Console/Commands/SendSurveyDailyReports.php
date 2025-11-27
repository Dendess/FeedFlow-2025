<?php

namespace App\Console\Commands;

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
        $this->info("Checking survey activity for yesterday…");
        Log::info("Ma tâche planifiée s’est exécutée.");

        $yesterday = Carbon::yesterday();

        $surveys = Survey::withCount(['responses' => function ($query) use ($yesterday) {
            $query->whereDate('created_at', $yesterday);
        }])->get();

        foreach ($surveys as $survey) {
            if ($survey->responses_count >= 0) {
                // Send email to survey owner
                Mail::to($survey->owner->email)->send(
                    new \App\Mail\DailySurveyReportMail($survey, $survey->responses_count)
                );
                Log::info("mail.");

                $this->info(
                    "Report sent to {$survey->owner->email} ({$survey->responses_count} responses)"
                );
            }
        }

        $this->info("Daily survey report job completed.");

        return Command::SUCCESS;
    }
}
