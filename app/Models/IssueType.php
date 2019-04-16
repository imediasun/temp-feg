<?php

namespace App\Models;

use App\Models\Sximo;
use Illuminate\Database\Eloquent\Model;

class IssueType extends Sximo
{
    protected $table = 'issue_types';

    public function scopeIsActive($query){
        return $query->where('is_active',1);
    }
}
