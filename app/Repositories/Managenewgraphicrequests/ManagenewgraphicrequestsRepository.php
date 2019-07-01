<?php

namespace App\Repositories\Managenewgraphicrequests;

use Illuminate\Database\Eloquent\Collection;

interface ManagenewgraphicrequestsRepository
{
    public function search($query = "");
}