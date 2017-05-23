@extends('layouts.app')
@section('content')

    <div class="sbox">
        <div class="sbox-title">
            <h4><i class="fa fa-table"></i> <?php echo $pageTitle;?>
                <small>{{ $pageNote }}</small>
            </h4>
        </div>

        <div class="sbox-content">

            {!! Form::open(array('url'=>'order/save/', 'class'=>'form-vertical','files' => true ,
            'parsley-validate'=>'','novalidate'=>' ','id'=> 'ordersubmitFormAjax')) !!}
            <div class="col-md-6">
                <fieldset>
                    <legend>Order Info</legend>
                    <div class="form-group  ">
                        <label for="Company Id" class=" control-label col-md-4 text-left"> Bill To:</label> <span>Family Entertainment Group, LLC</span>

                        <div class="col-md-8">
                            <input type="hidden" name="company_id" value="{{  $data['order_company_id'] }}"/>
                        </div>

                    </div>
                    <div class="form-group  ">
                        <label for="location" class=" control-label col-md-4 text-left"> Location </label>

                        <div class="col-md-8">
                            <select class="select3" id="location_id" name="location_name"></select>
                        </div>

                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="alt_ship_to" class="control-label col-md-4 text-left"> Alt. Shipping
                            Address </label>

                        <div class="col-md-8">
                            <input id="alt_ship_to" name="alt_ship_to" type="checkbox" onchange="showAltShipTo()"/>
                        </div>

                    </div>
                    {{-- Ship Address starts here  --}}
                    <div id="ship_address" style="display:none">
                        <div class="form-group  ">
                            <br/><br/>
                            <label for="alt_loc_name" class=" control-label col-md-4 text-left">
                                Name </label>

                            <div class="col-md-8">
                                <input type="text" name="to_add_name" id="to_add_name" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group  ">
                            <br/><br/>
                            <label for="to_add_street" class=" control-label col-md-4 text-left">
                                Street Address </label>

                            <div class="col-md-8">
                                <input type="text" name="to_add_street" id="to_add_street" class="form-control"/></div>
                        </div>
                        <div class="form-group  ">
                            <br/><br/>
                            <label class=" control-label col-md-4 text-left">
                                City | State | Zip </label>

                            <div class="col-md-8">
                                <input type="text" name="to_add_city" id="to_add_city" value="" class="form-control"
                                       style="width:40%;float:left;margin-left:3px"/>
                                <input type="text" name="to_add_state" id="to_add_state" class="form-control" value=""
                                       style="width:30%;float:left;margin-left:3px"/>
                                <input type="text" name="to_add_zip" id="to_add_zip" required class="form-control"
                                       value="" style="width:25%;float:left;margin-left:3px"/>

                            </div>
                        </div>
                        <div class="form-group  ">
                            <br/><br/>
                            <label for="to_add_notes" class=" control-label col-md-4 text-left">
                                Shipping Notes </label>

                            <div class="col-md-8">
                                <input type="text" name="to_add_notes" id="to_add_notes" class="form-control"/></div>

                        </div>
                    </div>
                    {{--Ship Address Ends here--}}
                </fieldset>
            </div>

            <div class="col-md-6">
                <fieldset>
                    <legend> Order Detail</legend>

                    <div class="form-group  ">
                        <label for="order_type_id" class=" control-label col-md-4 text-left">
                            Order Type </label>

                        <div class="col-md-8">
                            <select name='order_type_id' rows='5' id='order_type_id' class='select2 ' required></select>
                        </div>

                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="vendor_id" class=" control-label col-md-4 text-left">
                            Vendor </label>

                        <div class="col-md-8">
                            <select name='vendor_id' rows='5' id='vendor_id' class='select2 ' required></select>
                        </div>

                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="freight_type_id" class=" control-label col-md-4 text-left">
                            Frieght Type </label>

                        <div class="col-md-8">
                            <select name='freight_type_id' rows='5' id='freight_type_id' class='select2 '
                                    required></select>
                        </div>

                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="date_orederd" class=" control-label col-md-4 text-left">
                            Date Orderd</label>

                        <div class="col-md-8">
                            <div class="input-group m-b" style="width:150px !important;">
                                <input type="text" class="form-control date" value="{{ date("m/d/Y", strtotime($data['today'])) }}"/>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>

                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="total_cost" class=" control-label col-md-4 text-left">
                            Total Cost</label>

                        <div class="col-md-8">
                            <input style="width:150px !important;" type="text" name="total_cost" id="total_cost"
                                   class="form-control" value="{{ $data['order_total'] }}"/>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="po_number" class=" control-label col-md-4 text-left">
                            PO Number</label>

                        <div class="col-md-8">

                            <input type="text" name="po_1" readonly id="po_1" value="{{ $data['order_loc_id'] }}"
                                   class="form-control" style="width:25%;float:left;margin-left:3px"/>
                            <input type="text" name="po_2" readonly id="po_2" class="form-control"
                                   value="{{  $data['po_2'] }}" style="width:35%;float:left;margin-left:3px"/>
                            <input type="text" name="po_3" id="po_3" required class="form-control"
                                   value="{{ $data['po_3'] }}" style="width:30%;float:left;margin-left:3px"/>

                            <br/>
                            <br/>

                            <div id="po_message"></div>
                            <br/>
                        </div>
                    </div>
                    <div class="form-group">
                        <br/><br/>
                        <label class="label-control col-md-4" for="notes"> Order Notes **Will be on PO**</label>

                        <div class="col-md-8">
                            <textarea id="notes" name='notes' cols="40" rows="5"
                                      placeholder='Additional Notes'>{{ $data['po_notes'] }}</textarea>
                        </div>
                    </div>

                    <div style="clear:both"></div>
                    <input type="hidden" id="hidden_num_items" name="hidden_num_items">
                    <input type="hidden" id="form_type" name="form_type" value="">
                    <input type="hidden" id="where_in_expression" name="where_in_expression"
                           value="">
                    <input type="hidden" id="SID_string" name="SID_string" value="">
                    <input type="hidden" id="order_id" name="order_id" value="">
                    <input type="hidden" id="editmode" name="editmode" value="">
                </fieldset>
            </div>
            <hr/>
            <div class="clr clear"></div>

            <h5> Item Details </h5>

            <div class="table-responsive">
                <table class="table table-striped itemstable">
                    <thead>
                    <tr class="invHeading">
                        <th>tem Descripton / Item Number</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th></th>
                        <th>Total</th>
                        <th>Remove Item</th>
                    </tr>

                    </thead>

                    <tbody>
                    <tr class="clone clonedInput">
                        <td> <textarea name='item[0]' placeholder='Item #1 Description'
                                       class='form-control' cols="30" rows="4" maxlength="225" required></textarea></td>
                        <td><br/> <input type='number' name='price[]' placeholder='Price' id="price"
                                         class='form-control' min=".00000" step=".00001"
                                         required></td>
                        <td><br/> <input type='number' name='qty[]' placeholder='Quantity'
                                         class='form-control' min="1" step="1" id="qty"
                                         required></td>
                        <td><br/> <select name="game_id[]" id="game_id" class="select3 game_id"/></td>
                        <td><br/><input type="text" name="total[]" value="" readonly class="form-control"/></td>
                        <td><br/> <a onclick=" $(this).parents('.clonedInput').remove(); calculateSum(); return false"
                                     href="#" class="remove btn btn-xs btn-danger">-</a>
                            <input type="hidden" name="counter[]">
                        </td>
                    </tr>
                    </tr>

                    </tbody>

                </table>
                <div style="padding-left:60px !important;">
                    <a href="javascript:void(0);" class="addC btn btn-xs btn-info" rel=".clone" id="add_new_item"><i
                                class="fa fa-plus"></i>
                        New Item</a></div>
                <input type="hidden" name="enable-masterdetail" value="true">
            </div>
            <br/><br/>


            <div style ="
    padding-left: 865px;
    padding-right: 105px;
">

                <td class="game"></td>
                <td> </td>
                <td colspan="6" class="text-left"><strong> Subtotal ( $ ) </strong></td>
                <td> <input type="text" name="Subtotal" value="{{ CurrencyHelpers::formatCurrency($data['order_total']) }}" readonly
                            class="form-control"/></td>



            </div>

            <br><hr>

            <div style="clear:both"></div>

            <div class="form-group" style="margin-bottom:50px">
                <label class="col-sm-4 text-right">&nbsp;</label>

                <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary btn-sm " id="submit_btn"><i
                                class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
                    <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm">
                        <i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    </div>

    </div>

    <script type="text/javascript">
        $('#alt_ship_to').on('ifChecked', function () {
                    $("#ship_address").css('display', 'block');

                }
        );
        $('input').on('ifUnchecked', function () {
                    $("#ship_address").css('display', 'none');
                }
        );
        function calculateSum() {

            var Subtotal = 0;
            $('table tr.clone ').each(function (i) {
                Qty = $(this).find(" input[name*='qty']").val();
                Price = $(this).find("input[name*='price']").val();
                sum = Qty * Price;
                Subtotal += sum;
                $(this).find("input[name*='total']").val(sum);
            });

            $("input[name='Subtotal']").val(Subtotal);
        }

        $(document).ready(function () {
            $("#submit_btn").hide();
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
            $("input[name*='total'] ").attr('readonly', '1');
            $("input[name*='qty'] , input[name*='bulk_Price'] ").addClass('calculate');

            calculateSum();
            $(".calculate").keyup(function () {
                calculateSum();
            })
            $('.remove').click(function () {
                calculateSum();
            })


            $('.addC').relCopy({});
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
            var form = $('#sbinoviceFormAjax');
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
            var requests_item_count = <?php echo json_encode($data['requests_item_count']) ?>;
            var order_description_array = <?php echo json_encode($data['orderDescriptionArray']) ?>;
            var order_price_array = <?php echo json_encode($data['orderPriceArray']) ?>;
            var order_qty_array = <?php echo json_encode($data['orderQtyArray']) ?>;
            var order_product_id_array = <?php echo json_encode($data['orderProductIdArray']) ?>;
            var order_request_id_array = <?php echo json_encode($data['orderRequestIdArray']) ?>;

            var item_total = 0;

            for (var i = 0; i < requests_item_count; i++) {

                $('textarea[name^=item]').eq(i).val(order_description_array[i]);
                $('input[name^=price]').eq(i).val(order_price_array[i]);
                $('input[name^=qty]').eq(i).val(order_qty_array[i]);
                $('input[name^=product_id]').eq(i).val(order_product_id_array[i]);
                $('input[name^=request_id]').eq(i).val(order_request_id_array[i]);

                if (i < requests_item_count - 1) //COMPENSATE FOR BEGINNING WITH ONE INPUT
                {

                    $("#add_new_item").trigger('click');
                }
            }
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
        $("#location_id").click(function(){
            $("#po_1").val($(this).val());
        });
        $('#po_3').on("keyup", function () {
            if (poajax) {
                if (poajax.abort) {
                    poajax.abort();
                }
            }

            $("#submit_btn").hide();
            var $elm = $('#po_3');

            if (editmode && $elm.val().trim() == $elm.data('original')) {
                $("#po_message").html('');
                $("#submit_btn").show();
                return;
            }
            if ($elm.val().trim() === '') {
                $("#po_message").html('');
                $("#submit_btn").hide();
                return;
            }

            validatePONumber();
        });
        // -----------------for checking and validating PO number.... -----------------------//
        var poajax;
        function validatePONumber() {
            var base_url = <?php echo  json_encode(url()) ?>;
            po_1 = $('#po_1').val().trim();
            po_2 = $('#po_2').val().trim();
            po_3 = $('#po_3').val().trim();
            if (poajax) {
                if (poajax.abort) {
                    poajax.abort();
                }
            }
            if (!po_1 || !po_2 || !po_3) {
                return false;
            }
            poajax = $.ajax({
                type: "POST",
                url: base_url + "/order/validateponumber",
                data: {
                    po_1: $('#po_1').val().trim(),
                    po_2: $('#po_2').val().trim(),
                    po_3: $('#po_3').val().trim()
                },
                success: function (msg) {

                    poajax = null;
                    if (msg == 'taken') {
                        $("#po_message").html('<b style="color:red">PO# is taken, try another number..</b>');
                        $("#submit_btn").fadeOut();
                    }
                    else {
                        $("#po_message").html('<b style="color:green">PO# is Available!</b>');
                        $("#submit_btn").fadeIn();
                    }

                }
            });


        }


    </script>
    <style type="text/css">
        tr.invHeading th {
            background: #d9d9d9 !important;
            pading-top: 10px !important;
            padding-bottom: 10px !important;
        }

        table.itemstable tbody tr:first-of-type td:last-of-type {
            display: none;
        }
    </style>
@endsection
