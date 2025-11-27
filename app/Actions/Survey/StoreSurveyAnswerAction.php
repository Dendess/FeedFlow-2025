<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyAnswerDTO;
use App\DTOs\SurveyDTO;
use App\Events\SurveyAnswerSubmitted;
use App\Models\SurveyAnswer;
use Illuminate\Support\Facades\DB;

final class StoreSurveyAnswerAction
{
    public function __construct() {}

    /**
     * Store a Survey
     * @param SurveyAnswerDTO $dto
     * @return SurveyAnswer
     */
    public function execute(SurveyAnswerDTO $dto): SurveyAnswer
    {
// Création de l’article via Eloquent
        $article = SurveyAnswer::create([
            'answer' => $dto->answer,
            'survey_id' => $dto->survey_id,
            'survey_question_id' => $dto->survey_question_id,
            'user_id' => $dto->user_id,
        ]);
        return $article;
    }
}
