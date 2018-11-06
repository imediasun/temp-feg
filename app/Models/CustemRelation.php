<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustemRelation extends Sximo
{
    protected $table = 'custom_relations';
    protected $primaryKey = 'id';
    protected $fillable = ['related_id','related_to','related_type','related_type_to','is_excluded'];
    //
}
