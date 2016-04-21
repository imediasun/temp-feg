<div style="font-family: helvetica;font-size:14px;">
    <p style="font-size: 36px"><img src="<?php echo public_path() . '/fegpo.png';?>" width="50"> {{ $main_title }} </p>

    <div style="position:relative">
        <div style="widtd:50%">
            <p>- BILL TO - <br/>
                {{ $data[0]['company_name_long'] }} <br/> 1000 Hart Road - Suite 375 <br/>Barrington, IL 6008
            </p>
        </div>
        <div style="width:50%; position:absolute; left:50%;top:8px">
            <table width="100%">
                <tr>
                    <td style="border:1px solid #000;text-align: center;padding:8px 0px;">Date:
                        <br/> {{ $data[0]['date_ordered'] }} </td>
                    <td style="border:1px solid #000;text-align: center;padding:8px 0px;">PO #
                        <br/>   {{ $data[0]['po_number'] }} </td>
                </tr>
            </table>
        </div>
        <div id="vendor">
            <h4>Vendor</h4>

            <p style="border:1px solid #000;border-right:0px;padding:2%;width:46%">
                {{ $data[0]['vendor_name'] }} <br/>
                {{ $data[0]['vend_street1'] }}<br/>
                {{ $data[0]['vend_city'] }} ,
                {{ $data[0]['vend_state'] }}
                {{ $data[0]['vend_zip'] }} <br/><br/>
                {{ $data[0]['vend_contact'] }}
                {{ $data[0]['vend_email'] }}
                <br/>
            </p>
        </div>
        <div style="position:relative;top:-187px;width:700px;left:50%;" id="shipto">
            <div style="position:absolute;;top:0px;color:red">
                <h4>Ship To</h4>

                <p style="border:1px solid #000;padding:2%;width:46%;min-height:350px">
                    {{ $data[0]['company_name_long'] }} <br/>
                    {{ $data[0]['po_location'] }} <br/>
                    {{ $data[0]['po_street1_ship'] }} ,<br/>
                    {{ $data[0]['po_city_ship'] }}
                    {{ $data[0]['po_state_ship'] }}
                    {{ $data[0]['po_zip_ship'] }} <br/>
                    {{ $data[0]['for_location'] }}
                    <br/>
                </p>
            </div>
        </div>
        <div style="width:100%;border:1px solid #000;padding:8px">
            <p> {{ $data[0]['po_notes'] }}</p>
        </div>
        <br/>

        <div>

            <table width="100%" border="1">
                <tr>
                    <td style="padding:8px;text-align: left"> Ordered By
                        : {{ $data[0]['first_name'] }} {{ $data[0]['last_name'] }} <br/>
                        Order Description
                    </td>
                    @if(($data[0]['new_format']==1))
                        <td style="padding:8px;">NO #</td>
                        <td style="padding:8px;">Unit Price</td>
                        <td style="padding:8px;">QTY</td>
                        <td style="padding:8px;">Item Total</td>
                </tr>
                @for($i=0;$i < count($data[0]['orderDescriptionArray']);$i++)
                    <tr>
                        <td style="padding:8px;">{{ $i+1 }}</td>
                        <td style="padding:8px;">  {{ $data[0]['orderDescriptionArray'][$i] }} <br/></td>
                        <td style="padding:8px;">  {{ $data[0]['orderPriceArray'][$i] }} <br/></td>
                        <td style="padding:8px;">  {{ $data[0]['orderQtyArray'][$i] }} <br/></td>
                        <td style="padding:8px;">   {{ $data[0]['orderPriceArray'][$i]* $data[0]['orderQtyArray'][$i] }} <br/></td>
                    </tr>
                @endfor
                <tr style="">
                    <td colspan="3" style="text-align: center;padding:8px">Shipping
                        Method: {{ $data[0]['freight_type'] }}</td>
                    <td colspan="2" style="padding:8px">Order Total:<br/>$  {{ number_format($data[0]['order_total_cost'],2) }}</td>
                </tr>
                @else
                    <td style="padding:8px;">Approximate<br/>Total Cost</td>
                    </tr>
                    <tr>
                        <td style="padding:8px;">{{ $data[0]['order_description'] }}</td>
                        <td style="padding:8px;"> $  {{ number_format($data[0]['order_total'],2) }}</td>
                    </tr>
                @endif

            </table>
        </div>


    </div>

</div>
<script>
    $(document).ready(function(){
        var height=$("#vendor").outerHeight();
        $("#shipto").css("height",height);
    });


</script>
