<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreferredArea extends Model
{
    protected $table = 'preferred_areas';

    protected $fillable = [
        'team_id' , 'venue_id'
    ];

    protected $hidden = [
        'team_id', 'created_at' , 'updated_at'
    ];

    protected $with = [
        'venues'
    ];

    public function venues()
    {
        return $this->belongsTo('App\Models\Venue', 'venue_id');
    }
}
