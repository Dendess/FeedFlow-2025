<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table    = 'surveys';
    public $timestamps  = true;
    protected $fillable = [
        'id', 'organization_id', 'user_id',
        'title', 'description', 'start_date', 'end_date', 'is_anonymous',
        'created_at', 'updated_at',
        'token',
    ];
    protected $casts = [
    ];

    protected static function booted()
    {
        static::creating(function ($survey) {
            if (! $survey->token) {
                $survey->token = Str::uuid(); // ou Str::random(40)
            }
        });
    }
}
