<?php

namespace App\Repositories\Orders;

use Illuminate\Database\Eloquent\Collection;

interface OrdersRepository
{
    public function search($query = "");
}