<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'game_id','team','title' , 'body'
    ];

    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    protected $with = ['game','teams'];

    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'game_id');
    }
    
    public function teams()
    {
        return $this->hasMany('App\Models\NotificationTeam');
    }

    public static function getCreatedAtAttribute($created_at)
    {
        return parseByFormat($created_at, 'h:i A, d/m/Y');
    }
}
