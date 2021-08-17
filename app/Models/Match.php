<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    /**
     * Match's Status
     *
     * @var array
     */
    public const Status = [
        'scheduled' => 0,
        'played'    => 1,
        'cancelled' => 2
    ];

    protected $fillable = [
        'challange_id',
        'challanger_id',
        'opponent_id',
        'status',
    ];

    protected $hidden = [
        'created_at' , 'updated_at',
    ];

    protected $with = ['challenge'];

    /**
     * returns the status string
     *
     * @param int $status  Match's status
     * @return string status
     */
    public static function getStatusAttribute($status)
    {
        return array_search($status, self::Status);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', self::Status['scheduled']);
    }

    public function challenge()
    {
        return $this->belongsTo('App\Models\Challange', 'challange_id');
    }

    public function like()
    {
        return $this->hasMany('App\Models\MatchesLike');
    }
}
