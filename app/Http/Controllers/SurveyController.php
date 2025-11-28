<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\StoreSurveyAnswerAction;
use App\Actions\Survey\StoreSurveyQuestionAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\DTOs\SurveyAnswerDTO;
use App\DTOs\SurveyDTO;
use App\DTOs\SurveyQuestionDTO;
use App\Events\SurveyAnswerSubmitted;
use App\Http\Requests\Survey\StoreSurveyAnswerRequest;
use App\Http\Requests\Survey\StoreSurveyQuestionRequest;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SurveyController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Survey::class);

        $userOrgIds = Auth::user()->organizations()->pluck('organizations.id');

        $surveys = Survey::whereIn('organization_id', $userOrgIds)
            ->with('organization')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('surveys.index', compact('surveys'));
    }

    public function create(): View
    {
        $this->authorize('create', Survey::class);

        $organizations = Auth::user()->organizations()
            ->wherePivot('role', 'admin')
            ->get();

        return view('surveys.create', compact('organizations'));
    }

    public function store(StoreSurveyRequest $request, StoreSurveyAction $action): RedirectResponse
    {
        $dto = SurveyDTO::fromRequest($request);

        $action->handle($dto);

        return redirect()->route('surveys.index')->with('success', 'Sondage créé avec succès.');
    }

    public function show(Survey $survey): View
    {
        $this->authorize('view', $survey);

        return view('surveys.show', compact('survey'));
    }

    public function edit(Survey $survey): View
    {
        $this->authorize('update', $survey);

        return view('surveys.edit', compact('survey'));
    }

    public function update(UpdateSurveyRequest $request, Survey $survey, UpdateSurveyAction $action): RedirectResponse
    {
        $this->authorize('update', $survey);

        $dto = SurveyDTO::fromRequest($request);
        $action->handle($survey, $dto);

        return redirect()->route('surveys.index')->with('success', 'Sondage mis à jour.');
    }

    public function destroy(Survey $survey): RedirectResponse
    {
        $this->authorize('delete', $survey);
        $survey->delete();

        return redirect()->route('surveys.index')->with('success', 'Sondage supprimé.');
    }

    public function publicShow(string $token)
    {
        $survey = Survey::where('token', $token)->firstOrFail();

        $now = now();
        if ($survey->start_date && $now->lt($survey->start_date)) {
            abort(403, "Ce sondage n'est pas encore ouvert.");
        }
        if ($survey->end_date && $now->gt($survey->end_date)) {
            abort(403, "Ce sondage est expiré.");
        }

        return view('surveys.public-show', compact('survey'));
    }

    public function results(Survey $survey): View
    {
        $this->authorize('viewResults', $survey);

        // Ensure questions are loaded for the results view
        $survey->load('questions');

        return view('surveys.results', compact('survey'));
    }

    public function storeAnswer(StoreSurveyAnswerRequest $request, StoreSurveyAnswerAction $action): RedirectResponse
    {
        $article = null;

        $validated = $request->validated();

        $first = $validated['answers'][0] ?? null;
        if (!$first || empty($first['survey_id'])) {
            abort(400, 'Survey id missing');
        }

        $survey = Survey::findOrFail($first['survey_id']);
        $this->authorize('view', $survey);

        foreach ($validated['answers'] as $answerData) {
            if (is_array($answerData['answer'])) {
                $answerData['answer'] = json_encode($answerData['answer']);
            }

            $dto = SurveyAnswerDTO::fromArray($answerData, $request);
            $article = $action->execute($dto);
        }

        if ($article) {
            SurveyAnswerSubmitted::dispatch($article);
        }

        return redirect()->route('surveys.show', $survey)->with('success', 'Merci pour votre réponse!');
    }

    public function indexAnswer(Survey $survey): View
    {
        $this->authorize('view', $survey);

        $questions = SurveyQuestion::where('survey_id', $survey->id)->get();

        return view('layouts.AnswerQuestion', [
            'questions' => $questions,
            'survey_id' => $survey->id,
            'survey' => $survey,
        ]);
    }

    public function displayAnswer(int $organization, int $survey, int $question)
    {
        $answers = \App\Models\SurveyAnswer::selectRaw('answer')
            ->where('survey_question_id', $question)
            ->get();

        $rawAnswers = $answers->pluck('answer');
        $answerCounts = [];

        foreach ($rawAnswers as $answer) {
            $decoded = json_decode($answer, true);

            if (is_array($decoded)) {
                foreach ($decoded as $item) {
                    $answerCounts[$item] = ($answerCounts[$item] ?? 0) + 1;
                }
            } else {
                $answerCounts[$answer] = ($answerCounts[$answer] ?? 0) + 1;
            }
        }

        $labels = array_keys($answerCounts);
        $totals = array_values($answerCounts);

        return view('layouts.answers_display', compact('labels', 'totals'));
    }

    public function storeSurveyQuestion(StoreSurveyQuestionRequest $request, StoreSurveyQuestionAction $action)
    {
        $dto = SurveyQuestionDTO::fromRequest($request);
        $question = $action->execute($dto);

        return redirect()
            ->route('surveys.questions.create', ['survey' => $request->survey_id])
            ->with('success', "Question '{$question->title}' ajoutée avec succès!");
    }
}
