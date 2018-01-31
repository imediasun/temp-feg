<?php

namespace App\Handlers\Events;
use App\Library\FEG\System\FEGSystemHelper;
use App\Models\ReservedQtyLog;
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
        $ReservedQtyLog = new \App\Models\ReservedQtyLog();

        foreach($event->products as $productData){

            $product = $productData['product'];
            $orderItem = $productData['order_item'];

            if($product->is_reserved==1) {
                $reserve_qty_log_amount=$orderItem->qty;
                if ($product->allow_negative_reserve_qty == 1) {
                    $adjustmentAmount = $product->reserved_qty-$orderItem->qty;
                    //\DB::update("update products set reserved_qty='$adjustmentAmount' where id=".$product->id);
                    $product->reserved_qty=$adjustmentAmount;
                    $product->save();
                    $user= \AUTH::user();
                    $user_id=$user->id;
                    $order_id=$event->order_id;
                    $product_id=$product->id;


                    $LogData = [product_id=>$product_id,"order_id"=>$order_id,"adjustment_amount"=>$reserve_qty_log_amount,"adjusted_by"=>$user_id];
                    $ReservedQtyLog->insertRow($LogData);
                    /*$sql ='INSERT INTO `reserved_qty_log`(`product_id`, `order_id`, `adjustment_amount`, `adjusted_by`)';
                    $sql .=" VALUES($product_id,$order_id,$reserve_qty_log_amount,$user_id) ";
                    \DB::insert($sql);*/
                }else{
                    $adjustmentAmount = $product->reserved_qty-$orderItem->qty;
                   // $inactiveProduct = '';
                    $product->reserved_qty=$adjustmentAmount;
                    if($adjustmentAmount==0){
                        //$inactiveProduct = ', inactive=1 ';
                        $product->inactive=1;
                    }

                    $product->save();
                  //  \DB::update("update products set reserved_qty='$adjustmentAmount' $inactiveProduct where id=".$product->id);
                    $user= \AUTH::user();
                    $user_id=$user->id;
                    $order_id=$event->order_id;
                    $product_id=$product->id;
                    $LogData = [product_id=>$product_id,"order_id"=>$order_id,"adjustment_amount"=>$reserve_qty_log_amount,"adjusted_by"=>$user_id];
                    $ReservedQtyLog->insertRow($LogData);
                    /*$sql ='INSERT INTO `reserved_qty_log`(`product_id`, `order_id`, `adjustment_amount`, `adjusted_by`)';
                    $sql .=" VALUES($product_id,$order_id,$reserve_qty_log_amount,$user_id) ";
                    \DB::insert($sql);*/
                }
                if($product->reserved_qty_limit>=($product->reserved_qty-$orderItem->qty)){
                    $message = "<span style='color:red;'> Product reserved quantity limit is ".$product->reserved_qty_limit." and quantity ".($product->reserved_qty-$orderItem->qty)." is available for product <strong>(".$product->item_name.")</strong></span>";
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
