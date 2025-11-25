<?php
namespace App\Actions\User;

use App\DTOs\UserDTO;
use Illuminate\Support\Facades\DB;

final class UpdateUserAction
{
    public function __construct() {}

    /**
     * Update a User
     * @param UserDTO $dto
     * @return array
     */
    public function handle(UserDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
        });
    }
}
