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
class OrderContent extends Sximo
{
    /**
     * @var string
     */
    protected $table = 'order_contents';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo("App\Models\product");
    }

    public function order()
    {
        return $this->belongsTo("App\Models\order");
    }

    public function getOrderQty($order_id){
        return self::where('order_id', $order_id)->sum('qty');
    }

}
