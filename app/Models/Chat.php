<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';

    protected $fillable = [
        'sender_team', 'receiver_team', 'match_id', 'chat_id'
    ];

    protected $with = [
        'sender', 'receiver', 'match'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function sender()
    {
        return $this->belongsTo('App\Models\Team', 'sender_team');
    }
    
    public function receiver()
    {
        return $this->belongsTo('App\Models\Team', 'receiver_team');
    }
    
    public function match()
    {
        return $this->belongsTo('App\Models\Match');
    }
}
