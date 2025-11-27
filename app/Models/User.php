<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    public function isAdmin(): bool
    {
        // adapte selon ton champ DB réel
        return $this->role === 'admin'
            || $this->is_admin === 1
            || $this->email === 'admin@example.com';
    }
    use HasFactory, Notifiable;

    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // AJOUTE CETTE RELATION
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    // AJOUTE CETTE MÉTHODE HELPER
    public function currentOrganizationId()
    {
        return session('current_organization_id') ?? $this->organizations()->first()?->id;
    }
}
