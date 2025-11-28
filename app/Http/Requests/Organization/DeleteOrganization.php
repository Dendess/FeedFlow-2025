<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class DeleteOrganization extends FormRequest
{
    // Autorise ou non la suppression (désactivé par défaut)
    public function authorize(): bool
    {
        return false;
    }

    // Règles de validation générées automatiquement
    public function rules(): array
    {
        return [
            'id' => ['required', 'bigint'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => "L'identifiant de l'organisation est obligatoire.",
            'id.bigint' => "L'identifiant de l'organisation doit être un entier valide.",
        ];
    }
}
