<?php

namespace App\Handlers\Events;

use App\Events\PostSaveOrderEvent;
use App\Library\FEG\System\FEGSystemHelper;
use App\Models\product;
use App\Models\ReservedQtyLog;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostSaveOrderEventHandler
{
    /**
     * Create the event listener.
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
     * @param  PostSaveOrderEvent  $event
     * @return void
     */
    public function handle(PostSaveOrderEvent $event)
    {
        $item = (object)$event->order_item;
        $product = product::find($item->product_id);

        if($product->is_reserved==1) {

            $ReservedProductQtyLogObj = ReservedQtyLog::where('order_id', $event->order_id)
                ->where('product_id', $product->id)
                ->orderBy('id', 'DESC')
                ->first();

            if($ReservedProductQtyLogObj and $item->prev_qty){
                $adjustmentAmount = ($product->reserved_qty + $item->prev_qty) - $item->qty;
            }else{
                $adjustmentAmount = $product->reserved_qty - $item->qty;
            }

            if($product->allow_negative_reserve_qty != 1 and $adjustmentAmount == 0) {
                $inactive = 1;
            }else{
                $inactive = 0;
            }

            $product->updateProduct(['reserved_qty' => $adjustmentAmount, 'inactive' => $inactive]);
            $product->save();

            $reservedLogData = [
                "product_id" => $item->product_id,
                "order_id" => $item->order_id,
                "adjustment_amount" => $item->qty,
                "adjustment_type" => "negative",
                "adjusted_by" => \AUTH::user()->id,
            ];

            $reservedQtyLog = new ReservedQtyLog();
            $reservedQtyLog->insert($reservedLogData);

            if($adjustmentAmount <= $product->reserved_qty_limit){
                $message = "<span style='color:red;'> Product Name : $product->item_description <br> Reserved Qty Limit: $product->reserved_qty_limit <br> Reserved Qty Available: $adjustmentAmount</span>";
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
