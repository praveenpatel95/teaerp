<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    public function setRouteNameAttribute($value)
    {
        $this->attributes['route_name'] = ucwords($value);
    }

    public function lineRoute(){
        return $this->hasMany(LineRoute::class);
    }
}
