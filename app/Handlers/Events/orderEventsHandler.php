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


                $adjustmentAmount = $this->validateMerchandiseQty($product , $ReservedProductQtyLogObj,$event->isMerch);


            if ($product->allow_negative_reserve_qty == 0 && $adjustmentAmount > $product->reserved_qty) {
                $error = true;
                $reservedQty = $product->reserved_qty;
                if($event->isMerch){
                    $reservedQty = ($product->reserved_qty+($product->prev_qty*$product->num_items))/$product->num_items;
                    if($reservedQty < 1){
                        $reservedQty = 0;
                    }else {
                        $reservedQty = gettype($reservedQty) == 'double' ? (int)floor($reservedQty) : $reservedQty;
                    }
                }else{
                    $reservedQty = $reservedQty + $product->prev_qty;
                }
                $errorData = $product->reserved_qty;
                if($event->isMerch){
                    $errorData = $reservedQty * $product->num_items;
                }
                if($errorData < $adjustmentAmount) {
                    $message .= "<br>* $product->item_name, SKU: $product->sku, Quantity: $reservedQty";
                    $adjustQty[$product->id] = $ReservedProductQtyLogObj ? $reservedQty : $reservedQty;
                }
            }else if ($product->allow_negative_reserve_qty == 0 && $adjustmentAmount < 1){

                $error = true;
                $reservedQty = $product->reserved_qty;
                if($event->isMerch){
                    $reservedQty = ($product->reserved_qty+($product->prev_qty*$product->num_items))/$product->num_items;
                    if($reservedQty < 1){
                        $reservedQty = 0;
                    }else {
                        $reservedQty = gettype($reservedQty) == 'double' ? (int)floor($reservedQty) : $reservedQty;
                    }
                }else{
                    $reservedQty = $reservedQty + $product->prev_qty;
                }
                $errorData = $product->reserved_qty;
                if($event->isMerch){
                    $errorData = $reservedQty * $product->num_items;
                }
                if($errorData < $adjustmentAmount) {
                    $message .= "<br>* $product->item_name, SKU: $product->sku, Quantity: $reservedQty";
                    $adjustQty[$product->id] = $ReservedProductQtyLogObj ? $reservedQty : $reservedQty;
                }

            }else {

                if ($event->isMerch) {
                    if ($adjustmentAmount * $product->num_items > $product->reserved_qty) {

                        $error = true;
                        $reservedQty = $product->reserved_qty;
                        if ($event->isMerch) {
                            $reservedQty = ($product->reserved_qty+($product->prev_qty*$product->num_items)) / $product->num_items;
                            if ($reservedQty < 1) {
                                $reservedQty = 0;
                            } else {
                                $reservedQty = gettype($reservedQty) == 'double' ? (int)floor($reservedQty) : $reservedQty;
                            }
                        } else {
                            $reservedQty = $reservedQty + $product->prev_qty;
                        }
                        $errorData = $product->reserved_qty;
                        if($event->isMerch){
                            $errorData = $reservedQty * $product->num_items;
                        }
                        if($errorData < $adjustmentAmount) {
                            $message .= "<br>* $product->item_name, SKU: $product->sku, Quantity: $reservedQty";
                            $adjustQty[$product->id] = $ReservedProductQtyLogObj ? $reservedQty : $reservedQty;
                        }
                    }
                }else{
                    if ($adjustmentAmount > $product->reserved_qty) {

                        $error = true;
                        $reservedQty = $product->reserved_qty;
                        if ($event->isMerch) {
                            $reservedQty = ($product->reserved_qty+($product->prev_qty*$product->num_items)) / $product->num_items;
                            if ($reservedQty < 1) {
                                $reservedQty = 0;
                            } else {
                                $reservedQty = gettype($reservedQty) == 'double' ? (int)floor($reservedQty) : $reservedQty;
                            }
                        } else {
                            $reservedQty = $reservedQty + $product->prev_qty;
                        }
                        $errorData = $product->reserved_qty;
                        if($event->isMerch){
                            $errorData = $reservedQty * $product->num_items;
                        }
                        if($errorData < $adjustmentAmount) {
                            $message .= "<br>* $product->item_name, SKU: $product->sku, Quantity: $reservedQty";
                            $adjustQty[$product->id] = $ReservedProductQtyLogObj ? $reservedQty : $reservedQty;
                        }
                    }
                }
            }
        }

        return array_merge($ProductResponse, ['error' => $error, "message" => $message, "adjustQty" => $adjustQty]);
    }

    public function validateMerchandiseQty($product , $ReservedProductQtyLogObj, $isMerch){
        $adjustmentAmount = 0;
        if($product->product_is_broken_case == 1 && $isMerch == 0){
            if ($ReservedProductQtyLogObj and $product->prev_qty) {
                $adjustmentAmount = $product->qty - $product->prev_qty;
            } else {
                $adjustmentAmount = $product->qty;
            }
        }elseif($product->product_is_broken_case == 0 && $isMerch == 1){

            if($product->isPreIsBrokenCase == 1){

                if ($ReservedProductQtyLogObj and $product->prev_qty) {
                    $adjustmentAmount = ($product->qty * $product->num_items) - ($product->prev_qty );
                } else {
                    $adjustmentAmount = ($product->qty * $product->num_items);
                }
            }else {
                if ($ReservedProductQtyLogObj and $product->prev_qty) {
                    $adjustmentAmount = $product->qty  - ($product->prev_qty - $product->num_items);
                } else {
                    $adjustmentAmount = $product->qty;

                }
            }

        }else{
            if ($ReservedProductQtyLogObj and $product->prev_qty) {
                $adjustmentAmount = $product->qty - $product->prev_qty;
            } else {
                $adjustmentAmount = $product->qty;
            }
        }


        return $adjustmentAmount;

    }
}
