<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Géré par $this->authorize('update', $survey) dans le controller
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'start_date'   => ['nullable', 'date'],
            'end_date'     => ['nullable', 'date', 'after:start_date'],
            'is_anonymous' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre du sondage est obligatoire.',
            'description.required' => 'La description du sondage est obligatoire.',
            'end_date.after' => 'La date de fin doit être après la date de début.',
            'start_date.date' => 'La date de début doit être une date valide.',
            'end_date.date' => 'La date de fin doit être une date valide.',
        ];
    }
}