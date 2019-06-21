<?php

namespace App\Repositories\Productsubtype;

use Illuminate\Database\Eloquent\Collection;

interface ProductsubtypeRepository
{
    public function search($query = "");
}