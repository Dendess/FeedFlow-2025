<?php

namespace App\Actions\Organization\User;

use App\DTOs\OrganizationUserDTO;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class StoreOrganizationUserAction
{
    public function execute(Organization $organization, OrganizationUserDTO $dto): void
    {
        $user = User::where('email', $dto->email)->first();

        // Sécurité anti-doublon
        if ($organization->users()->where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Cet utilisateur appartient déjà à cette organisation.'
            ]);
        }

        $organization->users()->attach($user->id, ['role' => $dto->role]);
    }
}