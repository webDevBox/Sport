<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Models\ChallangeLike;

class Challange extends Model
{
    /**
     * Challanges' Status
     *
     * @var array
     */
    public const Status = [
        'pending'  => 0,
        'accepted' => 1,
        'rejected' => 2
    ];

    protected $fillable = [
        'challanger_id',
        'opponent_id',
        'message',
        'proposed_time',
        'day_id',
        'venue_id',
        'game_id',
        'actor_id'
    ];

    protected $hidden = [
        'created_at' , 'updated_at',
    ];

    protected $with = ['game', 'venue', 'challenger', 'opponent'];

    /**
     * returns the id of a given role
     *
     * @param int $status  challenge's status
     * @return string status
     */
    public static function getStatusAttribute($status)
    {
        return array_search($status, self::Status);
    }   

    /**
     * returns the id of a given role
     *
     * @param int $status  challenge's status
     * @return string status
     */
    public static function getProposedTimeAttribute($proposed_time)
    {
        return parseByFormat($proposed_time, 'h:i A, d/m/Y');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::Status['pending'])->orderBy('id','desc')->where('opponent_id' , auth()->user()->team_id);
    }
    
    
    public function scopeMychallenge($query)
    {
        return $query->where('challanger_id' , auth()->user()->team_id)->whereIn('status',[0,2])->orderBy('id','desc');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', self::Status['accepted'])->orderBy('id','desc')->where('opponent_id' , auth()->user()->team_id);
    }

    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }
    
    public function like()
    {
        return $this->hasMany('App\Models\ChallangeLike');
    }
    
    // public static function isLike($id)
    // {
    //     $check = ChallangeLike::where('challange_id',$this->id)->where('user_id',$id)->first();
    //     if(isset($check))
    //         return true;
    //     return false;
    // }

    public function venue()
    {
        return $this->belongsTo('App\Models\Venue');
    }

    public function challenger()
    {
        return $this->belongsTo('App\Models\Team', 'challanger_id');
    }
    
    public function opponent()
    {
        return $this->belongsTo('App\Models\Team', 'opponent_id');
    }

    public static function getStatus($key){
        return self::Status[$key];
    }
}
