<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoTrack extends Model
{
    protected $table = 'po_track';

    public function isPOAvailable($po){
        $po = self::where('po_number', $po)->first();
        return $po ?: false;
    }
}
