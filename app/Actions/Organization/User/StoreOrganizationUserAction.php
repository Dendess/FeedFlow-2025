<?php

namespace App\Actions\Organization\User;

use App\DTOs\OrganizationUserDTO;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class StoreOrganizationUserAction
{
    public function handle(Organization $organization, OrganizationUserDTO $dto): void
    {
        // 1. On cherche l'utilisateur par son email
        $user = User::where('email', $dto->email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => "Aucun utilisateur trouvé avec cet email."
            ]);
        }

        // 2. On vérifie qu'il n'est pas déjà membre
        if ($organization->users()->where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'email' => "Cet utilisateur est déjà membre de l'organisation."
            ]);
        }

        // 3. On l'attache avec le rôle défini
        $organization->users()->attach($user->id, ['role' => $dto->role]);
    }
}