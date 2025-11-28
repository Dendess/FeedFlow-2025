<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyQuestionDTO;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\DB;
use App\Models\Survey;
use Illuminate\Validation\ValidationException;


final class StoreSurveyQuestionAction
{
    public function __construct() {}

    /**
     * Store a Survey
     * @param SurveyQuestionDTO $dto
     * @return SurveyQuestion
     */
    public function execute(SurveyQuestionDTO $dto): SurveyQuestion
    {
        // 1. Créer la question
        // 2. Éventuellement gérer data (options, etc.)
        // Guard: ensure survey exists to avoid foreign key DB error
        if (empty($dto->survey_id) || ! Survey::where('id', $dto->survey_id)->exists()) {
            throw ValidationException::withMessages([
                'survey_id' => ['Sondage introuvable ou identifiant invalide.'],
            ]);
        }

        // Create and return the question
        return SurveyQuestion::create([
            'survey_id' => $dto->survey_id,
            'title' => $dto->title,
            'question_type' => $dto->question_type,
            'options' => $dto->options,
        ]);
    }
}
