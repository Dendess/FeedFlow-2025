<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'answers' => ['required', 'array'],
            'answers.*.question_id' => ['required', 'integer'],
            'answers.*.survey_id' => ['required', 'integer'],
            'answers.*.answer' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'answer.*.answer.required' => 'Une réponse obligatoire est manquante.',
            'answer.*.answer.max' => 'La réponse est trop longue.',
        ];
    }

}
