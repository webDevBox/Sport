<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTeam extends Model
{
    protected $table = 'notification_teams';

    protected $fillable = [
        'notification_id','user_id'
    ];

    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    public static function getCreatedAtAttribute($created_at)
    {
        return parseByFormat($created_at, 'h:i A, d/m/Y');
    }
}
