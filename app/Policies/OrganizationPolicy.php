<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Organization $organization): bool
    {
        // Utilisation de == au lieu de === pour gérer les ID en string/int
        if ($user->id == $organization->user_id) {
            return true;
        }

        // Vérification d'appartenance
        return $organization->users()
                    ->where('users.id', $user->id)
                    ->exists();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Organization $organization): bool
    {
        // 1. LE PROPRIÉTAIRE (Comparaison souple indispensable !)
        if ($user->id == $organization->user_id) {
            return true;
        }

        // 2. LES ADMINS (Via la table pivot)
        return $organization->users()
                    ->where('users.id', $user->id)
                    ->wherePivot('role', 'admin')
                    ->exists();
    }

    public function delete(User $user, Organization $organization): bool
    {
        // Seul le propriétaire peut supprimer l'organisation
        return $user->id == $organization->user_id;
    }

    public function restore(User $user, Organization $organization): bool
    {
        return false;
    }

    public function forceDelete(User $user, Organization $organization): bool
    {
        return false;
    }
}