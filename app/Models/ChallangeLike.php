<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallangeLike extends Model
{
    protected $table = 'challange_likes';

    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    protected $fillable = [
        'user_id', 'challange_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
}
