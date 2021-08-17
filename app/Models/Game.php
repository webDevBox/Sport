<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';

    protected $fillable = [
        'name', 'status', 'logo'
    ];

    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    public function scopeActive($query)
    {
        return $query->whereStatus(1);
    }
}
