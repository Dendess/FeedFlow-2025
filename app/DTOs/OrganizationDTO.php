<?php

namespace App\DTOs;

use Illuminate\Http\Request;

readonly class OrganizationDTO
{
    public function __construct(
        public string $name,
        public ?string $description, // Ajouté
        public int $user_id,         // Ajouté (CRITIQUE pour la sécurité)
    ) {}

    public static function fromRequest(Request $request): self
    {
        // On récupère les données validées
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        return new self(
            name: $data['name'] ?? '',
            description: $data['description'] ?? null,
            user_id: $request->user()->id // <--- C'est ici que la magie opère !
        );
    }
}