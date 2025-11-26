<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyQuestionDTO
{
    private function __construct(
        public readonly int  $survey_id,
        public readonly string $title,
        public readonly string $question_type,
        public readonly array $options,
    )
    {}

    private static function attributeValueToCheckbox ($question_type): string {
        // condition pour que la checkbox ne fasse pas planter l'ajout
        if (empty($question_type) || $question_type === null) {
            $question_type = 'text';
        }
        return $question_type;
    }


    public static function fromRequest(Request $request): self
    {
       $rawQuestionType = $request->question_type;
       $answerQuestionType = self::attributeValueToCheckbox($rawQuestionType);

        return new self(
            survey_id: 1,
            title: $request->title,
            question_type: $answerQuestionType,
            options: $request->options,
        );
    }
}
