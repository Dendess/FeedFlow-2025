<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyQuestionDTO;
use Illuminate\Support\Facades\DB;


final class StoreSurveyQuestionAction
{
    public function __construct() {}

    /**
     * Store a Survey
     * @param SurveyQuestionDTO $dto
     * @return array
     */
    public function execute(SurveyQuestionDTO $dto): array
    {

        return DB::transaction(function () use ($dto) {
        });
    }
}
