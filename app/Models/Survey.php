<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';
    public $timestamps  = true;


    protected $fillable = [
        'organization_id',
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'is_anonymous',
        'created_at',
        'updated_at',
        'token',

    ];

    // Relation : Un sondage appartient à une organisation.
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    // Survey belongs to a user (owner)
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Survey has many answers
    public function responses()
    {
        return $this->hasMany(\App\Models\SurveyAnswer::class, 'survey_id');
    }
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

    // Relation : Un sondage appartient à un utilisateur (le créateur).
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
