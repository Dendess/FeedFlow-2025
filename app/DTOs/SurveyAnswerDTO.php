<?php

namespace App\DTOs;

use App\Http\Requests\Survey\StoreSurveyAnswerRequest;
use Illuminate\Http\Request;

final class SurveyAnswerDTO
{
    private function __construct(
        public readonly string $answer,
        public readonly int $survey_id,
        public readonly int $survey_question_id,
        public readonly int $user_id,
    ) {}

    public static function fromArray(array $array,Request $request): self
    {
        return new self(
            answer: $array['answer'],
            survey_id: $array['survey_id'],
            survey_question_id: $array['question_id'],
            user_id: $request->user()->id,
        );
    }
}
