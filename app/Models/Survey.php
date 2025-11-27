<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';
    
    protected $fillable = [
        'organization_id', 
        'user_id',
        'title', 
        'description', 
        'start_date', 
        'end_date', 
        'is_anonymous',
        'token',
    ];

    // Relation : Un sondage appartient à une organisation.
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    
    // Relation : Un sondage appartient à un utilisateur (le créateur).
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}