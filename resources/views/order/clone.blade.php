@extends('layouts.app')

@section('content')

<div class="sbox">
    <div class="sbox-title">
        <h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>

        </h4>
    </div>

        <div class="sbox-content">
            {!! Form::open(array('url'=>'order/save/',
            'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
            <div class="col-md-offset-2 col-md-8">
                <h1>Order Submit</h1>
                <div class="form-group  ">
                    <label for="Company Id" class=" control-label col-md-4 text-left"> Bill To:</label> <span>Family Entertainment Group, LLC</span>

                    <div class="col-md-6">
                        <input type="hidden" name="company_id" value="{{  $data['order_company_id'] }}"/>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                <div class="clearfix"></div><br/>
                <div class="form-group  ">
                    <label for="location" class=" control-label col-md-4 text-left"> Location </label>

                    <div class="col-md-6">
                      <select class="select3" id="location_id" name="location"></select>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                <div class="form-group  ">
                    <label for="alt_ship_to" class="control-label col-md-4 text-left"> Alt. Shipping Address </label>
                    <div class="col-md-6">
                        <input id="alt_ship_to" name="alt_ship_to"  type="checkbox" onchange="showAltShipTo()"/></div>
                    <div class="col-md-2">

                    </div>
                </div>
                {{-- Ship Address starts here  --}}
                <div id="ship_address" style="display:none">
                <div class="form-group  ">
                    <label for="alt_loc_name" class=" control-label col-md-4 text-left">
                        Name </label>
                        <div class="col-md-6">
                            <input type="text" name="to_add_name" id="to_add_name" class="form-control"/>
                        </div>
                        <div class="col-md-2">

                        </div>
            </div>
                <div class="form-group  ">
                    <label for="to_add_street" class=" control-label col-md-4 text-left">
                        Street Address </label>

                    <div class="col-md-6">
                        <input type="text" name="to_add_street" id="to_add_street" class="form-control"/></div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div class="form-group  ">
                    <label class=" control-label col-md-4 text-left">
                        City | State | Zip </label>

                    <div class="col-md-6">
                        <div class="col-md-6" style="margin:0px">
                        <input type="text" name="to_add_city" id="to_add_city" class="col-md-4 form-control"/>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="to_add_state" id="to_add_state" class="col-md-4 form-control"
                               maxlength="2"/>
                        </div>
                        <div class="col-md-3">
                        <input type="text" name="to_add_zip" id="to_add_zip" class="col-md-4 form-control"/>
                    </div>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div class="form-group  ">
                    <label for="to_add_notes" class=" control-label col-md-4 text-left">
                        Shipping Notes </label>

                    <div class="col-md-6">
                        <input type="text" name="to_add_notes" id="to_add_notes" class="form-control"/></div>
                    <div class="col-md-2">

                    </div>
                </div>
                    </div>
                {{--Ship Address Ends here--}}
                <div class="form-group  ">
                    <label for="order_type_id" class=" control-label col-md-4 text-left">
                        Order Type </label>

                    <div class="col-md-6">
                        <select name='order_type_id' rows='5' id='order_type_id' class='select2 ' required></select>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div class="form-group  ">
                    <label for="vendor_id" class=" control-label col-md-4 text-left">
                        Vendor </label>

                    <div class="col-md-6">
                        <select name='vendor_id' rows='5' id='vendor_id' class='select2 ' required></select>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div class="form-group  ">
                    <label for="freight_type_id" class=" control-label col-md-4 text-left">
                        Frieght Type </label>

                    <div class="col-md-6">
                        <select name='freight_type_id' rows='5' id='freight_type_id' class='select2 ' required></select>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div class="form-group " id="items_dev">
                    <label for="item_description" class=" control-label col-md-5 ">
                        Item Description / Item Number </label>   <label for="price" class="control-label col-md-3">  Price  </label>   <label for="qty" class=" control-label col-md-3 text-left"> Quantity </label>
<br/>
                    <div class="col-md-12" id="items_div">
                        <br/>
                        <div class="col-md-6">
                        <textarea name='item[0]' placeholder='Item #1 Description'
                                  class='form-control' cols="30" rows="4" maxlength="225" required></textarea>
                        </div>
                        <div class="col-md-3">
                            <br/>
                        <input type='number' name='price[0]' placeholder='Price' id="price"
                               class='form-control' min=".00000" step=".00001"
                               required onchange='calculateTotal();'>
                        </div>
                        <div class="col-md-3">
                            <br/>
                        <input type='number' name='qty[0]' placeholder='Quantity'
                               class='form-control' min="1" step="1" id="qty"
                               onchange='calculateTotal();' required>
                        </div>
                        <div id="gameDiv_0">
                            <input type='hidden' name='game_0' id='game_0' class='game'>
                        </div>
                        <input type='hidden' name='product_id[0]'>
                        <input type='hidden' name='request_id[0]'>
                    </div>

                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label class="col-sm-4 text-right">&nbsp;</label>

                    <div class="col-sm-8">
                        <button type="button" class="btn btn-success btn-sm " onClick="addInput();"> Add Item</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm" onClick="removeInput();"> Remove Item </button>
                    </div>
                </div>
                <div class="form-group  ">
                    <label for="date_orederd" class=" control-label col-md-4 text-left">
                        Date Orederd</label>

                    <div class="col-md-6">
                        <div class="input-group m-b" style="width:150px !important;">
                            <input type="text" class="form-control date" value=""/>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                    </div>
                    </div>
                    <div class="form-group  ">
                        <label for="total_cost" class=" control-label col-md-4 text-left">
                           Total Cost</label>
                        <div class="col-md-6">
                            <input style="width:150px !important;" type="text" name="total_cost" id="total_cost" class="form-control" value=""/>
                        </div>
                        <div class="col-md-2">
                        </div>
                        </div>
                        <div class="form-group  ">
                            <label for="po_number" class=" control-label col-md-4 text-left">
                                PO Number</label>
                            <div class="col-md-6">
                                <div class="col-md-4">
                                <input type="text" name="po_1" readonly id="po_1" class="form-control"/>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="po_2" readonly id="po_2" class="form-control"/>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="po_3" id="po_3" required class="form-control" value=""/>
                                </div>
                                </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label-control col-md-4" for="notes"> Order Notes **Will be on PO**</label>
                            <div class="col-md-6">
                                <textarea id="notes" name='notes' cols="50" rows="5" placeholder='Additional Notes'></textarea>
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                    <div style="clear:both"></div>
                    <input type="hidden" id="hidden_num_items" name="hidden_num_items">
                    <input type="hidden" id="form_type" name="form_type" value="">
                    <input type="hidden" id="where_in_expression" name="where_in_expression"
                           value="">
                    <input type="hidden" id="SID_string" name="SID_string" value="">
                    <input type="hidden" id="order_id" name="order_id" value="">
                    <input type="hidden" id="editmode" name="editmode" value="">

                    <div class="form-group">
                        <label class="col-sm-4 text-right">&nbsp;</label>

                        <div class="col-sm-8">
                            <button type="submit" class="btn btn-primary btn-sm "><i
                                        class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
                            <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')"
                                    class="btn btn-success btn-sm"><i
                                        class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }}
                            </button>
                        </div>
                    </div>




                    {!! Form::close() !!}
                </div><div class="clearfix"></div>
        </div>
</div>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#location_id").jCombo("{{ URL::to('order/comboselect?filter=location:id:location_name') }}",
                            {selected_value: '{{ $data["order_loc_id"] }}',
                                <?php $data["order_loc_id"] == '' ? '': print_r("ready:addInactiveItem('#location_id', ".$data['order_loc_id']." , 'Location', 'active' , 'location_name')") ?>
                            });

                    $("#vendor_id").jCombo("{{ URL::to('order/comboselect?filter=vendor:id:vendor_name') }}",
                            {selected_value: '{{ $data["order_vendor_id"] }}',
                                <?php $data["order_vendor_id"] == '' ? '': print_r("ready:addInactiveItem('#vendor_id', ".$data['order_vendor_id']." , 'Vendor', 'status' , 'vendor_name')") ?>
                            });

                    $("#freight_type_id").jCombo("{{ URL::to('order/comboselect?filter=freight:id:freight_type') }}",
                            {selected_value: '{{ $data['order_freight_id'] }}'});

                    $("#order_type_id").jCombo("{{ URL::to('order/comboselect?filter=order_type:id:order_type') }}",
                            {selected_value: '{{ $data["order_type"] }}'});


                    $('.editor').summernote();
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
                    var form = $('#orderFormAjax');
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
                    console.log("helllo");

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
                    } else {
                        notyMessageError(data.message);
                        $('.ajaxLoading').hide();
                        return false;
                    }
                }
                $('input').on('ifChecked', function ()
                {
                $("#ship_address").show('slow');
                }
                );
                $('input').on('ifUnchecked', function ()
                        {
                            $("#ship_address").hide('slow');
                        }
                );

                // ---- to add new Items ---//

                var counter = 1;
                var limit = 150;
                function addInput(){

                    if (counter == limit)  {
                        alert("You have reached the limit of adding " + counter + " inputs");
                    }

                    else {
                        var html='<div class="col-md-12" id="item_' + (counter + 1) + '"><br/>'+
                                '<div class="col-md-6">'+
                                '<textarea name=item['+ counter  +'] placeholder="Item #'+(counter + 1) +' Description"'+
                                'class="form-control" cols="30" rows="4" maxlength="225" required></textarea>'+
                                '</div>'+
                                '<div class="col-md-3">'+
                                '<br/>'+
                                '<input type="number" name="price['+ counter+']" placeholder="Price" id="price"'+
                                'class="form-control" min=".00000" step=".00001"'+
                                'required onchange="calculateTotal();">'+
                                '</div>'+
                                '<div class="col-md-3">'+
                                '<br/>'+
                                '<input type="number" name="qty['+counter+']" placeholder="Quantity"'+
                                'class="form-control" min="1" step="1" id="qty"'+
                                'onchange="calculateTotal();" required>'+
                                '</div>'+
                                '<div id="gameDiv_0">'+
                                '<input type="hidden" name="game_'+counter+'" id="game_'+counter+'" class="game">'+
                                '</div>'+
                                '<input type="hidden" name="product_id['+counter+']">'+
                        '<input type="hidden" name="request_id['+counter+']"></div></div>';
                        $('#items_div').append(html);
                        counter++;
                    }
                    $('#hidden_num_items').val(counter);
                }

                    function removeInput(){
                        $('div').remove("#item_"+counter);
                        if(counter > 1)
                        {
                            counter--;
                        }
                        $('#hidden_num_items').val(counter);
                    }

            </script>
    @endsection
