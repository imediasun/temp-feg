<?php

namespace App\Repositories\Location;

use Illuminate\Database\Eloquent\Collection;

interface LocationRepository
{
    public function search($query = "");
}