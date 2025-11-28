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
        $baseType = $request->question_type;

        if ($baseType === 'option') {
            // permet de voir si le checkbox et caucher ou non
            $isMultiple = $request->has('question_type_multi_or_single');

            // opérateur ternaire pour transformer l'option
            $finalType = $isMultiple
                ? 'options_multiple'   //  checkboxes
                : 'options_single';    //  radios
        } else {
            $finalType = $baseType;
        }
        // récupération des valeurs envoyer par les inputs
        $options = $request->input('options', []);

        return new self(
            survey_id: 1,
            title: $request->title,
            question_type: $finalType,
            options: $options,
        );
    }
}
