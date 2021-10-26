<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;

    public function lineRoute()
    {
        return $this->hasMany(LineRoute::class);
    }

    public function setLineNameAttribute($value)
    {
        $this->attributes['line_name'] = ucwords($value);
    }

    public function sales()
    {
        $this->hasMany(Sale::class, 'customer_id');

    }

    public function route()
    {
        $this->hasOne(Route::class);

    }
}
