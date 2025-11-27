<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Actions\Survey\StoreSurveyAnswerAction;
use App\Actions\Survey\StoreSurveyQuestionAction;
use App\DTOs\SurveyAnswerDTO;
use App\DTOs\SurveyQuestionDTO;
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

class SurveyController extends Controller
{
    public function publicShow($token)
    {
        $survey = Survey::where('token', $token)->first();

        if (! $survey) {
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

    // Affiche la liste des sondages (Filtrée par organisations de l'utilisateur)
    public function index(): View
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

    // Affiche le formulaire de création
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

    // Met à jour un sondage
    public function update(Request $request, Survey $survey): RedirectResponse
    {
        $this->authorize('update', $survey);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_anonymous' => 'boolean',
        ]);

        $survey->update($validated);

        return redirect()->route('surveys.index')->with('success', 'Sondage mis à jour.');
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

    public function storeAnswer(StoreSurveyAnswerRequest $request, StoreSurveyAnswerAction $action)
    {
// 1. Construction du DTO à partir de la requête validée
        //dd($request->all());
        $answers = $request->input('answers');
        $survey_id = $answers[0]['survey_id'];
        foreach ($request->validated()['answers'] as $answerData) {
            $dto = SurveyAnswerDTO::fromArray($answerData,$request);

            // 2. Exécution de la logique métier via l’Action
            $action->execute($dto);
        }
// 3. Réponse HTTP au format JSON
        return redirect()->route('surveys.index', ['survey' => $survey_id])
            ->with('success', 'Merci pour votre réponse!');
    }

    public function index2(int $survey_id)
    {
        $questions = SurveyQuestion::where('survey_id', $survey_id)->get();   // FETCH DB
        return view('layouts.AnswerQuestion', compact('questions','survey_id'));  // BLADE
    }
    public function store(StoreSurveyQuestionRequest $request, StoreSurveyQuestionAction $action)
    {
        // 1. Construction du DTO à partir de la requête validée
        $dto = SurveyQuestionDTO::fromRequest($request);
        // 2. Exécution de la logique métier via l’Action
        $article = $action->execute($dto);
        return view('layouts.questionShow' , ['questions' => $article]);
// 3. Réponse HTTP au format JSON
    }
}
