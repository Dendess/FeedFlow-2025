<?php

namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class StoreOrganizationAction
{
    // Crée une organisation et définit le créateur comme propriétaire/admin
    public function handle(OrganizationDTO $dto): Organization
    {
        return DB::transaction(function () use ($dto) {
            // 1. Création de l'organisation
            $organization = Organization::create([
                'name'    => $dto->name,
                'user_id' => $dto->user_id, // IMPORTANT : On sauvegarde le propriétaire
            ]);

            // 2. On ajoute aussi le créateur dans la liste des membres avec le rôle 'admin'
            // Cela permet de le voir dans la liste des membres
            $organization->users()->attach($dto->user_id, ['role' => 'admin']);

            return $organization;
        });
    }
}