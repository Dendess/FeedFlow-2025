<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $organizationId = $this->input('organization_id');
        
        // Sécurité : L'user doit être ADMIN de l'organisation pour laquelle il crée le sondage
        return Auth::user()->organizations()
            ->where('organizations.id', $organizationId)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string'],
            'start_date'      => ['required', 'date', 'after_or_equal:today'],
            'end_date'        => ['required', 'date', 'after:start_date'],
            'is_anonymous'    => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'organization_id.required' => 'Une organisation est requise.',
            'end_date.after' => 'La date de fin doit être après la date de début.',
            'start_date.after_or_equal' => 'La date de début doit être aujourd\'hui ou une date future.',
            'title.required' => 'Le titre du sondage est obligatoire.',
            'description.required' => 'La description du sondage est obligatoire.',
            'organization_id.required' => 'Une organisation est requise.',
            'organization_id.exists' => 'L\'organisation sélectionnée est invalide.',
        ];
    }
}