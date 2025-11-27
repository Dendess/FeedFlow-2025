<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StoreOrganizationAction
{
    /**
     * Crée une nouvelle organisation et assigne le créateur comme administrateur.
     *
     * @param OrganizationDTO $dto  Les données de l'organisation (Nom, Desc...)
     * @param User $admin           L'utilisateur connecté qui crée l'orga
     * @return Organization         L'organisation fraîchement créée
     */
    public function execute(OrganizationDTO $dto, User $admin): Organization
    {
        // On encapsule tout dans une Transaction SQL
        return DB::transaction(function () use ($dto, $admin) {
            
            $organization = Organization::create([
                'name' => $dto->name,
                'user_id' => $admin->id, 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // On ajoute l'utilisateur dans la table pivot 'organization_user'
            // pour qu'il puisse accéder à l'organisation via $user->organizations
            $organization->users()->attach($admin->id, ['role' => 'admin']);

            return $organization;
        });
    }
}