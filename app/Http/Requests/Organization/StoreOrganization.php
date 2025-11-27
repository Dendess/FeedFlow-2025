<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganization extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'bigint'],
            'name' => ['required', 'varchar'],
            'user_id' => ['required', 'bigint'],
            'created_at' => ['required', 'timestamp'],
            'updated_at' => ['required', 'timestamp'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => "L'identifiant de l'organisation est obligatoire.",
            'id.bigint' => "L'identifiant de l'organisation doit être un entier valide.",
            'name.required' => "Le nom de l'organisation est obligatoire.",
            'name.varchar' => "Le nom de l'organisation doit être une chaîne de caractères valide.",
            'user_id.required' => "L'identifiant de l'utilisateur est obligatoire.",
            'user_id.bigint' => "L'identifiant de l'utilisateur doit être un entier valide.",
            'created_at.required' => "La date de création est obligatoire.",
            'created_at.timestamp' => "La date de création doit être un timestamp valide.",
            'updated_at.required' => "La date de mise à jour est obligatoire.",
            'updated_at.timestamp' => "La date de mise à jour doit être un timestamp valide.",
        ];
    }
}
