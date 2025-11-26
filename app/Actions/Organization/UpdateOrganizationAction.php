<?php

namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;

final class UpdateOrganizationAction
{
    /**
     * Met à jour les informations d'une organisation.
     * * @param Organization $organization L'entité à modifier (reçue du Controller)
     * @param OrganizationDTO $dto       Les nouvelles données validées
     */
    public function execute(Organization $organization, OrganizationDTO $dto): Organization
    {
        // Pas besoin de DB::transaction ici, c'est une opération atomique simple
        $organization->update([
            'name' => $dto->name,
        ]);

        return $organization;
    }
}