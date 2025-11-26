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
    // static car fromRequest est également statique
    private static function convertOptionsToArray (string $options): array
    {
        // sépare les options avec des virgules
        $parts = explode(",", $options);

        // enlève les espaces
        $trimmedOptions = array_map('trim', $parts);

        // enlève les chaînes vides
        $filterdOptions = array_filter($trimmedOptions , function ($value){
            // teste pour enlever les élément vide qui n'ont plus d'espace
            return $value !== '';
        });

        // réindex les clées proprement
        return array_values($filterdOptions);
    }

    private static function attributeValueToCheckbox ($question_type): string {
        // condition pour que la checkbox ne fasse pas planter l'ajout
        if (empty($question_type) || $question_type === null) {
            $question_type = 'text';
        }
        return $question_type;
    }


    public static function fromRequest(Request $request): self
    {
        // prepartion du tableau des options
        $rawOptions = $request->options;
        $optionsArray = self::convertOptionsToArray($rawOptions); // => tableau final des options

        $rawQuestionType = $request->question_type;
       $answerQuestionType = self::attributeValueToCheckbox($rawQuestionType);

        return new self(
            survey_id: 1,
            title: $request->title,
            question_type: $answerQuestionType,
            options: $optionsArray,
        );
    }
}
