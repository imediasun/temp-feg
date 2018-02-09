<?php
namespace App\Models;

use App\Http\Controllers\OrderController;
use App\Models\Sximo\Module;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ordertyperestrictions;
use Illuminate\Support\Facades\DB;
use Log;

/**
 * Class Ordercontent
 * @package App\Models
 */
class Ordercontent extends Sximo
{
    /**
     * @var string
     */
    protected $table = 'order_contents';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(){
        return $this->belongsTo("App\Models\Order");
    }


}
