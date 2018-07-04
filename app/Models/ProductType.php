<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Sximo
{
    protected $table = 'product_type';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subType(){
        return $this->belongsTo(self::class, 'request_type_id', 'id');
    }
    //
}
