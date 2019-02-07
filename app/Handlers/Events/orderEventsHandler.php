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

            if ($product->allow_negative_reserve_qty == 0) {

                $product->prev_qty = is_null($product->prev_qty) ||  $product->prev_qty == '' ? 0: $product->prev_qty;


                if ($event->isMerch) { // If Order is Merchandise
                    if ($product->product_is_broken_case &&  !($product->isPreIsBrokenCase)) {
                        // If Ordered Product Qty as Broken Case and Product Qty was not Broken Case Previously
                        $reservedQty = $product->reserved_qty + ($product->prev_qty * $product->num_items);
                        $remainingQty = $reservedQty - $product->qty;

                        if ($remainingQty < 0) {
                            $error = true;
                            $message .= "<br>* $product->item_name, SKU: $product->sku, Quantity: $reservedQty";
                            $adjustQty[$product->id] =  $reservedQty;
                        }

                    }elseif ($product->product_is_broken_case &&  ($product->isPreIsBrokenCase)) {
                        // If Product Qty is Broken Case and it was broken case previously
                        $reservedQty = $product->reserved_qty + $product->prev_qty;
                        $remainingQty = $reservedQty - $product->qty;

                        if ($remainingQty < 0) {
                            $error = true;
                            $message .= "<br>* $product->item_name, SKU: $product->sku, Quantity: $reservedQty";
                            $adjustQty[$product->id] = $reservedQty;
                        }

                    } elseif (!($product->product_is_broken_case) && $product->isPreIsBrokenCase) {
                    //If product is not Broken case but it was broken case previously
                        $reservedQty = $product->reserved_qty + $product->prev_qty ;
                        $remainingQty = $reservedQty - ($product->qty * $product->num_items);

                        if ($remainingQty < 0) {
                            $reservedQty = in_array(gettype(($reservedQty / $product->num_items)),['double','float' ]) ? (int) floor($reservedQty / $product->num_items):$reservedQty / $product->num_items;
                            $error = true;
                            $message .= "<br>* $product->item_name, SKU: $product->sku, Quantity: $reservedQty";
                            $adjustQty[$product->id] =  $reservedQty;
                        }

                    } else {
                        //If Order is a Merchandise order and product qty is not as broken case
                        $reservedQty = $product->reserved_qty + ($product->prev_qty * $product->num_items);
                        $orderedQty = $product->qty * $product->num_items;

                        if ($reservedQty < $orderedQty) {
                            $reservedQty = in_array(gettype(($reservedQty / $product->num_items)),['double','float' ]) ? (int) floor($reservedQty / $product->num_items):$reservedQty / $product->num_items;
                            $error = true;
                            $message .= "<br>* $product->item_name, SKU: $product->sku, Quantity: $reservedQty";
                            $adjustQty[$product->id] = $reservedQty;
                        }
                    }

                } else {
                    // If order is a non merchandise order
                    $reservedQty = $product->reserved_qty + $product->prev_qty;

                    if ($reservedQty < $product->qty) {
                        $error = true;
                        $message .= "<br>* $product->item_name, SKU: $product->sku, Quantity: $reservedQty";
                        $adjustQty[$product->id] =  $reservedQty;
                    }
                }
            }
        }
            return array_merge($ProductResponse, ['error' => $error, "message" => $message, "adjustQty" => $adjustQty]);

    }

}
