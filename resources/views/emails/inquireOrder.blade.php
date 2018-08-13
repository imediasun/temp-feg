Location Name/ID: {{$order->location ? $order->location->location_name : ''}},\r\n
PO number: {{$order->po_number}},\r\n
Vendor Name: {{$order->vendor ? $order->vendor->vendor_name : ''}},\r\n
Order Total: {{$order->order_total}}\r\n

The details of Items Ordered are\r\n
---------------------------------------------\r\n
---------------------------------------------\r\n

@foreach($order->contents as $key=>$content)
Item# {{$key++}}
Item name: {{$content->item_name}},
Item Sku: {{$content->sku}},\r\n
Case price: {{$content->case_price}},
Unit Price: {{$content->unit_price}},
Quantity: {{$content->qty}}
---------------------------------------------\r\n
@endforeach