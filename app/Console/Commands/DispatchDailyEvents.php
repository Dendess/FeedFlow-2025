<?php

namespace App\Console\Commands;

use App\Events\DailyAnswersThresholdReached;
use App\Models\Survey;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DispatchDailyEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-daily-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info("Checking survey activity for yesterday…");
        Log::info("Ma tâche planifiée s’est exécutée.");

        $yesterday = Carbon::yesterday();

        $surveys = Survey::withCount(['responses' => function ($query) use ($yesterday) {
            $query->whereDate('end_date', '<', $yesterday);
        }])->get();
        foreach ($surveys as $survey) {
            if ($survey->responses_count >= 0) {
                DailyAnswersThresholdReached::dispatch($survey);
                $this->info('DailyAnswersThresholdReached dispatched for survey ID ' . $survey->id);
                Log::info("ok");

            }
        }
        return Command::SUCCESS;

    }
}
