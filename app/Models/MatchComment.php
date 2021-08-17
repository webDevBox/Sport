<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchComment extends Model
{
    protected $table = 'match_comments';

    protected $fillable = [
        'user_id', 'match_id', 'comment'
    ];

    protected $hidden = [
        'updated_at'
    ];

    protected $with = [
        'team'
    ];

    public function team(){
        return $this->belongsTo('App\Models\Team', 'user_id', 'captain_id');
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
