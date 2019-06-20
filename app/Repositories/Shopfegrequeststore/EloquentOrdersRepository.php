<?php

namespace App\Repositories\Orders;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class EloquentOrdersRepository implements ProductsRepository
{
    public function search($query = "")
    {
        return Order::where('body', 'like', "%{$query}%")
            ->orWhere('title', 'like', "%{$query}%")
            ->get();
    }
}