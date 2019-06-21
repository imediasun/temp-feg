<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderType extends Model
{
    protected $table = 'order_type';
    protected $primaryKey = 'id';

    public function __construct(){
        parent::__construct();
    }

}
