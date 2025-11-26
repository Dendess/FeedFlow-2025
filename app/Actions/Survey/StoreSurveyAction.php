<?php

namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use App\Models\Survey;
use Illuminate\Support\Str; // Nécessaire pour générer le token

class StoreSurveyAction
{
    /**
     * Crée un nouveau sondage, génère le token et l'assigne à l'organisation/utilisateur.
     */
    public function handle(SurveyDTO $dto): Survey
    {
        return Survey::create([
            'title'           => $dto->title,
            'description'     => $dto->description,
            'start_date'      => $dto->start_date,
            'end_date'        => $dto->end_date,
            'is_anonymous'    => $dto->is_anonymous,
            'organization_id' => $dto->organization_id,
            'user_id'         => $dto->user_id,

            // Ligne clé : Génération du token unique (Critère de votre BDD)
            'token'           => Str::random(32),
        ]);
    }
}
