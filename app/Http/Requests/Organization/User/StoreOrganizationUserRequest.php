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
}