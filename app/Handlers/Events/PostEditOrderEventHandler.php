<?php

namespace App\Handlers\Events;

use App\Library\FEG\System\FEGSystemHelper;
use App\Models\ReservedQtyLog;
use App\Models\product;
use App\Models\order;
use App\Events\PostEditOrderEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostEditOrderEventHandler
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostEditOrderEvent  $event
     * @return void
     */
    public function handle(PostEditOrderEvent $event)
    {

        foreach($event->products as $products) {

            if ($products->is_reserved == 1) {

                $ReservedProductQtyLogObj = ReservedQtyLog::selectRaw('id,adjustment_amount as total_adjustment_amount')
                    ->where('order_id',$event->order_id)
                    ->where('product_id',$products->id)
                    ->get();

                if(empty($ReservedProductQtyLogObj[0])) {


                    $user= \AUTH::user();
                    $user_id=$user->id;
                    $order_id=$event->order_id;
                    $product_id=$products->id;
                    $ReservedLogData = [
                        "product_id"=>$product_id,
                        "order_id"=>$order_id,
                        "adjustment_amount"=>$products->qty,
                        "adjusted_by"=>$user_id,
                    ];
                    $ProductReservedQtyObject= new ReservedQtyLog();

                    $ProductReservedQtyObject->insert($ReservedLogData);
                }else {
                    $ReservedProductQtyLogObj = ReservedQtyLog::selectRaw('id,adjustment_amount as total_adjustment_amount')
                        ->where('order_id', $event->order_id)
                        ->where('product_id', $products->id)
                        ->get();
                    $ProductObj = product::find($products->id);

                    $ProductObj->reserved_qty = $products->reserved_qty + $ReservedProductQtyLogObj[0]->total_adjustment_amount;


                    $ProductObj->save();
                }


                $ProductObj = product::find($products->id);
                $ProductObj->reserved_qty = ( $ProductObj->reserved_qty-$products->qty);
                if($ProductObj->reserved_qty==0 && $ProductObj->allow_negative_reserve_qty==0){
                    $ProductObj->inactive=1;
                }elseif($ProductObj->reserved_qty > 0){
                    $ProductObj->inactive=0;
                }
                $ProductObj->save();
                $ReservedQtyLog = ReservedQtyLog::find($ReservedProductQtyLogObj[0]->id);
                $ReservedQtyLog->adjustment_amount=$products->qty;
                $ReservedQtyLog->save();

            }
            $ProductObj = product::find($products->id);
            if($products->reserved_qty_limit>=$ProductObj->reserved_qty){
                $message = "<span style='color:red;'> Product reserved quantity limit is ".$products->reserved_qty_limit." and quantity ".$ProductObj->reserved_qty." is available for product <strong>(".$products->item_name.")</strong></span>";
                self::sendProductReservedQtyEmail($message);
                /*An email alert will be sent when the Reserved Quantity reaches an amount defined per-product. */
            }

        }


    }
    public static function sendProductReservedQtyEmail($message){
        /*An email alert will be sent when the Reserved Quantity reaches an amount defined per-product. */
        $receipts = FEGSystemHelper::getSystemEmailRecipients("Product Reserved Quantity Email");
        FEGSystemHelper::sendSystemEmail(array_merge($receipts, array(
            'subject' => "Product Reserved Quantity Email Alert",
            'message' => $message,
            'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
        )));
    }
}
