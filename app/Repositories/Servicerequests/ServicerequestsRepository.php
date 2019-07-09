<?php

namespace App\Repositories\Servicerequests;

use Illuminate\Database\Eloquent\Collection;

interface ServicerequestsRepository
{
    public function search($query = "");
}