<?php

namespace App\Repositories\Vendor;

use Illuminate\Database\Eloquent\Collection;

interface VendorRepository
{
    public function search($query = "");
}