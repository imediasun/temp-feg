<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavoriteProduct extends Sximo
{
    protected $table = "user_favorite_products";
    protected $primaryKey = 'id';
    protected $guarded = [];
}
