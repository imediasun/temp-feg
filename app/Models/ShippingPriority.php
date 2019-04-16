<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingPriority extends Sximo
{
    public function scopeIsActive($query){
        return $query->where('is_active',1);
    }
}
