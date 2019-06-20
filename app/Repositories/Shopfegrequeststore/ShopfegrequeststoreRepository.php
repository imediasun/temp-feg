<?php

namespace App\Repositories\Shopfegrequeststore;

use Illuminate\Database\Eloquent\Collection;

interface ShopfegrequeststoreRepository
{
    public function search($query = "");
}