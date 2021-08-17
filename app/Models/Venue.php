<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $table = 'venues';

    protected $fillable = [
        'name', 'address', 'lat', 'lng'
    ];

    protected $hidden = [
        'id', 'address', 'created_at' , 'updated_at',
    ];
}
