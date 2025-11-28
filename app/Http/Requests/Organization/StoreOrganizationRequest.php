<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRequest extends FormRequest
{
    // Autorise la requête (policy gérée ailleurs)
    public function authorize(): bool
    {
        return true;
    }

    // Règles de validation pour créer une organisation
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:organizations,name'],
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
