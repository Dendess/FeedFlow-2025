<?php


namespace App\Http\Requests\Organization\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Autorisation gérée par la Policy dans le Controller
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'role' => ['required', 'string', 'in:admin,member'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => "L'adresse e-mail est obligatoire.",
            'email.email' => "L'adresse e-mail doit être valide.",
            'email.exists' => "Aucun utilisateur trouvé avec cette adresse e-mail.",
            'role.required' => "Le rôle est obligatoire.",
            'role.string' => "Le rôle doit être une chaîne de caractères.",
            'role.in' => "Le rôle doit être soit 'admin' soit 'member'.",
        ];
    }
}