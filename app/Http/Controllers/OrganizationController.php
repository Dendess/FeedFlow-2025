<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Actions\Organization\StoreOrganizationAction;
use App\Actions\Organization\UpdateOrganizationAction;
use App\Actions\Organization\DeleteOrganizationAction;
use App\DTOs\OrganizationDTO;
use App\Http\Requests\Organization\StoreOrganizationRequest;
use App\Http\Requests\Organization\UpdateOrganizationRequest;

class OrganizationController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Organization::class);

        $user = Auth::user();
        
        // Récupération : Propriétaire OU Membre
        $organizations = Organization::where('user_id', $user->id)
                        ->orWhereHas('users', function ($query) use ($user) {
                            $query->where('users.id', $user->id);
                        })
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('organizations.index', compact('organizations'));
    }

    public function create(): View
    {
        $this->authorize('create', Organization::class);
        return view('organizations.create');
    }

    public function store(StoreOrganizationRequest $request, StoreOrganizationAction $action): RedirectResponse
    {
        $this->authorize('create', Organization::class);

        $dto = OrganizationDTO::fromRequest($request);
        $action->handle($dto);

        return redirect()->route('organizations.index')->with('success', 'Organisation créée !');
    }

    public function show(Organization $organization): View
    {
        $this->authorize('view', $organization);
        
        // Optimisation : charge les utilisateurs pour éviter les requêtes N+1 dans la vue
        $organization->load('users');
        
        return view('organizations.show', compact('organization'));
    }

    public function edit(Organization $organization): View
    {
        $this->authorize('update', $organization);
        return view('organizations.edit', compact('organization'));
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization, UpdateOrganizationAction $action): RedirectResponse
    {
        $this->authorize('update', $organization);

        $dto = OrganizationDTO::fromRequest($request);
        $action->handle($organization, $dto);

        return redirect()->route('organizations.index')->with('success', 'Organisation mise à jour.');
    }

    public function destroy(Organization $organization, DeleteOrganizationAction $action): RedirectResponse
    {
        $this->authorize('delete', $organization);

        $action->handle($organization);

        return redirect()->route('organizations.index')->with('success', 'Organisation supprimée.');
    }
}