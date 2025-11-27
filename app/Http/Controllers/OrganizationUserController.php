<?php

namespace App\Http\Controllers;

use App\Actions\Organization\User\DeleteOrganizationUserAction;
use App\Actions\Organization\User\StoreOrganizationUserAction;
use App\DTOs\OrganizationUserDTO;
use App\Http\Requests\Organization\User\StoreOrganizationUserRequest;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

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
        $this->authorize('update', $organization); // Seul l'admin ajoute des gens

        $dto = OrganizationUserDTO::fromRequest($request);
        $action->execute($organization, $dto);

        return back()->with('success', 'Utilisateur ajouté à l\'organisation.');
    }

    /**
     * Retire un User de l'Organisation.
     */
    public function destroy(
        Organization $organization, 
        User $user, // Le User qu'on veut virer (Route Model Binding)
        DeleteOrganizationUserAction $action
    ) {
        $this->authorize('update', $organization); // Seul l'admin supprime des gens

        // Protection : On empêche de supprimer le propriétaire
        if ($organization->user_id === $user->id) {
            return back()->with('error', 'Impossible de retirer le propriétaire.');
        }

        $action->execute($organization, $user);

        return back()->with('success', 'Utilisateur retiré de l\'organisation.');
    }
}