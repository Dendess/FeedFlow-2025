<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table    = 'organizations';
    public $timestamps  = true;
    protected $fillable = [ 'id', 'name', 'user_id', 'created_at', 'updated_at' ];
    protected $casts = [
    ];

    public function users()
    {
        // Relation many-to-many with pivot 'organization_user'.
        // Include the 'role' pivot column so policies can check membership role (e.g. 'admin').
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

}
