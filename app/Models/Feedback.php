<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
        'user_id', 'message'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $with = [
        'user'
    ];

    public static function getCreatedAtAttribute($created_at)
    {
        return parseByFormat($created_at, 'h:i A, d/m/Y');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
