<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class DigitalPackingList extends Sximo
{
    protected $table = 'digital_packing_lists';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }
    public function truncateString($string)
    {
        $string = str_replace(["&",",",'"'],"",$string);
        if (strlen($string) < 50 || strlen($string) == 50 ) {
           return $string;
        }
        else{
            $string = substr($string,0,50);
            return $string;
        }
    }
    public function isOrderReceived($order_id)
    {
        $order = Order::where("id",'=',$order_id)->first();
            $orderedQty = 0;
            $receivedQty = -1;
        if($order) {
            if ($order->contents) {
                $orderedQty = $order->contents->sum('qty');
            }
            if ($order->orderReceived) {
                $receivedQty = $order->orderReceived->sum('quantity');
            }
        }
            if ($orderedQty == $receivedQty && $order->is_freehand == 0) {
                return $flagCheck = true;
            } else {
                return $flagCheck = false;
            }

    }
    public function getTruncateString($string)
    {
        if (strlen($string) < 50 || strlen($string) == 50 ) {
            return $string;
        }
        else{
            $string = substr($string,0,50);
            return $string;
        }
    }
}
