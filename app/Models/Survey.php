<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survey extends Model
{
    use HasFactory;

    // Champs autorisés à être remplis (token est inclus car l'Action le remplit)
    protected $fillable = [
        'organization_id', 'user_id', 'title', 'token', 'description',
        'start_date', 'end_date', 'is_anonymous',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_anonymous' => 'boolean',
    ];

    // Relation vers l'organisation propriétaire
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // Relation vers l'utilisateur créateur (Propriétaire)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Survey has many answers
    public function responses()
    {
        return $this->hasMany(\App\Models\SurveyAnswer::class, 'survey_id');
    }
}
