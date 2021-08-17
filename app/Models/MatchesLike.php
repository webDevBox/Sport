<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchesLike extends Model
{
    protected $table = 'matches_likes';

    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    protected $fillable = [
        'user_id', 'match_id'
    ];
}
