<?php

namespace App\Handlers\Events;

use App\Events\ordersEvent;
use App\Models\ReservedQtyLog;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class orderEventsHandler
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  ordersEvent $event
     * @return array
     */
    public function handle(ordersEvent $event)
    {
        $error = false;
        $ProductResponse = ['error' => $error, "message" => ''];
        $message = 'You have attempted to request more product than there is Reserved Quantity available. Your request has been modified to reflect this amount.';
        $adjustQty = [];
        foreach ($event->products as $product) {

            $ReservedProductQtyLogObj = ReservedQtyLog::where('order_id', $event->order_id)
                ->where('product_id', $product->id)
                ->orderBy('id', 'DESC')
                ->first();

            if ($ReservedProductQtyLogObj and $product->prev_qty) {
                $adjustmentAmount = $product->qty - $product->prev_qty;
            } else {
                $adjustmentAmount = $product->qty;
            }

            if ($product->allow_negative_reserve_qty == 0 && $adjustmentAmount > $product->reserved_qty) {
                $error = true;
                $message .= "<br>* $product->item_name, SKU: $product->sku, Quantity: $product->reserved_qty";
                $adjustQty[$product->id] = $product->reserved_qty < 1 ? $product->reserved_qty : $ReservedProductQtyLogObj ? $product->reserved_qty + $product->prev_qty : $product->reserved_qty;
            }
        }

        return array_merge($ProductResponse, ['error' => $error, "message" => $message, "adjustQty" => $adjustQty]);
    }
}
