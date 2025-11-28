<?php

namespace Tests\Unit;

use App\Actions\Survey\StoreSurveyAnswerAction;
use App\DTOs\SurveyAnswerDTO;
use App\Events\SurveyAnswerSubmitted;
use App\Models\Organization;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreSurveyAnswerActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_persists_an_answer_and_dispatches_the_event(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Beta Org',
            'user_id' => $user->id,
        ]);

        $survey = Survey::create([
            'title' => 'Feedback',
            'description' => 'Collecte hebdomadaire',
            'start_date' => now()->toDateTimeString(),
            'end_date' => now()->addDay()->toDateTimeString(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'token' => Str::random(32),
        ]);

        $question = SurveyQuestion::create([
            'survey_id' => $survey->id,
            'title' => 'Êtes-vous satisfait ?',
            'question_type' => 'text',
            'options' => ['Oui', 'Non'],
        ]);

        $request = Request::create('/surveys/'.$survey->id.'/answers', 'POST');
        $request->setUserResolver(fn () => $user);

        $dto = SurveyAnswerDTO::fromArray([
            'answer' => 'Oui',
            'survey_id' => $survey->id,
            'question_id' => $question->id,
        ], $request);

        $answer = (new StoreSurveyAnswerAction())->execute($dto);

        $this->assertDatabaseHas('survey_answers', [
            'id' => $answer->id,
            'answer' => 'Oui',
            'survey_id' => $survey->id,
            'survey_question_id' => $question->id,
            'user_id' => $user->id,
        ]);

        Event::assertDispatched(SurveyAnswerSubmitted::class, function ($event) use ($answer) {
            return $event->answer->is($answer);
        });
    }
}
