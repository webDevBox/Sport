<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $table = 'app_notifications';

    protected $fillable = [
        'type', 'sender_id', 'receiver_id', 'challange_id', 'match_id'
    ];

    protected $hidden = [
        'sender_id', 'receiver_id', 'is_read', 'updated_at'
    ];

    protected $with = [
        'challange', 'match', 'sender', 'senderTeam', 'type', 'notification'
    ];

    public function challange()
    {
        return $this->belongsTo('App\Models\Challange', 'challange_id');
    }
    
    public function match()
    {
        return $this->belongsTo('App\Models\Match', 'match_id');
    }

    public function sender(){
        return $this->belongsTo('App\Models\User', 'sender_id');
    }

    public function senderTeam(){
        return $this->belongsTo('App\Models\Team', 'sender_id', 'captain_id');
    }
    
    public function receiver(){
        return $this->belongsTo('App\Models\Team', 'receiver_id', 'captain_id');
    }

    public function notification()
    {
        return $this->belongsTo('App\Models\Notification', 'notification_id');
    }

    public function type(){
        return $this->belongsTo('App\Models\AppNotificationType', 'type');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read',0);
    }

    public static function getCreatedAtAttribute($created_at)
    {
        $date = parseByFormat($created_at , 'M d, Y,h:i A');

        $pos = strrpos($date, ',');

        if($pos !== false)
        {
            $subject = substr_replace($date, ' at ', $pos, strlen(','));
        }
        
        return $subject;
    }
}
