<?php

namespace App\Policies;

use App\Models\Survey;
use App\Models\User;

class SurveyPolicy
{
    /**
     * Helper : Vérifie si l'utilisateur appartient à l'organisation du sondage.
     */
    protected function isMemberOfOrganization(User $user, Survey $survey): bool
    {
        return $user->organizations()
            ->where('organizations.id', $survey->organization_id)
            ->exists();
    }

    /**
     * Helper : Vérifie si l'utilisateur est ADMIN de l'organisation du sondage.
     */
    protected function isAdminOfOrganization(User $user, Survey $survey): bool
    {
        return $user->organizations()
            ->where('organizations.id', $survey->organization_id)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    // Voir la liste (Filtrage fait dans le Controller)
    public function viewAny(User $user): bool
    {
        return true;
    }

    // Voir un sondage (Membres + Admins)
    public function view(User $user, Survey $survey): bool
    {
        return $this->isMemberOfOrganization($user, $survey);
    }

    // Créer (Autorisé globalement, vérif orga dans le FormRequest)
    public function create(User $user): bool
    {
        return true;
    }

    // Modifier (Admins uniquement)
    public function update(User $user, Survey $survey): bool
    {
        return $this->isAdminOfOrganization($user, $survey);
    }

    // Supprimer (Admins uniquement)
    public function delete(User $user, Survey $survey): bool
    {
        return $this->isAdminOfOrganization($user, $survey);
    }

    // Voir résultats (Membres + Admins)
    public function viewResults(User $user, Survey $survey): bool
    {
        return $this->isMemberOfOrganization($user, $survey);
    }
}