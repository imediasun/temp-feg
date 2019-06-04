<?php

namespace App\Repositories\Products;

use Illuminate\Database\Eloquent\Collection;

interface ProductsRepository
{
    public function search($query = "");
}