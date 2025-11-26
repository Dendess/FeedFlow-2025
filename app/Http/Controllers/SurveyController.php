<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\DTOs\SurveyDTO;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class SurveyController extends Controller
{
    public function __construct()
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

        return redirect()->route('surveys.index')->with('success', 'Sondage créé avec succès.');
    }

    // --- MODIFICATION (Update) ---
    public function update(UpdateSurveyRequest $request, Survey $survey, UpdateSurveyAction $action): RedirectResponse
    {
        // 1. Autorisation via Policy : vérifie si l'utilisateur est Admin ou Propriétaire
        $this->authorize('update', $survey);

        // 2. Préparation du DTO
        $dto = SurveyDTO::fromRequest($request);

        // 3. Exécution de l'Action de mise à jour
        $action->handle($survey, $dto);

        return redirect()->route('surveys.index')->with('success', 'Sondage mis à jour.');
    }

    // --- SUPPRESSION (Destroy) ---
    public function destroy(Survey $survey): RedirectResponse
    {
        // 1. Autorisation via Policy : vérifie si l'utilisateur est Admin ou Propriétaire
        $this->authorize('delete', $survey);

        // 2. Suppression
        $survey->delete();

        return redirect()->route('surveys.index')->with('success', 'Sondage supprimé.');
    }
}
