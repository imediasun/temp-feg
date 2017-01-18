<div style="font-family: helvetica;font-size:14px;">
    <p style="font-size: 36px"><img src="<?php echo public_path() . '/fegpo.png';?>" width="50"> {{ $main_title }} </p>

    <div style="position:relative">
        <div style="widtd:50%">
            <p>- BILL TO - <br/>
                {{ $data[0]['company_name_long'] }} <br/> 1000 Hart Road - Suite 375 <br/>Barrington, IL 6008
            </p>
        </div>
        <div style="width:50%; position:absolute; left:50%;top:8px">
            <table width="100%" style="border-collapse: collapse">
                <tr>
                    <td style="width:50%;border:1px solid #000;border-right:none;text-align: center;;padding:6px 0px;">Date:
                        <br/> {{ $data[0]['date_ordered'] }} </td>
                    <td style="width:50%;border:1px solid #000;text-align: center;padding:6px 0px;">PO #
                        <br/>   {{ $data[0]['po_number'] }} </td>
                </tr>
            </table>
        </div>
        <div id="vendor">
            <table width="100%" style="border-collapse: collapse">
                <tr><th width="50%" style="text-align: left">Vendor</th><th style="text-align: left;color:red" width="50%">Ship To</th></tr>
                <tr>
                    <td style="border:1px solid #000;border-right:none;padding:10px;">
                        {{ $data[0]['vendor_name'] }} <br/>
                        {{ $data[0]['vend_street1'] }}<br/>
                        {{ $data[0]['vend_city'] }} ,
                        {{ $data[0]['vend_state'] }}
                        {{ $data[0]['vend_zip'] }} <br/><br/>
                        {{ $data[0]['vend_contact'] }}
                        {{ $data[0]['vend_email'] }}
                        <br/>
                    </td>
                    <td style="border:1px solid #000;padding:10px;color:red">
                        {{ $data[0]['company_name_long'] }} <br/>
                        {{ $data[0]['po_location'] }} <br/>
                        {{ $data[0]['po_street1_ship'] }} ,<br/>
                        {{ $data[0]['po_city_ship'] }}
                        {{ $data[0]['po_state_ship'] }}
                        {{ isset($data[0]['po_zip_ship'])?isset($data[0]['po_zip_ship']):"" }} <br/>
                        {{ $data[0]['for_location'] }}
                         </td>
                </tr>
            </table>
        </div>
<br/>
        <div style="width:100%;border:1px solid #000;padding:8px; border-collapse:collapse;">
            <p> {{ $data[0]['po_notes'] }}</p>
        </div>
        <br/>

        <div>

            <table width="100%"  style="border-collapse: collapse;overflow:hidden;">
                <tr>
                    <td style="padding:8px;text-align: left;border:1px solid #000"> Ordered By
                        : {{ $data[0]['first_name'] }} {{ $data[0]['last_name'] }} <br/>
                        Order Description
                    </td>
                    @if(($data[0]['new_format']==1))
                        <td style="padding:8px;border:1px solid #000">NO #</td>
                        <td style="padding:8px;border:1px solid #000">Unit Price</td>
                        <td style="padding:8px;border:1px solid #000">QTY</td>
                        <td style="padding:9px;border:1px solid #000">Item Total</td>
                        <td></td>
                </tr>
                @for($i=0;$i < count($data[0]['orderDescriptionArray']);$i++)
                    <tr>
                        <td style="padding:8px;border:1px dotted #000;border-top:none">  {{ $data[0]['orderDescriptionArray'][$i] }} <br/></td>
                        <td style="padding:9px;border:1px dotted #000;border-top:none"> {{ $i+1 }}</td>
                        <td style="padding:8px;border:1px dotted #000;border-top:none">  {{CurrencyHelpers::formatCurrency($data[0]['orderPriceArray'][$i]) }} <br/></td>

                        <td style="padding:8px;border:1px dotted #000;border-top:none">  {{ $data[0]['orderQtyArray'][$i] }} <br/></td>
                        <td style="padding:9px;border:1px dotted #000;border-top:none">   {{ $data[0]['orderPriceArray'][$i]* $data[0]['orderQtyArray'][$i] }} <br/></td>
<td></td>
                    </tr>
                @endfor
                <tr style="">
                    <td colspan="3" style="text-align: left;padding:8px;border:1px dotted #000">Shipping
                        Method: {{ $data[0]['freight_type'] }}</td>
                    <td colspan="1" style="padding:8px;border:1px dotted #000;border-left:1px solid #000">Order Total</td>
                    <td colspan="1" style="padding:8px;border:1px dotted #000;">$  {{ number_format($data[0]['order_total_cost'],2) }}</td>
                <td></td>
                </tr>
                @else
                    <td style="padding:8px;border:1px solid #000">Approximate<br/>Total Cost</td>
                <td></td>
                  </tr>
                    <tr>
                        <td style="padding:8px;border:1px dotted #000;border-top:none">{{ $data[0]['order_description'] }}</td>
                        <td style="padding:8px;border:1px dotted #000;border-top:none"> $  {{ number_format($data[0]['order_total'],2) }}</td>
                        <td></td>
                    </tr>
                @endif

            </table>
        </div>
    </div>
</div>
<script>



</script>
