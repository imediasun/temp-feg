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
    public function __construct()
    {
        parent::__construct();

    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo("App\Models\Order");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo("App\Models\product");
    }

}
