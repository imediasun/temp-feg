<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservedQtyLog extends Model
{
    protected $table = 'reserved_qty_log';
    protected $primaryKey = 'id';
    const TYPE = 1;

    public function __construct() {
        parent::__construct();

    }
}
