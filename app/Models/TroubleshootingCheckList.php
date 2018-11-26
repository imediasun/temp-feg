<?php

namespace App\Models;

use App\Models\Sximo;
use Illuminate\Database\Eloquent\Model;

class TroubleshootingCheckList extends Sximo
{
    public function scopeIsActive($query){
        return $query->where('is_active',1);
    }
}
