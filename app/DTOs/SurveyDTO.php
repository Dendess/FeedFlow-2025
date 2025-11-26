<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class SurveyDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $start_date,
        public readonly string $end_date,
        public readonly bool $is_anonymous,
        public readonly int $organization_id,
        public readonly int $user_id
    ) {}

    public static function fromRequest(Request $request): self
    {
        // Récupération de l'ID de l'organisation depuis la session
        $organizationId = session('organization_id');

        if (!$organizationId) {
            // Vous devez avoir un mécanisme pour garantir que l'organization_id est en session
            throw new \RuntimeException('L\'ID de l\'organisation courante est manquant en session.');
        }

        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            start_date: $request->validated('start_date'),
            end_date: $request->validated('end_date'),
            is_anonymous: $request->boolean('is_anonymous'),

            organization_id: $organizationId,
            user_id: auth()->id() // ID de l'utilisateur authentifié
        );
    }
}
