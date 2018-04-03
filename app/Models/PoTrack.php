<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoTrack extends Model
{
    protected $table = 'po_track';
    public $timestamps = false;

    protected $fillable = [
        'po_number',
        'location_id',
        'sort'
    ];
}
