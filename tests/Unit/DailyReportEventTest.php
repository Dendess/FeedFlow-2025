<?php

namespace Tests\Unit;

use App\Events\DailyAnswersThresholdReached;
use App\Models\Organization;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class DailyReportEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_daily_answers_threshold_reached_event_can_be_dispatched(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);

        $survey = Survey::create([
            'title' => 'Active Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'token' => Str::random(32),
        ]);

        DailyAnswersThresholdReached::dispatch($survey);

        Event::assertDispatched(DailyAnswersThresholdReached::class, function ($event) use ($survey) {
            return $event->survey->id === $survey->id;
        });
    }

    public function test_daily_answers_threshold_reached_event_contains_survey(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);

        $survey = Survey::create([
            'title' => 'Test Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'token' => Str::random(32),
        ]);

        $event = new DailyAnswersThresholdReached($survey);

        $this->assertInstanceOf(Survey::class, $event->survey);
        $this->assertEquals($survey->id, $event->survey->id);
        $this->assertEquals('Test Survey', $event->survey->title);
    }
}
