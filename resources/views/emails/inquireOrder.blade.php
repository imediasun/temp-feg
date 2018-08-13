Location Name/ID: {{$order->location ? $order->location->location_name : ''}},
PO number: {{$order->po_number}},
Vendor Name: {{$order->vendor ? $order->vendor->vendor_name : ''}},
Order Total: {{$order->order_total}}

The details of Items Ordered are<\br>
---------------------------------------------<\br>
---------------------------------------------<\br>

@foreach($order->contents as $key=>$content)
Item# {{$key++}}
Item name: {{$content->item_name}},
Item Sku: {{$content->sku}},<\br>
Case price: {{$content->case_price}},
Unit Price: {{$content->unit_price}},
Quantity: {{$content->qty}}
---------------------------------------------<\br>
@endforeach