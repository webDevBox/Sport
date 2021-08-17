<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamAvailableDays extends Model
{
    protected $table = "team_available_days";

    protected $hidden = [
        'created_at' , 'updated_at',
    ];
}
