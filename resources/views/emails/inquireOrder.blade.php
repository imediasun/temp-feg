
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="/sximo/css/sximo-light-blue.css">

<div class="sbox">
    <div class="sbox-content">
        <div class="col-md-12">
            <div class="col-md-12" style="background: #FFF;border: 1px solid black;">
                <fieldset>
                    <legend>Order Info</legend>
                    <div>
                        {{ SiteHelpers::activeLang('Location', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}: {!! SiteHelpers::gridDisplayView($row->location_id,'location_id','1:location:id:id|location_name',$nodata['location_id'])!!}
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        {{ SiteHelpers::activeLang('PO Number', (isset($fields['po_number']['language'])? $fields['po_number']['language'] : array())) }}: {{ \DateHelpers::formatStringValue($row->po_number) }}
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group  ">
                        {{ SiteHelpers::activeLang('Vendor', (isset($fields['vendor_id']['language'])? $fields['vendor_id']['language'] : array())) }} : {!! SiteHelpers::gridDisplayView($row->vendor_id,'vendor_id','1:vendor:id:vendor_name',$nodata['vendor_id']) !!}
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="clr clear"></div>
        <br/>

        <div class="col-md-12" style="border: 1px solid black;;background: #fff;padding:10px 10px 0px">
            <fieldset>
                <legend> Order Items</legend>
                <?php
                $dataOptions = explode(',',$case_price_permission->data_options);
                $case = in_array($order_data['order_type'],$dataOptions );
                ?>
                <table class="table table-wi table-responsive table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th style="border: 1px solid black;">NO #</th>
                        <th style="border: 1px solid black;">SKU #</th>
                        <th style="border: 1px solid black;">Item Name</th>
                        <th style="border: 1px solid black;">Case Price
                            @if($case == 1)
                                *
                            @endif
                        </th>
                        <th style="border: 1px solid black;">Unit Price
                            @if($case == 0)
                                *
                            @endif
                        </th>
                        <th style="border: 1px solid black;">Item Quantity </th>
                        @if($row->order_type_id == \App\Models\order::ORDER_TYPE_PART_GAMES)
                            <th>Game</th>
                        @endif
                        <th style="border: 1px solid black;">Total ($)</th>

                    </tr>
                    </thead>
                    <tbody>

                    @if( $order_data['requests_item_count'] > 0 )
                        @for($i = 0 ; $i < $order_data['requests_item_count']; $i++)
                            <tr>
                                <td style="border: 1px solid black;">{{ $i+1 }} </td>
                                <td style="border: 1px solid black;">{{  \DateHelpers::formatStringValue($order_data['skuNumArray'][$i])}}</td>
                                <td style="border: 1px solid black;">{{  \DateHelpers::formatStringValue($order_data['itemNameArray'][$i])}}</td>
                                <td style="border: 1px solid black;">
                                    {{CurrencyHelpers::formatPrice($order_data['itemCasePrice'][$i]) }}
                                </td>
                                <td style="border: 1px solid black;">
                                    {{CurrencyHelpers::formatPrice($order_data['orderPriceArray'][$i]) }}
                                </td>
                                <td style="border: 1px solid black;">{{  \DateHelpers::formatZeroValue($order_data['orderQtyArray'][$i]) }}</td>
                                @if($row->order_type_id == \App\Models\order::ORDER_TYPE_PART_GAMES)
                                    <td>{{  \DateHelpers::formatStringValue($order_data['gamenameArray'][$i]) }}</td>
                                @endif
                                <td style="border: 1px solid black;">{{ CurrencyHelpers::formatPrice($order_data['orderItemsPriceArray'][$i]* $order_data['orderQtyArray'][$i],\App\Models\Order::ORDER_PERCISION)}}</td>
                            </tr>
                        @endfor
                        <tr>
                            @if($row->order_type_id == \App\Models\order::ORDER_TYPE_PART_GAMES)
                                <td colspan="7">&nbsp;</td>
                            @else
                                <td colspan="6">&nbsp;</td>
                            @endif
                            <td  colspan="2" style="border: 1px solid black;"><b>Sub Total ($)</b></td>
                            <td colspan="1" style="border: 1px solid black;">
                                <b>{{CurrencyHelpers::formatPrice($order_data['order_total'],\App\Models\Order::ORDER_PERCISION) }}</b>
                            </td>

                        </tr>
                    @else
                        <tr>
                            @if($row->order_type_id == \App\Models\order::ORDER_TYPE_PART_GAMES)
                                <td colspan="9" class="text-center">Nothing  Found..</td>
                            @else
                                <td colspan="8" class="text-center">Nothing  Found..</td>
                            @endif

                        </tr>
                    @endif
                    </tbody>
                </table>
            </fieldset>
        </div>
        <div class="clr clear"></div>
    </div>
</div>
