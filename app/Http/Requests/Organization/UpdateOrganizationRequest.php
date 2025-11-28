<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationRequest extends FormRequest
{
    // Autorise la requête (les policies font le reste)
    public function authorize(): bool
    {
        // Ici, on suppose que les policies contrôlent l'accès (controller appelle authorize)
        return true;
    }

    // Règles de validation pour mettre à jour une organisation
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Le nom de l'organisation est obligatoire.",
            'name.string' => "Le nom de l'organisation doit être une chaîne de caractères.",
            'name.max' => "Le nom de l'organisation ne peut pas dépasser 255 caractères.",
        ];
    }
}
