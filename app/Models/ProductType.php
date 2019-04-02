<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Sximo
{

    protected $table = 'product_type';
    protected $primaryKey = 'id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
     *  If you want to change anything in this class please change in its parent
     *  App\Models\Productsubtype
     */
}
