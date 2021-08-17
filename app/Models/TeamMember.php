<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $table = 'team_members';

    protected $hidden = [
        'team_id', 'created_at' , 'updated_at'
    ];

    protected $fillable = [
        'name', 'phone', 'team_id'
    ];
}
