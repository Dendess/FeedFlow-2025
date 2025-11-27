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
        $organizationId = auth()->user()->currentOrganizationId();

        if (!$organizationId) {
            throw new \RuntimeException('Vous devez être assigné à une organisation.');
        }

        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            start_date: $request->validated('start_date'),
            end_date: $request->validated('end_date'),
            is_anonymous: $request->boolean('is_anonymous'),
            organization_id: $organizationId,
            user_id: auth()->id()
        );
    }
}
