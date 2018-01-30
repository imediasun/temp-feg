<?php

namespace App\Handlers\Events;

use App\Events\ordersEvent;
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
        $ProductResponse=['error'=>false,"message"=>''];
        foreach($event->products as $product){

            if($product->allow_negative_reserve_qty==0){
                if(($product->reserved_qty-$product->qty)<0){
                    return array_merge($ProductResponse,['error'=>true,"message"=>"Total quantity ".$product->reserved_qty." is available for ".$product->item_name]);
                }

            }
        }
        return $ProductResponse;
    }
}
