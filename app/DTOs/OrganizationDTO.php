<?php

namespace App\DTOs;

use Illuminate\Http\Request;

readonly class OrganizationDTO
{
    public function __construct(
        public string $name,
        public int $user_id, // On garde uniquement le propriétaire
    ) {}

    public static function fromRequest(Request $request): self
    {
        // On récupère les données validées
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        return new self(
            name: $data['name'] ?? '',
            user_id: $request->user()->id // <--- C'est ici que la magie opère !
        );
    }
}