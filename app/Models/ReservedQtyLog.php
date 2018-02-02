<?php

namespace App\Models;

use App\Http\Controllers\OrderController;
use App\Models\Sximo\Module;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ordertyperestrictions;
use Illuminate\Support\Facades\DB;
use Log;

class ReservedQtyLog extends Sximo
{
    protected $table = 'reserved_qty_log';
    protected $primaryKey = 'id';
    const TYPE = 1;

    public function __construct() {
        ini_set('memory_limit','1G');
        set_time_limit(0);
        parent::__construct();

    }
}
