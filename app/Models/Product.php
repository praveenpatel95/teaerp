<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function setProductNameAttribute($value)
    {
        $this->attributes['product_name'] = ucwords($value);
    }
}
