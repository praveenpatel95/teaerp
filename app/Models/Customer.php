<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    function getCustomerPhotoAttribute($value){
        return asset('storage/'.$value);
    }
    public function setCustomerNameAttribute($value)
    {
        $this->attributes['customer_name'] = ucwords($value);
    }

    public function setFatherNameAttribute($value)
    {
        $this->attributes['father_name'] = ucwords($value);
    }
    public function sales(){
        $this->hasMany(Sale::class,'customer_id');
    }

    public function route(){
        $this->hasOne(Route::class);
    }


}
