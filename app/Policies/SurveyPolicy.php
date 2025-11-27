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
    {
        return $user->isAdmin() || $user->id === $survey->user_id;
    }

    public function update(User $user, Survey $survey): bool
    {
        return $this->isOwnerOrAdmin($user, $survey);
    }

    public function delete(User $user, Survey $survey): bool
    {
        return $this->isOwnerOrAdmin($user, $survey);
    }

}
