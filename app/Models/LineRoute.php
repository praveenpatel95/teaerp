<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineRoute extends Model
{
    use HasFactory;

    public function route(){
        return $this->belongsTo(Route::class,'route_id');
    }

}
