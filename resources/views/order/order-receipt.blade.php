@extends('layouts.app')
@section('content')

    <div class="sbox">
        <div class="sbox-title">
            <h4><i class="fa fa-table"></i> <?php echo $pageTitle;?>
                <small>{{ $pageNote }}</small>
            </h4>
        </div>

        <div class="sbox-content">

            {!! Form::open(array('url'=>'order/receiveorder/', 'class'=>'form-vertical','files' => true ,
            'parsley-validate'=>'','novalidate'=>' ','id'=> 'orderreceiveFormAjax')) !!}
            <div class="col-md-offset-1 col-md-10" style="padding-bottom:50px">
                <fieldset>
                    <legend>Order Receipt</legend>
                    <div class=" table-responsive col-md-8 col-md-offset-2" style="background-color:#FFF;border:1px solid lightgray;font-size:16px">
                        <table class="table">
                            <tr><td><b>PO #</b></td><td>{{ $data['po_number'] }}</td></tr>
                            <tr><td><b>Ordered By</b></td><td>{{ $data['order_user_name'] }}</td></tr>
                            <tr><td><b>Location </b></td><td>{{ $data['location_id'] }}</td></tr>
                            <tr><td><b>Vendor</b></td><td>{{ $data['vendor_name'] }}</td></tr>
                            <tr><td><b>Total Cost</b></td><td>{{ $data['order_total'] }}</td></tr>
                            <?php //if(!empty($item_count) && ($order_type == 7 || $order_type == 8) && () && $added_to_inventory == 0)  //REDEMPTION OR INSTANT WIN PRIZES -  SET TO DUMMY VALUE TO FORCE ORDER DESCRIPION UNTIL WE INTRODUCE PRIZE ALLOCATION
                            ?>
                            @if((isset($data['item_count']) && !empty($data['item_count'])) && ($data['order_type'] == 7 || $data['order_type'] == 8) &&   $data['added_to_inventory'] == 0)  //REDEMPTION OR INSTANT WIN PRIZES -  SET TO DUMMY VALUE TO FORCE ORDER DESCRIPION UNTIL WE INTRODUCE PRIZE ALLOCATION

                               <tr style="margin-top:10px;">
                                <td width="4%" style="border:thin black solid; padding:2px">IMG</td>
                                <td width="78%" style="border:thin black solid; padding:2px">Item Description</td>
                                <td width="5%" style="border:thin black solid; text-align:center; padding:2px">Case QTY</td>
                                <td width="13%" style="border:thin black solid; text-align:center; padding:2px">Apply Prizes</td>
                                </tr>

                            @for ($i=1; $i<=$data['item_count']; $i++)
                             <tr>
                                <td style="border:thin white dotted;">
                                    <?php
                                        $product_id="product_id_".$i;
                                    echo SiteHelpers::showUploadedFile($data[$product_id],'/uploads/products/', 40,false)
                                    ?>
                                </td>
                                <td style="border:thin white dotted; padding:2px">{{ $data['order_description_' . $i] }}</td>
                                <td style="border:thin white dotted; padding:2px; text-align:center;">{{  $data['order_qty_'.$i] }}</td>
                                <td style="border:thin white dotted; padding:2px; text-align:center;">
                                    <select name='game_'.$i id='game_'.$i>
                                    @foreach($game_options as $key=>$value)
                                        <option value="{{ $key }}"> {{ $value }} </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                                <input type="hidden" name='order_qty_'.$i value="{{ $data['order_qty_'.$i] }}" id='order_qty_'.$i/>
                                <input type="hidden" name='product_id_'.$i value="{{ $data['product_id_'.$i] }}" id='product_id_'.$i/>
                            @endfor

                            @else

                            <tr><td><b>Order Summary:</b></td><br> <td><?php echo  $data['description'] ?></td>
                                @endif
                        </table>
                    </div>
                    <div class="col-md-8 col-md-offset-2">
                        <div class="form-group  ">
                            <br/><br/>
                            <label for="date_received" class=" control-label col-md-4 text-left">
                                Date Received </label>
                            <div class="col-md-8">
                           <input type="text" class="date form-control" name="date_received" value="{{ date("m/d/Y", strtotime($data['today']))}}" required/>
                            </div>
                        </div>
                        <div class="form-group  ">
                            <br/><br/>
                            <label for="vendor_id" class=" control-label col-md-4 text-left">
                                Order Status
                            </label>
                            <div class="col-md-8">
                                <select name='order_status' rows='5' id='order_status_id' class='select3' required onchange="removeBorder('order_status_id')");></select>
                            </div>

                        </div>
                        <div class="form-group " id="tracking_numberdiv" style="display:none">
                            <br/><br/>
                            <label id ="tracking_number_lbl" for="vendor_id" class=" control-label col-md-4 text-left">
                                Tracking Number
                            </label>
                            <div class="col-md-8">
                                <input type="text"  class="form-control" name="tracking_number" id="tracking_number"/>
                            </div>

                        </div>
                         <div class="form-group  ">
                            <br/><br/>
                            <label for="vendor_id" class=" control-label col-md-4 text-left">
                               Notes </label>
                            <div class="col-md-8">
                            <textarea name="notes" rows="7" cols="48" id="notes" onchange="removeBorder('order_status')" required minlength=2></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name='item_count' value="{{ $data['item_count'] }}" id='item_count'/>
                    <input type="hidden" name='order_id' value="{{ $data['order_id'] }}" id='order_id'/>
                    <input type="hidden" name='order_type_id' value="{{ $data['order_type'] }}" id='order_type_id'/>
                    <input type="hidden" name='location_id' value="{{ $data['location_id'] }}" id='location_id'/>
                    <input type="hidden" name='user_id' value="{{ $data['user_id'] }}" id='user_id'/>
                    <input type="hidden" name='added_to_inventory' value="{{ $data['added_to_inventory'] }}" id='added_to_inventory'/>
                    </fieldset>
            </div>

            <hr/>
            <div class="clr clear"></div>




            <div class="table-responsive" style="padding-top: 5px;">
                <table class="table table-striped itemstable" onload="calculatetest()">
                    <thead>
                    <tr class="invHeading">
                        <th width="70"> Item #</th>
                        <th width="150">Item Name</th>
                        <th width="200">Item Description</th>
                        <th width="100">Price Per Unit</th>
                        <th width="100">Case Price</th>
                        <th>Quantity</th>
                        <th>Received Quantity</th>
                        <th>Total ( $ )</th>
                        <th></th>
                        <th></th>

                    </tr>

                    </thead>
                    <tbody>
                    @foreach($data['order_items'] as $order_item)
                    <tr id="rowid" class="clone clonedInput">
                        <td>{{ $order_item->id }}</td>
                        <td>{{ $order_item->item_name }}</td>
                        <td>{{ $order_item->product_description }}</td>
                        <td>{{ $order_item->price }}</td>
                        <td>{{ $order_item->case_price }}</td>
                        <td>{{ $order_item->qty }}</td>
                        <td>{{ $order_item->item_received }}</td>
                        <td>{{ $order_item->total }}</td>

                        <td><input type="text"  style="width:70px" class="yourText" disabled /><td>
                        <td><input type="checkbox" class="yourBox" /></td>
                    </tr>

                    @endforeach

                    </tbody>

                </table>
                <input type="hidden" name="enable-masterdetail" value="true">
            </div>

            <br/><br/>





            <hr/>


            <div style="clear:both"></div>

            <div class="form-group col-md-offset-3" style="margin-bottom:50px">
                <label class="col-sm-4 text-right">&nbsp;</label>
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary btn-sm " id="submit_btn"><i
                                class="fa  fa-save "></i>  Receive Order </button>
                    <button type="button" onclick="window.history.back();" class="btn btn-success btn-sm">
                        <i class="fa  fa-arrow-circle-left "></i>  Go Back </button>
                </div>
            </div>

            {!! Form::close() !!}




        </div>

</div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {

            $('.yourBox').change(function() {

                $('.yourText').attr('disabled',!this.checked)
            })






            $("#order_status_id").jCombo("{{ URL::to('order/comboselect?filter=order_status:id:status') }}",
                    {selected_value: '{{ $data["order_status_id"] }}',initial_text:'Select Order Status'});
            $('.previewImage').fancybox();
            $('.tips').tooltip();
            $(".select3").select2({width: "98%"});
            $('.date').datepicker({format: 'mm/dd/yyyy', autoClose: true})
            $('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
            $('.removeCurrentFiles').on('click', function () {
                var removeUrl = $(this).attr('href');
                $.get(removeUrl, function (response) {
                });
                $(this).parent('div').empty();
                return false;
            });
            var form = $('#orderreceiveFormAjax');
            form.parsley();
            form.submit(function () {

                if (form.parsley('isValid') == true) {
                    var options = {
                        dataType: 'json',
                        beforeSubmit: showRequest,
                        success: showResponse
                    }
                    $(this).ajaxSubmit(options);
                    return false;

                } else {
                    return false;
                }
            });

        });

        function showRequest() {
            $('.ajaxLoading').show();
        }
        function showResponse(data) {
            if (data.status == 'success') {
                ajaxViewClose('#{{ $pageModule }}');
                ajaxFilter('#{{ $pageModule }}', '{{ $pageUrl }}/data');
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
                var href="{{url()}}/order";
                window.location=href;
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }


        function removeBorder(type)
        {
            var selected = $("#"+type).val();
            if(selected)
            {
               $("#"+type).css("border","");
                if(selected == 5) /* Advanced Replacement Returned.. add tracking number */
                {
                   $("#tracking_numberdiv").show();
                    }
                else
                {
                    $("#tracking_numberdiv").hide();
                    }
            }
            else
            {
                $("#"+type).css("border" , "2px solid #F00")  ;
            }
        }

    </script>

@endsection