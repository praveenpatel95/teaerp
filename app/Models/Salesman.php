<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
    use HasFactory;

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function setFatherNameAttribute($value)
    {
        $this->attributes['father_name'] = ucwords($value);
    }

    public function line(){
        return $this->hasOne(Line::class);
    }
}
