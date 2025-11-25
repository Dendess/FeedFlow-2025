<?php
namespace App\Actions\User;

use App\DTOs\UserDTO;
use Illuminate\Support\Facades\DB;

final class StoreUserAction
{
    public function __construct() {}

    /**
     * Store a User
     * @param UserDTO $dto
     * @return array
     */
    public function handle(UserDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
        });
    }
}
