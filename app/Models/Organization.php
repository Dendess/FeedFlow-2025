<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;      // <--- IMPORT INDISPENSABLE
use Illuminate\Database\Eloquent\Relations\BelongsToMany;  // <--- Recommandé pour le typage

class Organization extends Model
{
    use HasFactory;

    // Pas besoin de définir $table ou $timestamps, Laravel le fait par défaut.

    // On ne met que les champs modifiables par l'utilisateur.
    // 'id', 'created_at', 'updated_at' sont gérés automatiquement.
    protected $fillable = [
        'name',
        'user_id',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}