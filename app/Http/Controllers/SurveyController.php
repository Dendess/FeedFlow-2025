<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\DTOs\SurveyDTO;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SurveyController extends Controller
{
    /**
     * Liste des sondages (Filtrée par les orgas de l'user).
     */
    public function index(): View
    {
        $this->authorize('viewAny', Survey::class);

        $userOrgIds = Auth::user()->organizations()->pluck('organizations.id');

        $surveys = Survey::whereIn('organization_id', $userOrgIds)
            ->with('organization') // Optimisation
            ->orderBy('created_at', 'desc')
            ->get();

        return view('surveys.index', compact('surveys'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $this->authorize('create', Survey::class);

        // Seuls les admins d'une orga peuvent créer un sondage pour elle
        $organizations = Auth::user()->organizations()
            ->wherePivot('role', 'admin')
            ->get();

        return view('surveys.create', compact('organizations'));
    }

    /**
     * Stockage (Uses DTO + Action).
     */
    public function store(StoreSurveyRequest $request, StoreSurveyAction $action): RedirectResponse
    {
        // 1. Validation & Auth faites dans StoreSurveyRequest

        // 2. Création du DTO
        $dto = SurveyDTO::fromRequest($request);

        // 3. Exécution de l'Action
        $action->handle($dto);

        return redirect()->route('surveys.index')->with('success', 'Sondage créé avec succès.');
    }

    /**
     * Voir un sondage.
     */
    public function show(Survey $survey): View
    {
        $this->authorize('view', $survey);
        return view('surveys.show', compact('survey'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Survey $survey): View
    {
        $this->authorize('update', $survey);
        return view('surveys.edit', compact('survey'));
    }

    /**
     * Mise à jour (Uses DTO + Action).
     */
    public function update(UpdateSurveyRequest $request, Survey $survey, UpdateSurveyAction $action): RedirectResponse
    {
        $this->authorize('update', $survey);

        // 1. DTO
        // Note: Assure-toi que ton SurveyDTO a une méthode fromRequest ou similaire adaptée à l'update
        // Sinon, utilise simplement $request->validated() si l'action accepte un tableau.
        $dto = SurveyDTO::fromRequest($request);

        // 2. Action
        $action->handle($survey, $dto);

        return redirect()->route('surveys.index')->with('success', 'Sondage mis à jour.');
    }

    /**
     * Suppression.
     */
    public function destroy(Survey $survey): RedirectResponse
    {
        $this->authorize('delete', $survey);
        $survey->delete();
        return redirect()->route('surveys.index')->with('success', 'Sondage supprimé.');
    }

    /**
     * Page publique (répondre au sondage via token).
     */
    public function publicShow($token)
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

    /**
     * Voir les résultats (Story 9).
     */
    public function results(Survey $survey): View
    {
        $this->authorize('viewResults', $survey);
        return view('surveys.results', compact('survey'));
    }
}