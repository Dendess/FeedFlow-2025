<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Actions\Survey\StoreSurveyAnswerAction;
use App\Actions\Survey\StoreSurveyQuestionAction;
use App\DTOs\SurveyAnswerDTO;
use App\DTOs\SurveyQuestionDTO;
use App\Events\SurveyAnswerSubmitted;
use App\Http\Requests\Survey\StoreSurveyAnswerRequest;
use App\Http\Requests\Survey\StoreSurveyQuestionRequest;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Survey\StoreSurveyRequest;
// use App\Actions\Survey\StoreSurveyAction; // À décommenter plus tard
use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\DTOs\SurveyDTO;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Http\Controllers\Controller;

class SurveyController extends Controller
{
    public function publicShow($token)
    {
        $survey = Survey::where('token', $token)->first();

        if (!$survey) {
            abort(404, "Sondage introuvable");
        }

        $now = now();

        if ($survey->start_date && $now->lt($survey->start_date)) {
            return response("Ce sondage n'est pas encore ouvert.", 403);
        }

        if ($survey->end_date && $now->gt($survey->end_date)) {
            return response("Ce sondage est expiré.", 403);
        }

        return view('surveys.public-show', [
            'survey' => $survey,
        ]);
    }
    public function index()
    {
        $organizationId = auth()->user()->currentOrganizationId();

        $surveys = Survey::where('organization_id', $organizationId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('surveys2', compact('surveys'));
    }
    public function __constructFromG()
    {
        // Assure que seul un utilisateur authentifié peut accéder aux méthodes
        $this->middleware('auth');
    }

    // --- CRÉATION (Store) ---
    public function store(StoreSurveyRequest $request, StoreSurveyAction $action): RedirectResponse
    {
        // 1. La validation a été faite par StoreSurveyRequest

        // 2. Préparation du DTO (inclut org_id de la session)
        $dto = SurveyDTO::fromRequest($request);

        // 3. Exécution de l'Action de création (inclut la génération du token)
        $action->handle($dto);

        return redirect()->route('surveys')->with('success', 'Sondage créé avec succès.');
    }

    public function __constructFromdDev()
    {
        // Assure que seul un utilisateur authentifié peut accéder aux méthodes
        $this->middleware('auth');
    }

    public function indexFormDev(): View
    {
        $this->authorize('viewAny', Survey::class);

        // Récupération des IDs des organisations de l'utilisateur
        $userOrgIds = Auth::user()->organizations()->pluck('organizations.id');

        // Récupération des sondages liés à ces organisations
        $surveys = Survey::whereIn('organization_id', $userOrgIds)
            ->with('organization')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('surveys.index', compact('surveys'));
    }

    public function update(Request $request, Survey $surveyUpdateSurveyRequest ,Survey $survey, UpdateSurveyAction $action): RedirectResponse
    {
        $this->authorize('update', $survey);

        $dto = SurveyDTO::fromRequest($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_anonymous' => 'boolean',
        ]);

        $survey->update($validated);

        $action->handle($survey, $dto);

        return redirect()->route('surveys.index')->with('success', 'Sondage mis à jour.');
    }
    public function create(): View
    {
        $this->authorize('create', Survey::class);

        // On ne propose que les organisations où l'utilisateur est ADMIN
        $organizations = Auth::user()->organizations()
            ->wherePivot('role', 'admin')
            ->get();

        return view('surveys.create', compact('organizations'));
    }
    // Affiche un sondage spécifique
    public function show(Survey $survey): View
    {
        $this->authorize('view', $survey);

        return view('surveys.show', compact('survey'));
    }

    // Affiche le formulaire d'édition
    public function edit(Survey $survey): View
    {
        $this->authorize('update', $survey);

        return view('surveys.edit', compact('survey'));
    }
    public function storeAnswer(StoreSurveyAnswerRequest $request, StoreSurveyAnswerAction $action)
    {
        // 1. Construction du DTO à partir de la requête validée
        $answers = $request->input('answers');
        $survey_id = $answers[0]['survey_id'];
        $article = null;
        foreach ($request->validated()['answers'] as $answerData) {
            if (is_array($answerData['answer'])){
                $answerData['answer'] = json_encode($answerData['answer']);
            }
            $dto = SurveyAnswerDTO::fromArray($answerData,$request);

            // 2. Exécution de la logique métier via l’Action
            $article = $action->execute($dto);
        }

        SurveyAnswerSubmitted::dispatch($article);

        // 3. Réponse HTTP au format JSON
        return redirect()->route('surveys.index', ['survey' => $survey_id])
            ->with('success', 'Merci pour votre réponse!');
    }

    public function indexAnswer(int $survey_id)
    {
        $questions = SurveyQuestion::where('survey_id', $survey_id)->get();   // FETCH DB
        return view('layouts.AnswerQuestion', compact('questions','survey_id'));  // BLADE
    }

    public function displayAnswer($organization,$survey,$question_id)
    {
        // Commande SQL pour récupérer les réponses concernant la question et leur nombre d'occurrences
        $answers = \App\Models\SurveyAnswer::selectRaw('answer')
            ->where('survey_question_id', $question_id)
            ->get();

        $raw_answers = $answers->pluck('answer');
        $answer_counts = [];

        foreach ($raw_answers as $answer) {
            $decoded = json_decode($answer, true);

            if (is_array($decoded)) {
                foreach ($decoded as $item) {
                    if (!isset($answer_counts[$item])) {
                        $answer_counts[$item] = 0;
                    }
                    $answer_counts[$item]++;
                }
            } else {
                if (!isset($answer_counts[$answer])) {
                    $answer_counts[$answer] = 0;
                }
                $answer_counts[$answer]++;
            }
        }

        $labels = array_keys($answer_counts);
        $totals = array_values($answer_counts);

        return view('layouts.answers_display', [
            'labels' => $labels,
            'totals' => $totals,
        ]);
    }


    public function storeSurveyQuestion(StoreSurveyQuestionRequest $request, StoreSurveyQuestionAction $action)
    {
        // 1. Construction du DTO à partir de la requête validée
        $dto = SurveyQuestionDTO::fromRequest($request);
        // 2. Exécution de la logique métier via l’Action
        $article = $action->execute($dto);
        return view('layouts.questionShow' , ['questions' => $article]);
// 3. Réponse HTTP au format JSON
    }
    // Supprime un sondage
    public function destroy(Survey $survey): RedirectResponse
    {
        $this->authorize('delete', $survey);

        $survey->delete();

        return redirect()->route('surveys.index')->with('success', 'Sondage supprimé.');
    }

    // Affiche les résultats (Story 9)
    public function results(Survey $survey): View
    {
        $this->authorize('viewResults', $survey);

        return view('surveys.results', compact('survey'));
    }









}
