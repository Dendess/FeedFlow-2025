<?php

namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use App\Models\Survey;

class UpdateSurveyAction
{
    /**
     * Met à jour les champs modifiables du sondage existant avec les données du DTO.
     */
    public function handle(Survey $survey, SurveyDTO $dto): Survey
    {
        // On utilise les données du DTO pour mettre à jour le modèle
        $survey->update([
            'title'        => $dto->title,
            'description'  => $dto->description,
            'start_date'   => $dto->start_date,
            'end_date'     => $dto->end_date,
            'is_anonymous' => $dto->is_anonymous,
            // Note : on ne modifie pas organization_id, user_id ou token ici
        ]);

        return $survey;
    }
}
