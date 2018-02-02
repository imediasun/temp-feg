<?php

namespace App\Handlers\Events;

use App\Library\FEG\System\FEGSystemHelper;
use App\Models\ReservedQtyLog;
use App\Models\product;
use App\Models\order;
use App\Events\PostOrdersEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostOrdersEventHandler
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
     * @param  PostOrdersEvent  $event
     * @return void
     */
    public function handle(PostOrdersEvent $event)
    {

        foreach($event->products as $product){

            if($product->is_reserved==1) {
                $reserve_qty_log_amount=$product->qty;
                if ($product->allow_negative_reserve_qty == 1) {
                    $adjustmentAmount = $product->reserved_qty-$product->qty;
                    $ProductObj = product::find($product->id);
                    $ProductObj->reserved_qty = $adjustmentAmount;
                    $ProductObj->save();

                    $user= \AUTH::user();
                    $user_id=$user->id;
                    $order_id=$event->order_id;
                    $product_id=$product->id;
                    $ReservedLogData = [
                        "product_id"=>$product_id,
                        "order_id"=>$order_id,
                        "adjustment_amount"=>$reserve_qty_log_amount,
                        "adjustment_type"=>"negative",
                        "adjusted_by"=>$user_id,
                    ];
                    if($product->order_product_id>0) {
                        $ProductReservedQtyObject = new ReservedQtyLog();

                        $ProductReservedQtyObject->insert($ReservedLogData);
                    }

                }else{
                    $adjustmentAmount = $product->reserved_qty-$product->qty;

                    $ProductObj = product::find($product->id);
                    $ProductObj->reserved_qty = $adjustmentAmount;

                    if($adjustmentAmount==0){
                        $ProductObj->inactive=1;
                    }
                    $ProductObj->save();
                    $user= \AUTH::user();
                    $user_id=$user->id;
                    $order_id=$event->order_id;
                    $product_id=$product->id;
                    $ReservedLogData = [
                        "product_id"=>$product_id,
                        "order_id"=>$order_id,
                        "adjustment_amount"=>$reserve_qty_log_amount,
                        "adjustment_type"=>"negative",
                        "adjusted_by"=>$user_id,
                    ];
                    if($product->order_product_id>0) {
                        $ProductReservedQtyObject = new ReservedQtyLog();

                        $ProductReservedQtyObject->insert($ReservedLogData);
                    }

                }
                if($product->reserved_qty_limit>=($product->reserved_qty-$product->qty)){
                    $message = "<span style='color:red;'> Product reserved quantity limit is ".$product->reserved_qty_limit." and quantity ".($product->reserved_qty-$product->qty)." is available for product <strong>(".$product->item_name.")</strong></span>";
                    self::sendProductReservedQtyEmail($message);
                    /*An email alert will be sent when the Reserved Quantity reaches an amount defined per-product. */
                }
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
