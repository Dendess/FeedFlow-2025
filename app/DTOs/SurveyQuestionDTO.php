<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyQuestionDTO
{
    private function __construct(
        public readonly int    $survey_id,
        public readonly string $title,
        public readonly string $question_type,
        public readonly array  $options,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $questionType = $request->question_type;
        $options = $request->input('options', []);

        // Filter out empty options
        if (is_array($options)) {
            $options = array_filter($options, function($value) {
                return !empty($value) && trim($value) !== '';
            });
            $options = array_values($options); // Reindex array
        }

        // For text type, ensure empty array
        if ($questionType === 'text') {
            $options = [];
        }

        return new self(
            survey_id: (int) $request->input('survey', $request->input('survey_id', 0)),
            title: $request->title,
            question_type: $questionType,
            options: $options,
        );
    }
}
