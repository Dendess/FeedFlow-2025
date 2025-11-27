<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSurveyRequest extends FormRequest
{
    // Détermine si l'utilisateur est autorisé à faire cette requête.
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
            'title'        => ['required', 'string', 'max:255'],
            // 'description' est requis selon la migration
            'description'  => ['required', 'string'],
            'start_date'   => ['required', 'date', 'after_or_equal:today'],
            // Règle métier : la date de fin doit être après le début
            'end_date'     => ['required', 'date', 'after:start_date'],
            'is_anonymous' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre du sondage est obligatoire.',
            'title.string' => 'Le titre du sondage doit être une chaîne de caractères.',
            'title.max' => 'Le titre du sondage ne peut pas dépasser 255 caractères.',
            'description.string' => 'La description du sondage doit être une chaîne de caractères.',
            'start_date.date' => 'La date de début doit être une date valide.',
            'end_date.date' => 'La date de fin doit être une date valide.',
            'end_date.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'is_anonymous.boolean' => 'Le champ anonymat doit être vrai ou faux.',
            'organization_id.required' => 'Vous devez sélectionner une organisation.',
        ];
    }
}
