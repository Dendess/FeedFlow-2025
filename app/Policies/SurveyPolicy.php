<?php

namespace App\Policies;

use App\Models\Survey;
use App\Models\User;

class SurveyPolicy
{
    /**
     * Détermine si l'utilisateur peut modifier ou supprimer le sondage.
     */
    public function isOwnerOrAdmin(User $user, Survey $survey): bool
    // Vérifie si l'utilisateur est membre (Lecteur ou Admin).
    protected function isMemberOfOrganization(User $user, Survey $survey): bool
    {
        return $user->organizations()
                    ->where('organizations.id', $survey->organization_id)
                    ->exists();
    }

    // Vérifie si l'utilisateur est ADMIN spécifiquement.
    protected function isAdminOfOrganization(User $user, Survey $survey): bool
    {
        return $user->organizations()
                    ->where('organizations.id', $survey->organization_id)
                    ->wherePivot('role', 'admin')
                    ->exists();
    }

    // Accès à la page "Liste des sondages".
    // Autorisé pour tout le monde (le filtrage des données se fait dans le Controller).
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->id === $survey->user_id;
        return true;
    }

    //
     // Voir un sondage spécifique (Dashboard, détails).
     //Autorisé pour les MEMBRES et ADMINS.
    public function view(User $user, Survey $survey): bool
    {
        return $this->isMemberOfOrganization($user, $survey);
    }

    // Accès au formulaire de création.
    // Autorisé globalement (la vérification de l'orga choisie se fera dans le StoreRequest).
    public function create(User $user): bool
    {
        return true;
    }

    // Modifier un sondage.
    // Autorisé UNIQUEMENT pour les ADMINS.
    public function update(User $user, Survey $survey): bool
    {
        return $this->isOwnerOrAdmin($user, $survey);
        return $this->isAdminOfOrganization($user, $survey);
    }

    // Supprimer un sondage.
    // Autorisé UNIQUEMENT pour les ADMINS.
    public function delete(User $user, Survey $survey): bool
    {
        return $this->isOwnerOrAdmin($user, $survey);
        return $this->isAdminOfOrganization($user, $survey);
    }

    // Voir les résultats (Story 9).
    // Autorisé pour les MEMBRES et ADMINS.
    public function viewResults(User $user, Survey $survey): bool
    {
        return $this->isMemberOfOrganization($user, $survey);
    }
}
