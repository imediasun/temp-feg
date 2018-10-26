<option value="select_all">Select all</option>
@if($type == 'locations')
    @foreach($locations as $location)
        <option data-badge="" value="{{ $location['id'] }}">{{ $location->id." ".$location->location_name }}</option>
    @endforeach
    @elseif($type == 'products')
    @foreach($products as $product)
        <option data-badge="" value="{{ $product->id }}">{{ $product->vendor_description }}</option>
    @endforeach
@elseif($type == 'producttypes')
    @foreach($producttypes as $producttype)
        <option data-badge="" value="{{ $producttype->id }}">{{ $producttype->product_type }}</option>
    @endforeach
    @else

@endif