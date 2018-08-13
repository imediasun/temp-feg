Location Name/ID: {{$order->location ? $order->location->location_name : ''}},<br/>
PO number: {{$order->po_number}},<br/>
Vendor Name: {{$order->vendor ? $order->vendor->vendor_name : ''}},<br/>
Order Total: $ {{$order->order_total}}<br/>

The details of Items Ordered are<br/>
---------------------------------------------<br/>
---------------------------------------------<br/>

@foreach($order->contents as $key=>$content)
Item# {{$key++}}<br/>
Item name: {{$content->item_name}},<br/>
Item Sku: {{$content->sku}},<br/>
Case price: $ {{$content->case_price}},<br/>
Unit Price: $ {{$content->price}},<br/>
Quantity: {{$content->qty}}<br/>
---------------------------------------------<br/>
@endforeach