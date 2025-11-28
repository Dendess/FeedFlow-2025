<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyQuestionRequest extends FormRequest
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
        $rules = [
            'survey_id' => ['required', 'integer', 'exists:surveys,id'],
            'title' => ['required', 'string' , 'max:255'],
            'question_type' => ['required', 'string' , 'in:text,scale,option'],
            'options' => ['nullable', 'array'],
        ];

        // Only validate options content if question type is 'option'
        if ($this->input('question_type') === 'option') {
            $rules['options.*'] = ['required', 'string', 'distinct', 'max:255'];
        }

        return $rules;
    }

    /*
     * Message d'erreur personnalisé (optionnel)
     */

    public function messages(): array
    {
        return [
            'survey_id.required' => 'Identifiant du sondage manquant.',
            'survey_id.exists' => 'Sondage introuvable.',
            'title.required' => 'Veuillez écrire un titre avant de valider la question.',
            'question_type.required' => 'Le type de question est obligatoire.',
            'options.*.required_if' => 'Veuillez fournir au moins une option pour les questions à choix.',
        ];
    }

}
