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
        return [
            'title' => ['required', 'string' , 'max:255'],
            'question_type' => ['required', 'string' , 'max:255'],
            'options' => ['required', 'json'],
        ];
    }

    /*
     * Message d'erreur personnalisé (optionnel)
     */

    public function messages(): array
    {
        return [
            'title.required' => 'Veuillez écrire un titre avant de valider la question.',
            'question_type.required' => 'Le contenu est obligatoire.',
        ];
    }

}
