<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class VendorProductTrack extends Model
{
    protected $fillable = ['product_id','vendor_id', 'created_at'];

}
