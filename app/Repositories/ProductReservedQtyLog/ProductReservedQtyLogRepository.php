<?php

namespace App\Repositories\ProductReservedQtyLog;

use Illuminate\Database\Eloquent\Collection;

interface ProductReservedQtyLogRepository
{
    public function search($query = "");
}