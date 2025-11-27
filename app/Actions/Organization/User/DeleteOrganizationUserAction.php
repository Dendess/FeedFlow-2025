<?php

namespace App\Actions\Organization\User;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class DeleteOrganizationUserAction
{
    public function handle(Organization $organization, User $user): void
    {
        // Sécurité : On ne peut pas retirer le propriétaire de l'organisation
        if ($organization->user_id === $user->id) {
            throw ValidationException::withMessages([
                'user' => "Impossible de retirer le propriétaire de l'organisation."
            ]);
        }

        $organization->users()->detach($user->id);
    }
}