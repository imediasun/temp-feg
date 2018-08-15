<div style="font-family: helvetica;font-size:14px;">
    <p style="font-size: 36px"><img src="<?php echo public_path() . '/fegpo.png';?>" width="50"> {{ $main_title }} </p>

    <div style="position:relative">
        <div style="widtd:50%">
            <p>- BILL TO - <br/>
                {{ $data[0]['company_name_long'] }} <br/>
                1265 Hamilton Parkway<br/>Itasca, Illinois 60143 <br/>
            </p>
        </div>
        <div style="width:50%; position:absolute; left:50%;top:8px">
            <table width="100%" style="border-collapse: collapse">
                <tr>
                    <td style="width:50%;border:1px solid #000;border-right:none;text-align: center;;padding:6px 0px;">Date:
                        <br/> {{ DateHelpers::formatDate($data[0]['date_ordered']) }} </td>
                    <td style="width:50%;border:1px solid #000;text-align: center;padding:6px 0px;">PO #
                        <br/>   {{ $data[0]['po_number'] }} </td>
                </tr>
            </table>
        </div>
        <div id="vendor">
            <table width="100%" style="border-collapse: collapse;">
                <tr><th width="50%" style="text-align: left">Vendor</th><th style="text-align: left;color:red" width="50%">Ship To</th></tr>
                <tr>
                    <td style="border:1px solid #000; border-right:none; border-bottom: none; padding: 10px; padding-top: 0px; margin-top:0px;">
                        {{ $data[0]['vendor_name'] }} <br/>
                        {{ $data[0]['vend_street1'] }}<br/>
                        {{ $data[0]['vend_city'] }}
                        {{ $data[0]['vend_state'] }}
                        {{ $data[0]['vend_zip'] }} <br/><br/>
                        {{ $data[0]['vend_contact'] }}
                        {{ isset($data[0]['vend_email'])?$data[0]['vend_email']:"" }}<br/>
                        @if(isset($data[0]['billing_account']) && $data[0]['billing_account']!='0')
                            <b>Billing Account Number: </b>
                            {{$data[0]['billing_account']}}
                        @endif
                        <br/>
                    </td>
                    <td style="vertical-align:baseline;border-top:1px solid #000; border-right:1px solid #000;border-bottom: none;border-left: 1px solid #000;  padding-left: 10px; padding-top: 0px; margin-top:0px; color:red"><span style="padding: 0px !important;">
                            {{ preg_replace("/(\r?\n){2,}/", "\n\n", $data[0]['company_name_long'])}}</span>
                        {{ isset($data[0]['po_location'])?trim($data[0]['po_location']):"" }}<br />
                        {{ isset($data[0]['po_street1_ship'])?trim($data[0]['po_street1_ship']):"" }} <br/>
                        {{ isset($data[0]['po_city_ship'])?trim($data[0]['po_city_ship']):"" }}
                        {{ isset($data[0]['po_state_ship'])?$data[0]['po_state_ship']:"" }}
                        {{ isset($data[0]['po_city_zip'])?$data[0]['po_city_zip']:"" }}
                        {{ isset($data[0]['po_zip_ship'])?$data[0]['po_zip_ship']:"" }} <br/>
                        <br/>
                        <br/>
                        @if($data[0]['ismerch'] && (in_array($data[0]['order_type_id'], ['6', '7', '8'])))
                        @else
                            {{ $data[0]['fedex_number'] ?  'Please Ship parcels using '.Lang::get('core.fedex_number') .': '.$data[0]['fedex_number'] : ''}}
                        @endif
                    </td>
                </tr>
                <tr >
                    <td style="border-top: 0xp;border-left: 1px solid #000;border-bottom:1px solid #000; ">&nbsp;</td>
                    <td style="border-top: 0px; border-right:1px solid #000;border-left:1px solid #000;border-bottom:1px solid #000;padding-left:10px; color: red;text-align: justify">
                        {{  isset($data[0]['for_location'])?$data[0]['for_location']:""}}
                        {{ isset($data[0]['po_add_notes'])?$data[0]['po_add_notes']:"" }}
                    </td>
                </tr>
            </table>
        </div>
<br/>
        <div style="width:100%;border:1px solid #000;padding:8px; border-collapse:collapse;">
            <p> {{ $data[0]['po_notes'] }}</p>
            <p>{!! @$data[0]['relationships'] !!}</p>
        </div>
        <br/>

        <div>

            <table width="100%"  style="border-collapse: collapse;overflow:hidden;">
                <tr>
                    <td style="padding:8px;text-align: left;border:1px solid #000"> Ordered By
                        : {{ $data[0]['first_name'] }} {{ $data[0]['last_name'] }} <br/>
                        Item Name
                    </td>
                    @if(($data[0]['new_format']==1))
                        <?php
                            $case_price_categories = [];
                            if(isset($data['pass']['calculate price according to case price']))
                            {
                                $case_price_categories = explode(',',$data['pass']['calculate price according to case price']->data_options);
                            }
                            $case_price_if_no_unit_categories = [];
                            if(isset($data['pass']['use case price if unit price is 0.00']))
                            {
                                $case_price_if_no_unit_categories = explode(',',$data['pass']['use case price if unit price is 0.00']->data_options);
                            }
                        ?>
                        <td style="padding:8px;border:1px solid #000; text-align: right">@if(in_array($data[0]['order_type_id'],$case_price_if_no_unit_categories))Unit/Case Price @elseif(in_array($data[0]['order_type_id'],$case_price_categories)) Case Price @else Unit Price @endif</td>
                        <td style="padding:8px;border:1px solid #000;text-align: center">QTY</td>
                        <td style="padding:9px;border:1px solid #000;text-align: right">Item Total</td>
                        <td></td>
                </tr>
                @for($i=0;$i < count($data[0]['orderDescriptionArray']);$i++)
                    <tr>
                        <td style="padding:8px;border:1px dotted #000; border-top:none">  {{ $data[0]['orderDescriptionArray'][$i] }} <br/></td>
                        <td style="padding:8px;border:1px dotted #000;border-top:none;text-align: right">  {{ \CurrencyHelpers::formatPrice($data[0]['orderItemsPriceArray'][$i], \App\Models\Order::ORDER_PERCISION) }} <br/></td>
                        <td style="padding:8px;border:1px dotted  #000;border-top:none;text-align: center">  {{ $data[0]['orderQtyArray'][$i] }} <br/></td>
                        <td style="padding:2px;border:1px dotted  #000;border-top:none;border-right:1px dotted #000;text-align: right ">  {{ CurrencyHelpers::formatPrice(($data[0]['brokenCaseArray'][$i]) ? $data[0]['OriginalUnitPriceArray'][$i]* $data[0]['orderQtyArray'][$i] :(!in_array($data[0]['order_type_id'],$case_price_categories))? $data[0]['OriginalUnitPriceArray'][$i]* $data[0]['orderQtyArray'][$i]:$data[0]['OriginalCasePriceArray'][$i]* $data[0]['orderQtyArray'][$i],\App\Models\Order::ORDER_PERCISION) }}  <br/></td>
                        <td></td>
                    </tr>
                @endfor
                <tr style="">
                    <td colspan="2" style="text-align: left;padding:8px;border:1px dotted  #000;border-top:none;">Shipping Method: {{ $data[0]['freight_type'] }}</td>
                    <td colspan="1" style="padding:8px;border:1px dotted #000;border-left:1px solid #000;text-align: right">Order Total</td>
                    <td colspan="1" style="padding:8px;border:1px dotted #000;text-align: right">{{ \CurrencyHelpers::formatPrice($data[0]['order_total_cost'], \App\Models\Order::ORDER_PERCISION) }}</td>
                <td></td>
                </tr>
                @else
                    <td style="padding:8px;border:1px solid #000">Approximate<br/>Total Cost</td>
                <td></td>
                  </tr>
                    <tr>
                        <td style="padding:8px;border:1px dotted #000;border-top:none;">{{ $data[0]['order_description'] }}</td>
                        <td style="padding:8px;border:1px dotted #000;border-top:none;border-left:1px solid "> {{ \CurrencyHelpers::formatPrice($data[0]['order_total'],\App\Models\Order::ORDER_PERCISION) }}</td>
                        <td></td>
                    </tr>
                @endif

            </table>
        </div>
    </div>
</div>
<script>

</script>
