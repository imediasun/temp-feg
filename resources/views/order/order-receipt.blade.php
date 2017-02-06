@extends('layouts.app')
@section('content')
    <div class="page-content row">
    <div class="page-header">
        <div class="page-title">
            <h3> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small></h3>
        </div>
        <ul class="breadcrumb">
            <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
            <li class="active">{{ $pageTitle }}</li>
        </ul>
    </div>

    <div class="page-content-wrapper m-t">
    <div class="sbox">

        <div class="sbox-content">
<div class="ajaxLoading"></div>
            {!! Form::open(array('url'=>'order/receiveorder/', 'class'=>'form-vertical','files' => true ,
            'parsley-validate'=>'','novalidate'=>' ','id'=> 'orderreceiveFormAjax')) !!}
            <div class="col-sm-12 ">
                <fieldset>
                    <legend>Order Receipt</legend>
                    <div class=" table-responsive col-md-12 col-md-offset-2 item-receipt-container">
                        <table class="table">
                            <tr><td  style="border: none;" ><b>PO #</b></td><td  style="border: none;" >{{ $data['po_number'] }}</td></tr>
                            <tr><td><b>Ordered By</b></td><td>{{ $data['order_user_name'] }}</td></tr>
                            <tr><td><b>Location </b></td><td>{{ $data['location_id'] }}</td></tr>
                            <tr><td><b>Vendor</b></td><td>{{ $data['vendor_name'] }}</td></tr>
                            <tr><td><b>Description</b></td><td>{{ str_replace("<br>","" ,$data['description']) }}</td></tr>
                            <tr><td><b>Total Cost</b></td><td>{{ CurrencyHelpers::formatCurrency($data['order_total']) }}</td></tr>
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


                            @endif
                        </table>

                        <table id="itemTable" class="display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th> #</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price Unit</th>
                                <th>Case Price</th>
                                <th>Qty</th>
                                <th>Received Qty</th>
                                <th>Partially Received</th>
                                <th>Qty</th>
                                <th>Total ( $ )</th>

                            </tr>
                            </thead>

                            <tbody>
                           <?php  $value = 1   ?>
                            @foreach($data['order_items'] as $order_item)
                                @if($order_item->qty != $order_item->item_received)
                                    <tr>
                                        <td>
                                          {{ $value ++ }}
                                            <input type="hidden" name="itemsID[]" value="{{ $order_item->id }}">
                                        </td>
                                        <td>{{ $order_item->item_name }}</td>
                                        <td>{{ $order_item->product_description }}</td>
                                        <td>{{CurrencyHelpers::formatCurrency(number_format($order_item->price , \App\Models\Order::ORDER_PERCISION)) }}</td>
                                        <td> {{ CurrencyHelpers::formatCurrency( number_format( $order_item->case_price , \App\Models\Order::ORDER_PERCISION)) }}</td>

                                        <td>{{ $order_item->qty }}</td>
                                        <td>
                                            {{ $order_item->item_received }}
                                            <input type="hidden" name="receivedItemsQty[]" value="{{$order_item->item_received  }}">
                                        </td>

                                        <td style="text-align: center">
                                            <input type="checkbox" class="yourBox" name="receivedInParts[]" value="{{ $order_item->id }}" />
                                        </td>
                                        <td>
                                            <input type="text"  id="receivedItemText{{ $order_item->id }}" name="receivedQty[]" value="{{ $order_item->qty - $order_item->item_received}}" readonly="readonly" />
                                        </td>
                                      <td> {{CurrencyHelpers::formatCurrency( number_format($order_item->total,\App\Models\Order::ORDER_PERCISION)) }}
                                        </td>

                            </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="clearfix"></div>

                 <div class="col-md-8 col-md-offset-2" style="margin-left: 36.66666667% !important">
                        <div class="form-group  ">
                            <br/><br/>
                            <label for="date_received" class=" control-label col-md-4 text-right">
                                Date Received </label>
                            <div class="col-md-8">
                                <?php if(isset($data['date_received']) && ($data['date_received']!='0000-00-00'))
                                  $date_received = DateHelpers::formatDate($data['date_received']);
                                else
                                    $date_received=date('m/d/Y');
                                ?>
                                <input type="text" class="date form-control" name="date_received" value="{{ $date_received }}" required/>
                            </div>
                        </div>
                        <div class="form-group  ">
                            <br/><br/>
                            <label for="vendor_id" class=" control-label col-md-4 text-right">
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
                            <label for="vendor_id" class=" control-label col-md-4 text-right">
                                Notes </label>
                            <div class="col-md-8">
                                <textarea name="notes" rows="7" cols="48" id="notes" onchange="removeBorder('order_status')" ></textarea>
                            </div>
                        </div>
                        <div class="form-group" >
                            <input type="hidden" name='item_count' value="{{ $data['item_count'] }}" id='item_count'/>
                            <input type="hidden" name='order_id' value="{{ $data['order_id'] }}" id='order_id'/>
                            <input type="hidden" name='order_type_id' value="{{ $data['order_type'] }}" id='order_type_id'/>
                            <input type="hidden" name='location_id' value="{{ $data['location_id'] }}" id='location_id'/>
                            <input type="hidden" name='user_id' value="{{ $data['user_id'] }}" id='user_id'/>
                            <input type="hidden" name='added_to_inventory' value="{{ $data['added_to_inventory'] }}" id='added_to_inventory'/>
                            <label class="col-md-4 control-label text-right" >&nbsp</label>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary btn-sm " id="submit_btn"><i
                                            class="fa  fa-save "></i>  Receive Order </button>
                                <button type="button" onclick="window.history.back();" class="btn btn-success btn-sm">
                                    <i class="fa  fa-arrow-circle-left "></i>  Go Back </button>
                            </div>
                        </div>
                    </div>

                </fieldset>
            </div>

            <hr/>
            <div class="clr clear"></div>
           {!! Form::close() !!}
        </div>

    </div>
    </div>
    </div>
</div>
    <script type="text/javascript">
        $(document).ready(function () {

            var dTable =  $('#itemTable').DataTable({

            });


            $('#itemTable .yourBox').on('ifChecked',function(){
                var itemId= $(this).val();
                $('#receivedItemText'+itemId).removeAttr('readonly');
            });
            $('#itemTable .yourBox').on('ifUnchecked',function(){
                var itemId= $(this).val();
                $('#receivedItemText'+itemId).attr('readonly', 'readonly');
            });
            var isAdvaceReplacement=0;

            if("{{ $data['order_type'] }}" != 2) {
                $("#order_status_id").jCombo("{{ URL::to('order/comboselect?filter=order_status:id:status:order_type_id:1') }}",
                        {selected_value: '{{ $data["order_status_id"] }}'});
            }
            else
            {
                $("#order_status_id").jCombo("{{ URL::to('order/comboselect?filter=order_status:id:status:order_type_id:0') }}",
                        {selected_value: '{{ $data["order_status_id"] }}', initial_text: 'Select Order Status'});
            }


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