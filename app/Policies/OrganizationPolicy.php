<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    /**
     * Qui peut voir la liste des organisations ?
     * Tout utilisateur connecté peut accéder à la page "Mes organisations".
     * (Le Controller se chargera de filtrer pour n'afficher que les siennes).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Qui peut voir une organisation spécifique (Dashboard) ?
     * Règle : Il faut être membre de l'organisation.
     */
    public function view(User $user, Organization $organization): bool
    {
        // On vérifie si l'utilisateur est présent dans la relation 'users' de l'organisation
        return $organization->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Qui peut créer une organisation ?
     * Règle : Tout utilisateur authentifié.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Qui peut modifier (Update) l'organisation ?
     * Règle : Il faut être membre ET avoir le rôle 'admin'.
     */
    public function update(User $user, Organization $organization): bool
    {
        $member = $organization->users()->where('user_id', $user->id)->first();
        return $member && $member->pivot->role === 'admin';
    }

    /**
     * Qui peut supprimer l'organisation ?
     * Règle : Même règle que pour la modification (admin seulement).
     */
    public function delete(User $user, Organization $organization): bool
    {
        return $this->update($user, $organization);
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