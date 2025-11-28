<?php

namespace Tests\Unit;

use App\Actions\Survey\StoreSurveyAction;
use App\DTOs\SurveyDTO;
use App\Models\Organization;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreSurveyActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_survey_with_a_generated_token(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Acme Inc.',
            'user_id' => $user->id,
        ]);

        $dto = new SurveyDTO(
            title: 'Satisfaction 2025',
            description: 'Collecter les retours clients.',
            start_date: now()->toDateTimeString(),
            end_date: now()->addWeek()->toDateTimeString(),
            is_anonymous: false,
            organization_id: $organization->id,
            user_id: $user->id,
        );

        $survey = (new StoreSurveyAction())->handle($dto);

        $this->assertInstanceOf(Survey::class, $survey);
        $this->assertSame('Satisfaction 2025', $survey->title);
        $this->assertSame($organization->id, $survey->organization_id);
        $this->assertSame($user->id, $survey->user_id);
        $this->assertSame(32, strlen($survey->token), 'Le token doit faire 32 caractères.');

        $this->assertDatabaseHas('surveys', [
            'id' => $survey->id,
            'title' => 'Satisfaction 2025',
            'organization_id' => $organization->id,
            'user_id' => $user->id,
        ]);
    }
}
