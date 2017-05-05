@if($setting['view-method'] =='native')
    <div class="sbox">

        <div class="sbox-title">
            <h4><i class="fa fa-eye"></i> <?php echo $pageTitle;?>

                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose('#{{ $pageModule }}')">
                    <i class="fa fa fa-times"></i></a>
            </h4>
        </div>

        <div class="sbox-content">
            @endif
            <div class="col-md-6">
                <div class="col-md-11" style="background: #FFF;box-shadow: 1px 1px 10px gray">
                <fieldset>
                    <legend>Order Info</legend>
                    <div class="form-group">
                        <label class=" label-control col-md-4 text-left">
                            {{ SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{ $row->id }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class=" label-control col-md-4 text-left">
                            {{ SiteHelpers::activeLang('PO Number', (isset($fields['po_number']['language'])? $fields['po_number']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{ \DateHelpers::formatStringValue($row->po_number) }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4 text-left">
                            {{ SiteHelpers::activeLang('Alt Address', (isset($fields['alt_address']['language'])? $fields['alt_address']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{ \DateHelpers::formatStringValue($row->alt_address) }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div>
                        <label class=" label-control col-md-4 text-left">
                            {{ SiteHelpers::activeLang('Ordered By', (isset($fields['user_id']['language'])? $fields['user_id']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {!! SiteHelpers::gridDisplayView($row->user_id,'user_id','1:users:id:username') !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div>
                        <label class=" label-control col-md-4 text-left">
                            {{ SiteHelpers::activeLang('Location', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                          {!!
                            SiteHelpers::gridDisplayView($row->location_id,'location_id','1:location:id:id|location_name')
                            !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group  ">
                        <label class=" label-control col-md-4 text-left">
                            {{ SiteHelpers::activeLang('Vendor', (isset($fields['vendor_id']['language'])? $fields['vendor_id']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {!! SiteHelpers::gridDisplayView($row->vendor_id,'vendor_id','1:vendor:id:vendor_name') !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Company', (isset($fields['company_id']['language'])? $fields['company_id']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {!! SiteHelpers::gridDisplayView($row->company_id,'company_id','1:company:id:company_name_long') !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group  ">
                        <label class=" label-control col-md-4 text-left">
                            {{ SiteHelpers::activeLang('Date Ordered', (isset($fields['date_ordered']['language'])? $fields['date_ordered']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{  \DateHelpers::formatDate($row->date_ordered) }}

                        </div>

                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Date Received', (isset($fields['date_received']['language'])? $fields['date_received']['language'] : array())) }}
                        </label>
                        <div class="col-md-8">
                            {{  DateHelpers::formatDate($order_data["received_date"])  }}


                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Received By', (isset($fields['received_by']['language'])? $fields['received_by']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {!! SiteHelpers::gridDisplayView($row->received_by,'received_by','1:users:id:username') !!}

                        </div>
                    </div>

                </fieldset>
            </div>
                </div>

            <div class="col-md-6">
                <div class="col-md-11" style="background: #FFF;box-shadow: 1px 1px 10px gray;box-sizing: border-box">
                <fieldset>
                    <legend> Order Details</legend>

                    <div>
                        <label class=" label-control col-md-4 text-left">
                            {{ SiteHelpers::activeLang('Order Type', (isset($fields['order_type_id']['language'])? $fields['order_type_id']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{
                            SiteHelpers::gridDisplayView($row->order_type_id,'order_type_id','1:order_type:id:order_type')
                            }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class=" label-control col-md-4 text-left">
                            {{ SiteHelpers::activeLang('Order Description', (isset($fields['order_description']['language'])? $fields['order_description']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{ \DateHelpers::formatStringValue($row->order_description) }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Total Cost (NO "$")', (isset($fields['order_total']['language'])? $fields['order_total']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{ CurrencyHelpers::formatCurrency(number_format($row->order_total,\App\Models\Order::ORDER_PERCISION))  }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Order Status', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {!! SiteHelpers::gridDisplayView($row->status_id,'status_id','1:order_status:id:status') !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">

                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('PO Notes', (isset($fields['po_notes']['language'])? $fields['po_notes']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{ \DateHelpers::formatStringValue($row->po_notes) }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Freight Type', (isset($fields['freight_id']['language'])? $fields['freight_id']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            <td> {!!
                                SiteHelpers::gridDisplayView($row->freight_id,'freight_id','1:freight:id:freight_type')
                                !!}
                            </td>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Office Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{ \DateHelpers::formatStringValue($row->notes) }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">  {{ SiteHelpers::activeLang('Warranty', (isset($fields['warranty']['language'])? $fields['warranty']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                          {{  SiteHelpers::gridDisplayView($row->warranty,'warranty','1:yes_no:id:yesno') }}

                        </div>
                    </div>
                    <div class="clearfix"></div>
                    {{--<div class="form-group">--}}
                        {{--<label class="label-control col-md-4">--}}
                            {{--{{ SiteHelpers::activeLang('Game ', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) }}--}}
                        {{--</label>--}}

                        {{--<div class="col-md-8">--}}
                            {{--{!! SiteHelpers::gridDisplayView($row->game_id,'game_id','1:vendor:id:vendor_name') !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Quantity', (isset($fields['quantity']['language'])? $fields['quantity']['language'] : array())) }}
                        </label>
<?php
$quantity=0;
if(!empty($order_data['orderQtyArray'])){
    for($i=0; $i < count($order_data['orderQtyArray']);$i++)
    {
        $quantity += $order_data['orderQtyArray'][$i];
    }
}
?>
                        <div class="col-md-8" id="quantity">
                            {{ \DateHelpers::formatZeroValue($quantity) }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('New Format', (isset($fields['new_format']['language'])? $fields['new_format']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {!! SiteHelpers::gridDisplayView($row->new_format,'new_format','1:yes_no:id:yesno') !!}

                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Order Content', (isset($fields['order_content']['language'])? $fields['order_content']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{ \DateHelpers::formatStringValue($row->order_content) }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Added To Inventory', (isset($fields['added_to_inventory']['language'])? $fields['added_to_inventory']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {!! SiteHelpers::gridDisplayView( $row->added_to_inventory,'added_to_inventory','1:yes_no:id:yesno') !!}

                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Tracking Number', (isset($fields['tracking_number']['language'])? $fields['tracking_number']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{ \DateHelpers::formatZeroValue($row->tracking_number) }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Game Ids', (isset($fields['game_ids']['language'])? $fields['game_ids']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{ \DateHelpers::formatZeroValue($row->game_ids) }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="label-control col-md-4">
                            {{ SiteHelpers::activeLang('Request Ids', (isset($fields['request_ids']['language'])? $fields['request_ids']['language'] : array())) }}
                        </label>

                        <div class="col-md-8">
                            {{ \DateHelpers::formatZeroValue($row->request_ids) }}
                        </div>
                    </div>
                </fieldset>
            </div><div class="clearfix"></div>
            </div>
            <div class="clr clear"></div>
            <br/>
            <div class="table-responsive col-md-12" style="box-shadow: 1px 1px 10px gray;background: #fff;padding:10px 10px 0px">
                <fieldset>
                    <legend> Order Items</legend>
            <table class="table">
                <thead>
                <tr>
                <th>N0 #</th>
                <th>Sku #</th>
                <th>Item Description</th>
                <th>Item Price</th>
                <th>Item Quantity </th>
                <th>Items Received</th>
                @if($row->order_type_id == \App\Models\order::ORDER_TYPE_PART_GAMES)
                <th>Game</th>
                @endif
                <th>Total ($)</th>

                </tr>
                </thead>
                <tbody>
                @if( $order_data['requests_item_count'] > 0 )
                @for($i = 0 ; $i < $order_data['requests_item_count']; $i++)
                    <tr>
                        <td>{{ $i+1 }} </td>
                        <td>{{  \DateHelpers::formatStringValue($order_data['skuNumArray'][$i])}}</td>
                        <td>{{  \DateHelpers::formatStringValue($order_data['orderDescriptionArray'][$i]) }}</td>
                        <td>{{CurrencyHelpers::formatCurrency(number_format($order_data['orderItemsPriceArray'][$i],\App\Models\Order::ORDER_PERCISION)) }}</td>
                        <td>{{  \DateHelpers::formatZeroValue($order_data['orderQtyArray'][$i]) }}</td>
                        <td>{{ $order_data['receivedItemsArray'][$i] }}</td>
                        @if($row->order_type_id == \App\Models\order::ORDER_TYPE_PART_GAMES)
                            <td>{{  \DateHelpers::formatStringValue($order_data['gamenameArray'][$i]) }}</td>
                        @endif
                        <td>{{ CurrencyHelpers::formatCurrency(number_format(  $order_data['orderItemsPriceArray'][$i]* $order_data['orderQtyArray'][$i],3))}}</td>
                          </tr>
                    @endfor
                <tr>
                    @if($row->order_type_id == \App\Models\order::ORDER_TYPE_PART_GAMES)
                        <td colspan="6">&nbsp;</td>
                    @else
                        <td colspan="5">&nbsp;</td>
                    @endif
                    <td  colspan="1"><b>Sub Total ($)</b></td>
                    <td><b>{{CurrencyHelpers::formatCurrency(number_format($order_data['order_total'],3)) }}</b></td>

                </tr>
                    @else
                    <tr>
                        @if($row->order_type_id == \App\Models\order::ORDER_TYPE_PART_GAMES)
                            <td colspan="8" class="text-center">Nothing  Found..</td>
                        @else
                            <td colspan="7" class="text-center">Nothing  Found..</td>
                        @endif

                    </tr>
                    @endif
                </tbody>
            </table>
                    </fieldset>
</div>
        </div>
    </div>
 @if($setting['form-method'] =='native')
        </div>
        </div>
    @endif

<style>
</style>
