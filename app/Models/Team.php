<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';

    public const available = [
        'Available' => 1,
        'Unavailable' => 0,
    ];

    protected $fillable = [
        'captain_id',
        'name',
        'logo',
        'num_of_players',
        'game_id',
        'distance'
    ];

    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    protected $with = ['user', 'availableDays', 'prefferedAreas', 'members', 'game'];

    public function getAvailabilityAttribute($query)
    {
        return array_search($query, self::available);
    }

    public function scopeActive($query)
    {
        return $this->where('availability',1);
    }

    public function challenges()
    {
        return $this->hasMany('App\Models\Challange','challanger_id')->orWhere('opponent_id','=',$this->id);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'captain_id');
    }

    public function availableDays()
    {
        return $this->belongsToMany('App\Models\Days', 'team_available_days', 'team_id', 'day_id');
    }
    
    public function prefferedAreas()
    {
        return $this->hasMany('App\Models\PreferredArea', 'team_id');
    }
    
    public function members()
    {
        return $this->hasMany('App\Models\TeamMember', 'team_id');
    }

    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'game_id');
    }

    public function preferredArea()
    {
        return $this->hasMany('App\Models\PreferredArea');
    }
}
