<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class StoreSurveyAction
{
    public function __construct() {}

    /**
     * Store a Survey
     * @param SurveyDTO $dto
     * @return Survey
     */
    public function handle(SurveyDTO $dto): Survey
    {
        return DB::transaction(function () use ($dto) {
            return Survey::create([
                'organization_id' => $dto->organization_id ?? null,
                'user_id' => $dto->user_id ?? null,
                'title' => $dto->title ?? null,
                'description' => $dto->description ?? null,
                'start_date' => $dto->start_date ?? null,
                'end_date' => $dto->end_date ?? null,
                'is_anonymous' => $dto->is_anonymous ?? false,
                'token' => Str::random(32),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
