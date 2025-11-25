<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyQuestionDTO;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\DB;


final class StoreSurveyQuestionAction
{
    public function __construct() {}

    /**
     * Store a Survey
     * @param SurveyQuestionDTO $dto
     * @return array
     */
    public function execute(SurveyQuestionDTO $dto): SurveyQuestion
    {
        // 1. Créer la question
        // 2. Éventuellement gérer data (options, etc.)


        // 3. Retourner quelque chose
        return SurveyQuestion::create([
            'survey_id' => 1,
            'title' => $dto->title,
            'question_type' => $dto->question_type,
            'options' => $dto->options,
        ]);
    }
}
