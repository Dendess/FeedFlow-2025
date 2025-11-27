<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Survey\StoreSurveyRequest;
// use App\Actions\Survey\StoreSurveyAction; // À décommenter plus tard

class SurveyController extends Controller
{
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

    // Enregistre le sondage (Sécurisé par StoreSurveyRequest)
    public function store(StoreSurveyRequest $request): RedirectResponse
    {
        Survey::create(array_merge(
            $request->validated(), 
            ['user_id' => Auth::id()]
        ));

        return redirect()->route('surveys.index')->with('success', 'Sondage créé avec succès !');
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
}