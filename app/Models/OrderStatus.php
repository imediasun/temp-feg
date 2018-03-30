<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_status';
    protected $primaryKey = 'id';

    public function __construct(){
        parent::__construct();
    }

    public function getOrderStatuses(array $ids){
        return self::whereIn('id', $ids)
            ->orderBy('status', 'asc')
            ->get();
    }
}
