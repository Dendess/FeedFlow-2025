<?php

namespace App\Actions\Organization\User;

use App\Models\Organization;
use App\Models\User;

class DeleteOrganizationUserAction
{
    public function execute(Organization $organization, User $user): void
    {
        // On retire l'utilisateur de la table pivot
        $organization->users()->detach($user->id);
    }
}