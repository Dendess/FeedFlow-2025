<?php
namespace App\Actions\User;

use App\DTOs\OrganizationDTO;
use Illuminate\Support\Facades\DB;

final class DeleteUserAction
{
    public function __construct() {}

    /**
     * Delete a user
     * @param UserDTO $dto
     * @return array
     */
    public function handle(UserDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
        });
    }
}
