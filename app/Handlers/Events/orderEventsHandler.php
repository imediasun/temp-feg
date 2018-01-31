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
     * @param  ordersEvent  $event
     * @return void
     */
    public function handle(ordersEvent $event)
    {
        $error = false;
        $ProductResponse=['error'=>$error,"message"=>''];
        $message='You have attempted to request more product than there is Reserved Quantity available. Your request has been modified to reflect this amount.';
        foreach($event->products as $product){

            if($product->allow_negative_reserve_qty==0 && $product->reserved_qty<$product->qty){
                    $error=true;
                    $message .="<br>* Item Name: ".$product->item_name.", SKU: ".$product->sku.", Quantity: ".$product->reserved_qty."";
                  // $message .= "Total quantity ".$product->reserved_qty." is available for ".$product->item_name."<br />";
            }
        }
       return array_merge($ProductResponse,['error'=>$error,"message"=>$message]);
       // return $ProductResponse;
    }
}
