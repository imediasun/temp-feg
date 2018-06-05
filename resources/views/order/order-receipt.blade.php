@extends('layouts.app')
@section('content')
    <?php
        session_start();
        \Session::put('filter_before_redirect', 'redirect');
        \Session::put('searchParams', $_SESSION['searchParamsForOrder']);
    ?>
    <!-- some change in feature/Bug#97 -->
    <style>
        .dataTables_filter{
            float: right !important;
        }
        .dataTables_info{
            float: left !important;
        }
        .bootstrap-switch{
            /*float right;*/
            margin-top: 2px;
        }
        .info_table tr td:first-child {
            width: 110px;
        }
    </style>
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
            {!! Form::open(array('url'=>'order/receiveorder/', 'class'=>'form-vertical','files' => true ,
            'parsley-validate'=>'','novalidate'=>' ','id'=> 'orderreceiveFormAjax')) !!}
            <div class="col-sm-12 ">
               
                    <h3>Order Receipt</h3>
                    <div class=" table-responsive col-md-12 col-md-offset-2 item-receipt-container">

                        <table class="table info_table">
                            <tr><td  style="border: none;" ><b>PO #</b></td><td  style="border: none;" >{{ $data['po_number'] }}</td></tr>
                            <tr><td><b>Ordered By:</b></td><td>{{ $data['order_user_name'] }}</td></tr>
                            <tr><td><b>Location: </b></td><td>{{ $data['location_id'] ." |" }} {!!
                                    SiteHelpers::gridDisplayView($data['location_id'],'location_id','1:location:id:location_name')
                                    !!}</td></tr>
                            <tr><td><b>Vendor:</b></td><td>{{ $data['vendor_name'] }}</td></tr>
                            <tr><td><b>Description:</b></td><td style="white-space: inherit;">{{ str_replace("<br>","" ,$data['description']) }}</td></tr>
                            <tr><td><b>Total Cost:</b></td><td>{{ CurrencyHelpers::formatPrice($data['order_total'],\App\Models\Order::ORDER_PERCISION ) }}</td></tr>
                            <tr><td><b>Edit Receipt:</b></td> <td> <!--<button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#editItemsPan" style="float: right;margin-top: 19px;" id="edit_receipt_btn"><i class="fa fa-edit"></i> Edit Receipt</button>-->
                                   <input type='checkbox' name="toggle_trigger" data-handle-width="100px" data-size="mini" data-on-text="Active" data-off-text="Inactive" id="toggle_trigger" onSwitchChange="trigger()" /> </td></tr>
                            <?php //if(!empty($item_count) && ($order_type == 7 || $order_type == 8) && () && $added_to_inventory == 0)  //REDEMPTION OR INSTANT WIN PRIZES -  SET TO DUMMY VALUE TO FORCE ORDER DESCRIPION UNTIL WE INTRODUCE PRIZE ALLOCATION
                            ?>
                            @if((isset($data['item_count']) && !empty($data['item_count'])) && ($data['order_type'] == 7 || $data['order_type'] == 8) &&   $data['added_to_inventory'] == 0)  <!--//REDEMPTION OR INSTANT WIN PRIZES -  SET TO DUMMY VALUE TO FORCE ORDER DESCRIPION UNTIL WE INTRODUCE PRIZE ALLOCATION-->

                            <tr style="margin-top:10px;display: none;">
                                <td width="4%" style="border:thin black solid; padding:2px">IMG</td>
                                <td width="78%" style="border:thin black solid; padding:2px">Item Description</td>
                                <td width="5%" style="border:thin black solid; text-align:center; padding:2px">Case QTY</td>
                                <td width="13%" style="border:thin black solid; text-align:center; padding:2px">Apply Prizes</td>
                            </tr>

                            @for ($i=1; $i<=$data['item_count']; $i++)
                                <tr style="display: none;">
                                    <td style="border:thin white dotted;">
                                        <?php
                                        $product_id="product_id_".$i;
                                        echo SiteHelpers::showUploadedFile($data[$product_id],'/uploads/products/', 40,false)
                                        ?>
                                    </td>
                                    <td style="border:thin white dotted; padding:2px">{{ $data['order_description_' . $i] }}</td>
                                    <td style="border:thin white dotted; padding:2px; text-align:center;">{{  $data['order_qty_'.$i] }}</td>
                                    <td style="border:thin white dotted; padding:2px; text-align:center;">
                                        <?php if(!empty($game_options)):?>
                                        <select name='game_{{$i}}' id='game_{{$i}}'>
                                            @foreach($game_options as $key=>$value)
                                                <option value="{{ $key }}"> {{ $value }} </option>
                                            @endforeach
                                        </select>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <input type="hidden" name='order_qty_{{$i}}' value="{{ $data['order_qty_'.$i] }}" id='order_qty_{{$i}}'/>
                                <input type="hidden" name='product_id_{{$i}}' value="{{ $data['product_id_'.$i] }}" id='product_id_{{$i}}'/>
                            @endfor

                            @else


                            @endif
                        </table>

                        <div class="collapse" id="editItemsPan">
                            <b><h3>Edit Receipt:</h3></b>
                            <table id="editItemTable" class="display table" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>No#</th>
                                    <th>Item Name</th>
                                    <th>Item Description</th>
                                    @if($data['order_type'] == \App\Models\order::ORDER_TYPE_PART_GAMES)<th>Game</th>@endif
                                    <th>Unit Price</th>
                                    <th>Case Price</th>
                                    <th>Qty</th>
                                    <th>Received Qty</th>
                                    <!--<th>Update Qty</th>-->
                                    <th>Edit Received Qty</th>
                                    <th>Total($)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  $value = 1   ?>
                                @foreach($data['order_items'] as $order_item)
                                    @if($order_item->item_received > 0)
                                        <tr>
                                            <td style="text-align: center">
                                                {{ $value ++ }}
                                                <input type="hidden" name="orderLineItemId[]" value="{{ $order_item->id }}">
                                                <input type="hidden" name="updateItemNotes[]" value="{{ $order_item->notes }}">
                                            </td>
                                            <td>{{ $order_item->item_name }}</td>
                                            <td>{{ $order_item->product_description }}</td>
                                            @if($data['order_type'] == \App\Models\order::ORDER_TYPE_PART_GAMES)
                                                <td> {{ $order_item->game_name }}</td>
                                            @endif
                                            <td>{{CurrencyHelpers::formatPrice($order_item->price , \App\Models\Order::ORDER_PERCISION) }}</td>
                                            <td> {{ CurrencyHelpers::formatPrice($order_item->case_price , \App\Models\Order::ORDER_PERCISION) }}</td>

                                            <td>{{ $order_item->qty }}
                                                <input type="hidden" name="updateOrigQty[]" value="{{$order_item->qty}}">
                                            </td>
                                            <td>
                                                {{ $order_item->item_received }}
                                                <input type="hidden" name="updateAlreadyReceivedQty[]" value="{{$order_item->item_received}}">
                                            </td>


                                            <!--<td style="text-align: center">
                                                <input type="checkbox" class="updateBox" name="updateProducts[]" value="{{ $order_item->id }}" />
                                            </td>-->
                                            <td>
                                                <input type="number" class="updateQtyInput parsley-validated" id="updateItemText{{ $order_item->id }}" name="updateQty[]" value="{{$order_item->item_received}}" max="{!! $order_item->qty !!}" min="0" />
                                            </td>
                                            <td> {{CurrencyHelpers::formatPrice($order_item->total,\App\Models\Order::ORDER_PERCISION) }}
                                            </td>

                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                            <br><hr><br>
                        </div>


                        <div class="collapse in" id="receiveItemsPan">
                            <b><h3>Receive Items:</h3></b>
                            <table id="itemTable" class="display table" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                            <th>No#</th>
                                <th>Item Name</th>
                                <th>Item Description</th>
                                @if($data['order_type'] == \App\Models\order::ORDER_TYPE_PART_GAMES)<th>Game</th>@endif
                                <th>Unit Price</th>
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
                                        <td style="text-align: center">
                                          {{ $value ++ }}
                                            <input type="hidden" name="itemsID[]" value="{{ $order_item->id }}">
                                        </td>
                                        <td>{{ $order_item->item_name }}</td>
                                        <td>{{ $order_item->product_description }}</td>
                                        @if($data['order_type'] == \App\Models\order::ORDER_TYPE_PART_GAMES)
                                        <td> {{ $order_item->game_name }}</td>
                                        @endif
                                        <td>{{CurrencyHelpers::formatPrice( $order_item->price , \App\Models\Order::ORDER_PERCISION) }}</td>
                                        <td> {{ CurrencyHelpers::formatPrice( $order_item->case_price , \App\Models\Order::ORDER_PERCISION) }}</td>

                                        <td>{{ $order_item->qty }}</td>
                                        <td>
                                            {{ $order_item->item_received }}
                                            <input type="hidden" name="receivedItemsQty[]" value="{{$order_item->item_received  }}">
                                        </td>

                                        <td style="text-align: center">
                                            <input type="checkbox" class="yourBox" name="receivedInParts[]" value="{{ $order_item->id }}" />
                                        </td>
                                        <td>
                                            <input type="number"  id="receivedItemText{{ $order_item->id }}" name="receivedQty[]" value="{{ $order_item->qty - $order_item->item_received}}" max="{!! $order_item->qty - $order_item->item_received !!}" min="0" readonly="readonly" />
                                        </td>
                                      <td> {{CurrencyHelpers::formatPrice( $order_item->total,\App\Models\Order::ORDER_PERCISION) }}
                                        </td>

                            </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        </div>

                    </div>

                    <div class="clearfix"></div>
                <div class="row">
                 <div class="col-md-8 col-md-offset-4 col-xs-12">
                    <div class="row">
                        <div class="form-group clearfix">

                            <label for="date_received" class=" control-label col-md-4 text-right">
                                Date Received: </label>
                            <div class="col-md-8">
                                <?php if(isset($data['date_received']) && ($data['date_received']!='0000-00-00'))
                                  $date_received = DateHelpers::formatDate($data['date_received']);
                                else
                                    $date_received=date('m/d/Y');
                                ?>
                                <input type="text" class="date form-control" name="date_received" value="{{ $date_received }}" required/>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            
                            <label for="vendor_id" class=" control-label col-md-4 text-right">
                                Order Status:
                            </label>
                            <div class="col-md-8">
                                <select name='order_status' rows='5' id='order_status_id' class='select3' required onchange="removeBorder('order_status_id')");></select>
                            </div>

                        </div>

                        @if($data['order_type']=='2')
                            <div class="form-group clearfix">
                                <label for="date_received" class=" control-label col-md-4 text-right">
                                    Tracking Number: </label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="tracking_number" id="tracking_number" required>{{ $data['tracking_number'] }}</textarea>
                                </div>
                            </div>
                            @else
                            <input type="hidden" name='tracking_number' value="{{ $data['tracking_number'] }}" id='tracking_number'/>
                        @endif

                        <!--<div class="form-group clearfix" id="tracking_numberdiv" style="displaynone">
                            
                            <label id ="tracking_number_lbl" for="vendor_id" class=" control-label col-md-4 text-left">
                                Tracking Number:
                            </label>
                            <div class="col-md-8">
                                <input type="text"  class="form-control" name="tracking_number" id="tracking_number"/>
                            </div>

                        </div>-->
                        <div class="form-group clearfix">
                            
                            <label for="vendor_id" class=" control-label col-md-4 text-right">
                                Notes: </label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="notes" rows="7" cols="48" id="notes" ></textarea>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <input type="hidden" name='item_count' value="{{ $data['item_count'] }}" id='item_count'/>
                            <input type="hidden" name='order_id' value="{{ $data['order_id'] }}" id='order_id'/>
                            <input type="hidden" name='order_type_id' value="{{ $data['order_type'] }}" id='order_type_id'/>
                            <input type="hidden" name='location_id' value="{{ $data['location_id'] }}" id='location_id'/>
                            <input type="hidden" name='user_id' value="{{ $data['user_id'] }}" id='user_id'/>
                            <input type="hidden" name='added_to_inventory' value="{{ $data['added_to_inventory'] }}" id='added_to_inventory'/>
                            <input type="hidden" name='order_state_id' value="{{ $data['status_id'] }}" id='added_to_inventory'/>
                            <input type="hidden" name='mode' value="receive" id='mode'/>
                            <label class="col-md-4 control-label text-right" >&nbsp</label>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary btn-sm " id="submit_btn"><i
                                            class="fa  fa-save "></i>  Receive Order </button>
                                <!--<button type="submit" class="btn btn-primary btn-sm " id="update_receipt_btn"><i
                                            class="fa fa-refresh"></i>  Update Receipt </button>-->
                                <button type="button" onclick="window.history.back();" class="btn btn-success btn-sm">
                                    <i class="fa  fa-arrow-circle-left "></i>  Go Back </button>
                            </div>
                        </div>
                     </div>
                    </div>
                </div>
               
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
            $('#update_receipt_btn').toggle();
            console.log("Search params: "+"{{\Session::get('searchParams')}}");

            numberFieldValidationChecks($("input[type='number']"));

            var dTable =  $('#itemTable').DataTable({
                paging: true,
                "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ]
            });

            $('#editItemTable').DataTable({
                paging: true,
                "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ]
            });

            $('#itemTable').on( 'page.dt', function () {
                setTimeout(function(){ regIcheckEvent(); }, 500);
            });

            $('#editItemTable').on( 'page.dt', function () {
                setTimeout(function(){ regIcheckEventForEdit(); }, 500);
            });

            $('#itemTable .yourBox').on('ifChecked',function(){
                var itemId= $(this).val();
                $('#receivedItemText'+itemId).removeAttr('readonly');
            });
            $('#itemTable .yourBox').on('ifUnchecked',function(){
                var itemId= $(this).val();
                $('#receivedItemText'+itemId).attr('readonly', 'readonly');
                $('#receivedItemText'+itemId).val($('#receivedItemText'+itemId).attr('max'));
            });

            $('#editItemTable .updateBox').on('ifChecked',function(){
                var itemId= $(this).val();
                $('#updateItemText'+itemId).removeAttr('readonly');
                $('#updateItemText'+itemId).css('background-color', '#fff');
            });
            $('#editItemTable .updateBox').on('ifUnchecked',function(){
                var itemId= $(this).val();
                $('#updateItemText'+itemId).attr('readonly', 'readonly');
                $('#updateItemText'+itemId).css('background-color', '#c1c1c1');
                $('#updateItemText'+itemId).val(0);
                $('#orderreceiveFormAjax').parsley( 'validate' );
            });
            var isAdvaceReplacement=0;

            $("#order_status_id").jCombo("{{ URL::to('order/comboselect?filter=order_status:id:status:order_type_id:1') }}",
                    {selected_value: '{{ $data["order_status_id"] }}'});




            $('.previewImage').fancybox();
            $('.tips').tooltip();
            renderDropdown($(".select3 "), { width:"100%"});
            $('.date').datepicker({format: 'mm/dd/yyyy', autoclose: true})
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
                    $('#itemTable').DataTable().destroy();
                    $('#editItemTable').DataTable().destroy();
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

        function regIcheckEvent() {
            $('#itemTable .yourBox').unbind('ifChecked');
            $('#itemTable .yourBox').unbind('ifUnchecked');

            $('#itemTable .yourBox').on('ifChecked', function(){
                console.log('test me');
                var itemId= $(this).val();
                $('#receivedItemText'+itemId).removeAttr('readonly');
            });
            $('#itemTable .yourBox').on('ifUnchecked', function(){
                var itemId= $(this).val();
                $('#receivedItemText'+itemId).attr('readonly', 'readonly');
                $('#receivedItemText'+itemId).val($('#receivedItemText'+itemId).attr('max'));
            });
        }

        function regIcheckEventForEdit() {
            $('#editItemTable .updateBox').unbind('ifChecked');
            $('#editItemTable .updateBox').unbind('ifUnchecked');

            $('#editItemTable .updateBox').on('ifChecked', function(){
                var itemId= $(this).val();
                $('#updateItemText'+itemId).removeAttr('readonly');
                $('#updateItemText'+itemId).css('background-color', '#fff');
            });
            $('#editItemTable .updateBox').on('ifUnchecked', function(){
                var itemId= $(this).val();
                $('#updateItemText'+itemId).attr('readonly', 'readonly');
                $('#updateItemText'+itemId).css('background-color', '#c1c1c1');
                $('#updateItemText'+itemId).val(0);
                $('#orderreceiveFormAjax').parsley( 'validate' );
            });
        }

        function showRequest() {
            $('.ajaxLoading').show();
        }
        function showResponse(data) {
            if (data.status == 'success') {
                ajaxViewClose('#{{ $pageModule }}');
                //ajaxFilter('#{{ $pageModule }}', '{{ $pageUrl }}/data');
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
                if($('#mode').val()=='receive' || true){
                    var href="{{url()}}/order";
                    window.location=href;
                }else{
                    window.location = window.location.href;
                }

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
                if(selected == 2) /* Advanced Replacement Returned.. add tracking number */
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


        $('#order_status_id').on('click',function(){
            $('#orderreceiveFormAjax').parsley('destroy');
            if($(this).val()==2){
                $('#tracking_number').attr('required', 'required');
            }else {
                $('#tracking_number').removeAttr('required');
            }
            $('#orderreceiveFormAjax').parsley();
        });


        $('.yourBox').on('ifChanged',function(){
            $('#orderreceiveFormAjax').parsley('destroy');
            if(checkboxCount()>0){
                $('#tracking_number').removeAttr('required');
            }else{
                $('#tracking_number').attr('required', 'required');
            }
            $('#orderreceiveFormAjax').parsley();
        });

        function checkboxCount() {
            var count = 0;
            $('.yourBox:checked').each(function(){
                count++;
            });
            return count;
        }

        $('input[name^="receivedQty"]').change(function () {
            $('#orderreceiveFormAjax').parsley( 'validate' );
        });

        $('input[name^="updateQty"]').change(function () {
            $('#orderreceiveFormAjax').parsley( 'validate' );
        });

        $('#edit_receipt_btn').on('click', function () {
            $('#update_receipt_btn').toggle();
            $('#submit_btn').toggle();
            if($('#update_receipt_btn').is(":visible")){
                $('#mode').val('update');
                $('#receiveItemsPan').collapse('hide');
                $('#edit_receipt_btn').html('<i class="fa fa-truck"></i> Inactive')
            }else{
                $('#mode').val('receive');
                $('#receiveItemsPan').collapse('show');
                $('#edit_receipt_btn').html('<i class="fa fa-edit"></i> Active')
            }
        });

        $(document).ready(function () {

            $('#toggle_trigger').iCheck('destroy');
            $("#toggle_trigger").bootstrapSwitch();
            $("#toggle_trigger").on('switchChange.bootstrapSwitch', function(event, state) {
                //$('#update_receipt_btn').toggle();
                //$('#submit_btn').toggle();
                $('#editItemsPan').toggle();
                $('#receiveItemsPan').toggle();
                if($('#toggle_trigger').is(":checked")){
                    $('#mode').val('update');
                    //$('#receiveItemsPan').collapse('hide');
                }else{
                    $('#mode').val('receive');
                    //$('#receiveItemsPan').collapse('show');
                }
            });

        });

    </script>

@endsection
