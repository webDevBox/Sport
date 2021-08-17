<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Days extends Model
{
    protected $table = 'days';

    protected $hidden = [
        'created_at' , 'updated_at', 'pivot'
    ];

    public function scopeActive($query)
    {
        return $query;
    }
}
