<?php

namespace App\DTOs;

use Illuminate\Http\Request;

readonly class OrganizationUserDTO
{
    public function __construct(
        public string $email,
        public string $role = 'member',
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->validated('email'),
            role: $request->validated('role', 'member'),
        );
    }
}