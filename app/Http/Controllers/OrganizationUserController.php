<?php

namespace App\Http\Controllers;

use App\Actions\Organization\User\DeleteOrganizationUserAction;
use App\Actions\Organization\User\StoreOrganizationUserAction;
use App\DTOs\OrganizationUserDTO;
use App\Http\Requests\Organization\User\StoreOrganizationUserRequest;
use App\Models\Organization;
use App\Models\User;

class OrganizationUserController extends Controller
{
    /**
     * Ajoute un User à l'Organisation.
     */
    public function store(
        StoreOrganizationUserRequest $request,
        Organization $organization,
        StoreOrganizationUserAction $action
    ) {
        // Seul l'admin / owner peut ajouter des membres
        $this->authorize('update', $organization);

        $dto = OrganizationUserDTO::fromRequest($request);

        // Appel correct : handle()
        $action->handle($organization, $dto);

        return back()->with('success', 'Utilisateur ajouté à l\'organisation.');
    }

    /**
     * Retire un User de l'Organisation.
     */
    public function destroy(
        Organization $organization,
        User $user,
        DeleteOrganizationUserAction $action
    ) {
        // Seul l'admin / owner peut retirer des membres
        $this->authorize('update', $organization);

        // On empêche de retirer le propriétaire
        if ($organization->user_id === $user->id) {
            return back()->with('error', 'Impossible de retirer le propriétaire.');
        }

        // Appel correct : handle()
        $action->handle($organization, $user);

        return back()->with('success', 'Utilisateur retiré de l\'organisation.');
    }
}
