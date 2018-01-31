<?php

namespace App\Handlers\Events;

use App\Library\FEG\System\FEGSystemHelper;

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
                    \DB::update("update products set reserved_qty='$adjustmentAmount' where id=".$product->id);
                    $user= \AUTH::user();
                    $user_id=$user->id;
                    $order_id=$event->order_id;
                    $product_id=$product->id;

                    $sql ='INSERT INTO `reserved_qty_log`(`product_id`, `order_id`, `adjustment_amount`, `adjusted_by`)';
                    $sql .=" VALUES($product_id,$order_id,$reserve_qty_log_amount,$user_id) ";
                    \DB::insert($sql);
                }else{
                    $adjustmentAmount = $product->reserved_qty-$product->qty;
                    $inactiveProduct = '';
                    if($adjustmentAmount==0){
                        $inactiveProduct = ', inactive=1 ';
                    }
                    \DB::update("update products set reserved_qty='$adjustmentAmount' $inactiveProduct where id=".$product->id);
                    $user= \AUTH::user();
                    $user_id=$user->id;
                    $order_id=$event->order_id;
                    $product_id=$product->id;

                    $sql ='INSERT INTO `reserved_qty_log`(`product_id`, `order_id`, `adjustment_amount`, `adjusted_by`)';
                    $sql .=" VALUES($product_id,$order_id,$reserve_qty_log_amount,$user_id) ";
                    \DB::insert($sql);
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
