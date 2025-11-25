<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class OrganizationDTO
{
    private function __construct(

        public bigint $id,
        public varchar $name,
        public bigint $user_id,
        public timestamp $created_at,
        public timestamp $updated_at,

    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(

            id :   $request->validated('id'),
            name : $request->validated('name'),
            user_id : $request->validated('user_id'),
            created_at : $request->validated('created_at'),
            updated_at : $request->validated('updated_at'),

        );
    }
}
