@if($optionType == 'products')
    @foreach($products as $product)
        <option value="{{ $product['id'] }}">{{ $product['vendor_description'] }}</option>
        @endforeach
    @elseif($optionType == 'productTypes')
    @foreach($productTypes as $productType)
        <option value="{{ $productType['id'] }}">{{ $productType['product_type'] }}</option>
    @endforeach
@endif