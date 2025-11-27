<?php

namespace App\Http\Controllers;

use App\Actions\Organization\DeleteOrganizationAction;
use App\Actions\Organization\StoreOrganizationAction;
use App\Actions\Organization\UpdateOrganizationAction;
use App\DTOs\OrganizationDTO;
use App\Http\Requests\Organization\StoreOrganizationRequest;
use App\Http\Requests\Organization\UpdateOrganizationRequest;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Affiche la liste des organisations de l'utilisateur.
     */
    public function index(Request $request)
    {
        // On récupère toutes les organisations liées à l'utilisateur (via la table pivot)
        $organizations = $request->user()->organizations;

        return view('organizations.index', compact('organizations'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('organizations.create');
    }

    /**
     * Enregistre une nouvelle organisation.
     * * @param StoreOrganizationRequest $request (Valide les données)
     * @param StoreOrganizationAction $action   (Contient la logique métier)
     */
    public function store(StoreOrganizationRequest $request, StoreOrganizationAction $action)
    {
        // 1. Transformation : On convertit la Request validée en DTO neutre
        $dto = OrganizationDTO::fromRequest($request);

        // 2. Exécution : On appelle l'Action en lui passant le DTO et le User connecté
        $action->execute($dto, $request->user());

        // 3. Réponse : Redirection vers le dashboard avec message
        return redirect()->route('dashboard')->with('success', 'Organisation créée avec succès !');
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(Organization $organization)
    {
        // SÉCURITÉ : Vérifie la Policy (update)
        $this->authorize('update', $organization);

        return view('organizations.edit', compact('organization'));
    }

    /**
     * Affiche les détails d'une organisation.
     */
    public function show(Organization $organization)
    {
        // Optionnel : vérifier la Policy view si nécessaire
        $this->authorize('view', $organization);

        return view('organizations.show', compact('organization'));
    }

    /**
     * Met à jour l'organisation.
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization, UpdateOrganizationAction $action)
    {
        // SÉCURITÉ
        $this->authorize('update', $organization);

        // LOGIQUE
        $dto = OrganizationDTO::fromRequest($request);
        $action->execute($organization, $dto);

        return back()->with('success', 'Organisation mise à jour.');
    }

    /**
     * Supprime l'organisation.
     */
    public function destroy(Organization $organization, DeleteOrganizationAction $action)
    {
        // SÉCURITÉ : Seul l'admin peut supprimer (voir Policy)
        $this->authorize('delete', $organization);

        $action->execute($organization);

        return redirect()->route('dashboard')->with('success', 'Organisation supprimée.');
    }

    /**
     * Méthode personnalisée pour changer d'organisation active.
     */
    public function switch(Request $request, Organization $organization)
    {
        // On vérifie manuellement que l'utilisateur est bien membre de cette orga
        if (! $request->user()->organizations->contains($organization)) {
            abort(403);
        }

        // On stocke l'ID dans la session PHP
        session(['current_organization_id' => $organization->id]);

        return back();
    }
}