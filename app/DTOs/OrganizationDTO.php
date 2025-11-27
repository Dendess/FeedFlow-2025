<?php
namespace App\DTOs;

use Illuminate\Http\Request;

readonly class OrganizationDTO
{
    public function __construct(
        public string $name,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = method_exists($request, 'validated') ? $request->validated() : $request->all();

        return new self(
            name: $validated['name'] ?? $request->input('name') ?? '',
        );
    }
}