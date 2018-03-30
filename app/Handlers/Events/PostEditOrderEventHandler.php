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
     * @param  PostEditOrderEvent $event
     * @return void
     */
    public function handle(PostEditOrderEvent $event)
    {
        $orderdProductIds = [];
        foreach ($event->products as $products) {

            if ($products->is_reserved == 1) {
                $Reserved_qty_id = '';

                $ReservedProductQtyLogObj = ReservedQtyLog::selectRaw('id,adjustment_amount as total_adjustment_amount')
                    ->where('order_id', $event->order_id)
                    ->where('product_id', $products->id)
                    ->orderBy('id', 'DESC')
                    ->first();
                $Reserved_qty_id = !empty($ReservedProductQtyLogObj) ? $ReservedProductQtyLogObj->id : null;

                if (!empty($ReservedProductQtyLogObj)) {

                    $ReservedProductQtyLogObj = ReservedQtyLog::selectRaw('id,adjustment_amount as total_adjustment_amount')
                        ->where('order_id', $event->order_id)
                        ->where('product_id', $products->id)
                        ->orderBy('id', 'DESC')
                        ->first();
                    $ProductObj = product::find($products->id);

                    $adjustmentAmount = $ProductObj->reserved_qty + $ReservedProductQtyLogObj->total_adjustment_amount;
                    $ProductObj->updateProduct(['reserved_qty' => $adjustmentAmount]);
                    $user = \AUTH::user();
                    $user_id = $user->id;
                    $ReservedLogData = [
                        "product_id" => $products->id,
                        "order_id" => $event->order_id,
                        "adjustment_amount" => $adjustmentAmount,
                        "variation_id" => $products->variation_id,
                        "adjusted_by" => $user_id,
                    ];
                    $ProductReservedQtyObject = new ReservedQtyLog();
                    $ProductReservedQtyObject->setPositiveAdjustment($ReservedLogData, $Reserved_qty_id);
                    $Reserved_qty_id = $ReservedProductQtyLogObj->id;
                }

                $ProductObj = product::find($products->id);
                $adjustmentAmount = $ProductObj->reserved_qty - $products->qty;
                if ($ProductObj->allow_negative_reserve_qty != 1 && $adjustmentAmount == 0) {
                    $inactive = 1;
                } else {
                    $inactive = 0;
                }

                $ProductObj->updateProduct(['reserved_qty' => $adjustmentAmount, 'inactive' => $inactive]);
                $ProductObj->save();

                $user = \AUTH::user();
                $user_id = $user->id;
                $order_id = $event->order_id;
                $product_id = $products->id;
                if ($products->order_product_id > 0) {
                    $ReservedLogData = [
                        "product_id" => $product_id,
                        "order_id" => $order_id,
                        "adjustment_amount" => $products->qty,
                        "variation_id" => $products->variation_id,
                        "adjusted_by" => $user_id,
                    ];
                    $ProductReservedQtyObject = new ReservedQtyLog();
                    $ProductReservedQtyObject->setNegativeAdjustment($ReservedLogData, $Reserved_qty_id);
                    $orderdProductIds[] = $products->order_product_id;
                }

            }
            $ProductObj = product::find($products->id);
            if ($products->reserved_qty_limit >= $adjustmentAmount) {
                $message = "<span style='color:red;'> Product reserved quantity limit is " . $products->reserved_qty_limit . " and quantity " . $ProductObj->reserved_qty . " is available for product <strong>(" . $products->item_name . ")</strong></span>";
                self::sendProductReservedQtyEmail($message);
                /*An email alert will be sent when the Reserved Quantity reaches an amount defined per-product. */
            }
        }

    }

    public static function sendProductReservedQtyEmail($message)
    {
        /*An email alert will be sent when the Reserved Quantity reaches an amount defined per-product. */
        $receipts = FEGSystemHelper::getSystemEmailRecipients("Product Reserved Quantity Email");
        FEGSystemHelper::sendSystemEmail(array_merge($receipts, array(
            'subject' => "Product Reserved Quantity Email Alert",
            'message' => $message,
            'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
        )));
    }

}
