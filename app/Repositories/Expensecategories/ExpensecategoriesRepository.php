<?php

namespace App\Repositories\Expensecategories;

use Illuminate\Database\Eloquent\Collection;

interface ExpensecategoriesRepository
{
    public function search($query = "");
}