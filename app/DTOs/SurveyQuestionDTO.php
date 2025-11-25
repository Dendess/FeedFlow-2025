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


    public static function fromRequest(Request $request): self
    {
        return new self(
            survey_id: 1,
            title: $request->title,
            //question_type: $request->question_type,
            //options: $request->options,
            question_type: "Simple",
            options: ["Option 1", "Option 3"],
        );
    }
}
