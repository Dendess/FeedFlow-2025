<?php

namespace App\Console\Commands;

use App\Events\DailyAnswersThresholdReached;
use App\Events\SurveyClosed;
use App\Models\Survey;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckForSurveyToClose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'surveys:check-for-survey-to-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch event for surveys that closed yesterday';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         Log::info("check final");
        $yesterdayStart = Carbon::yesterday()->startOfDay();
        $yesterdayEnd   = Carbon::yesterday()->endOfDay();

        // Get surveys that ended yesterday
        $surveys = Survey::whereBetween('end_date', [$yesterdayStart, $yesterdayEnd])
            ->withCount('responses')
            ->get();
        foreach ($surveys as $survey) {

            Log::info("send mail final");

            // Dispatch event for each survey
            SurveyClosed::dispatch($survey);
        }
    }
}
