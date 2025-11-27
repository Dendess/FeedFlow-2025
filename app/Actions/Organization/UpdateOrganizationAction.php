<?php

namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;

class UpdateOrganizationAction
{
    public function handle(Organization $organization, OrganizationDTO $dto): Organization
    {
        $organization->update([
            'name'        => $dto->name,
            'description' => $dto->description,
        ]);

        return $organization;
    }
}