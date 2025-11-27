<?php

namespace App\Actions\Organization;

use App\Models\Organization;

class DeleteOrganizationAction
{
    public function handle(Organization $organization): void
    {
        // La suppression en cascade des membres et sondages est gérée 
        // par la base de données (ON DELETE CASCADE) ou par les Events Laravel.
        $organization->delete();
    }
}