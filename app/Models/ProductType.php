<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends productsubtype
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
     *  If you want to change anything in this class please change in its parent
     *  App\Models\productsubtype
     */
}
