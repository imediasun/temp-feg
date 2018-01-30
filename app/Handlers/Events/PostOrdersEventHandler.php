<?php

namespace App\Handlers\Events;

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
                if ($product->allow_negative_reserve_qty == 1) {
                    $adjestmentAmount = $product->reserved_qty-$product->qty;
                    \DB::update("update products set reserved_qty='$adjestmentAmount' where product_id=".$product->id);
                }else{

                }
            }
        }
    }
}
