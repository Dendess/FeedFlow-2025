<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSurveyRequest extends FormRequest
{
    // Détermine si l'utilisateur est autorisé à faire cette requête.
    public function authorize(): bool
    {
        $organizationId = $this->input('organization_id');

        // Sécurité : On vérifie que l'utilisateur est bien ADMIN de l'organisation cible
        return Auth::user()->organizations()
            ->where('organizations.id', $organizationId)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    // Règles de validation.
    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'start_date'      => ['nullable', 'date'],
            'end_date'        => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_anonymous'    => ['boolean'],
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