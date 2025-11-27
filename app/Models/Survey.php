<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';
    public $timestamps  = true;

    protected $fillable = [
        'id', 'organization_id', 'user_id',
        'title', 'description', 'start_date', 'end_date', 'is_anonymous',
        'created_at', 'updated_at',
        'token',
    ];

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
}
