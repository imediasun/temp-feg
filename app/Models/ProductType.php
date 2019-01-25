<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Sximo
{
    protected $table = 'product_type';
    use SoftDeletes;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subType(){
        return $this->belongsTo(self::class, 'request_type_id', 'id');
    }
    //
}
