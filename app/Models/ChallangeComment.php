<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallangeComment extends Model
{
    protected $table = 'challange_comments';

    protected $fillable = [
        'user_id', 'challange_id', 'comment'
    ];

    protected $hidden = [
         'updated_at'
    ];

    protected $with = [
        'team'
    ];

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

    public function team(){
        return $this->belongsTo('App\Models\Team', 'user_id', 'captain_id');
    }
}
