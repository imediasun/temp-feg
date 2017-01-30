@if($setting['form-method'] =='native')
    <div class="sbox">
        <div class="sbox-title">
            <h4>
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose1('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
            </h4>
        </div>
        <div class="sbox-content">
            @endif
            {!! Form::open(array('url'=>'order/save/', 'class'=>'form-vertical','files' => true ,
            'parsley-validate'=>'','novalidate'=>' ','id'=> 'ordersubmitFormAjax')) !!}
            <div class="col-md-6">
                <fieldset>
                    <legend>Order Info</legend>
                    <div class="form-group  ">
                        <label for="Company Id" class=" control-label col-md-4 text-left"> Bill To:</label>

                        <div class="col-md-8">
                            Family Entertainment Group, LLC
                            <input type="hidden" name="company_id" value="2" id="company_id"/>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group  ">
                        <label for="location_id" class=" control-label col-md-4 text-left"> Location </label>

                        <div class="col-md-8">
                            <select class="select3" id="location_id" name="location_id" required></select>
                        </div>

                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="alt_ship_to" class="control-label col-md-4 text-left"> Alt. Shipping
                            Address </label>

                        <div class="col-md-8">
                            <input id="alt_ship_to" name="alt_ship_to" type="checkbox"
                                   value="1" @if(!empty($data['alt_address'])) checked @endif/></div>

                    </div>
                    {{-- Ship Address starts here  --}}
                    <div id="ship_address" style="display:none">
                        <div class="form-group  ">
                            <br/><br/>
                            <label for="alt_loc_name" class=" control-label col-md-4 text-left">
                                Name </label>

                            <div class="col-md-8">
                                <input type="text" name="to_add_name" id="to_add_name" class="form-control"
                                       value="{{ $data['alt_name'] }}"/>
                            </div>
                        </div>
                        <div class="form-group  ">
                            <br/><br/>
                            <label for="to_add_street" class=" control-label col-md-4 text-left">
                                Street Address </label>

                            <div class="col-md-8">
                                <input type="text" name="to_add_street" id="to_add_street" class="form-control"
                                       value="{{ $data['alt_street']}}"/></div>
                        </div>
                        <div class="form-group  ">
                            <br/><br/>
                            <label class=" control-label col-md-4 text-left">
                                City | State | Zip </label>

                            <div class="col-md-8">
                                <input type="text" name="to_add_city" id="to_add_city" value="{{ $data['alt_city'] }}"
                                       class="form-control"
                                       style="width:40%;float:left;margin-left:3px"/>
                                <input type="text" name="to_add_state" id="to_add_state" class="form-control"
                                       value="{{ $data['alt_state']  }}"
                                       style="width:30%;float:left;margin-left:3px"/>

                                <input type="text" name="to_add_zip" id="to_add_zip" class="form-control"
                                       value="{{ $data['alt_zip'] }}" style="width:25%;float:left;margin-left:3px"/>

                            </div>
                        </div>
                        <div class="form-group  ">
                            <br/><br/>
                            <label for="to_add_notes" class=" control-label col-md-4 text-left">
                                Shipping Notes </label>

                            <div class="col-md-8">
                                <textarea name="to_add_notes" id="to_add_notes" rows="6" cols="50" class="form-control">
                                       {{ $data['shipping_notes'] }}
                            </textarea>
                            </div>

                        </div>
                    </div>
                    {{--Ship Address Ends here--}}
                </fieldset>
            </div>

            <div class="col-md-6">
                <fieldset>
                    <legend> Order Detail</legend>

                    <div class="form-group">
                        <label for="order_type_id" class=" control-label col-md-4 text-left">
                            Order Type </label>

                        <div class="col-md-8">
                            <select name='order_type_id' rows='5' id='order_type_id' class='select3'
                                    required></select>
                        </div>


                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="vendor_id" class=" control-label col-md-4 text-left">
                            Vendor </label>

                        <div class="col-md-8">
                            <select name='vendor_id' rows='5' id='vendor_id' class='select3 ' required></select>
                        </div>

                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="freight_type_id" class=" control-label col-md-4 text-left">
                            Freight Type </label>

                        <div class="col-md-8">
                            <select name='freight_type_id' rows='5' id='freight_type_id' class='select3 '
                                    required></select>
                        </div>

                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="date_orederd" class=" control-label col-md-4 text-left" style="margin-top: 7px;">
                            Date Ordered</label>

                        <div class="col-md-8" style="padding-left: 13px;">
                            <div class="input-group datepicker" style="width:150px !important; margin-bottom: 9px;">
                                <input type="text" class="form-control " id="my-datepicker" name="date_ordered"
                                       value="{{ date("m/d/Y", strtotime($data['today'])) }}" required="required"/>
                                <span class="input-group-addon "><i class="fa fa-calendar" id="icon"></i></span>
                            </div>
                        </div>

                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="total_cost" class=" control-label col-md-4 text-left">
                            Total Cost ( $ )</label>

                        <div class="col-md-8" style="padding-left: 18px !important;">
                            <input style="width:150px !important;" type="text" name="order_total" id="total_cost"
                                   class="form-control" value="{{ $data['order_total'] }}" maxlength="8"/>
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
                        </div>

                    </div>
                    <div class="form-group">
                        <br/><br/>
                        <label class="label-control col-md-4" for="notes"> Order Notes **Will be on PO**</label>

                        <div class="col-md-8">
                            <textarea id="notes" class="form-control " name='po_notes' cols="44" rows="9"
                                      placeholder='Additional Notes'>{{ $data['po_notes'] }}</textarea>
                        </div>
                    </div>

                    <div style="clear:both"></div>
                    <input type="hidden" id="hidden_num_items" name="hidden_num_items">
                    <input type="hidden" id="form_type" name="form_type" value="">
                    <input type="hidden" id="where_in_expression" name="where_in_expression"
                           value="{{$data['where_in_expression']}}">
                    <input type="hidden" id="SID_string" name="SID_string" @if(isset($data['SID_string'])))
                           value="{{$data['SID_string']}}" @else value="" @endif>
                    <input type="hidden" id="order_id" name="order_id" value="{{ $id }}">
                    <input type="hidden" id="editmode" name="editmode" value="{{ $data['prefill_type'] }}">
                </fieldset>
            </div>
            <hr/>
            <div class="clr clear"></div>
            <div style="visibility: hidden">
                <input type="button" value="hit me" id="experiment">

                <h5> Item Details </h5>
            </div>
            <div id="order-info-message"></div>
            <div class="table-responsive">
                <table class="table table-striped itemstable" onload="calculatetest()">
                    <thead>
                    <tr class="invHeading">
                        <th width="50"> Item #</th>
                        <th width="90"> Sku #</th>
                        <th width="170">Item Name</th>
                        <th width="200">Item Description</th>
                        <th width="90">Price Per Unit</th>
                        <th width="90">Case Price</th>
                        {{--<th>Retail Price</th>--}}
                        <th width="90">Quantity</th>
                        <th class="game" style="display:none" width="200">Game Located</th>
                        <th width="90">Total ( $ )</th>
                        <th width="60" align="center">Remove</th>


                    </tr>

                    </thead>

                    <tbody>
                    <tr id="rowid" class="clone clonedInput">
                        <td><br/><input type="text" id="item_num" name="item_num[]" disabled readonly
                                        style="width:30px;border:none;background:none"/></td>
                        <td><br/><input type="text" class="form-control sku" id="sku_num" name="sku[]"  readonly
                                    /></td>

                        <td><br/> <input type="text" name='item_name[]' placeholder='Item  Name' id="item_name"
                                         class='form-control item_name mysearch' onfocus="init(this.id,this)"
                                         maxlength="225" reuuired>
                        </td>
                        <td>
                            <textarea name='item[]' placeholder='Item  Description' id="item"
                                      class='form-control item' cols="30" rows="4" maxlength="225"></textarea>
                        </td>

                        <td><br/> <input type='number' name='price[]' placeholder='Unit Price' id="price"
                                         class='calculate form-control' min="0.000" step=".001" value="0.000"
                                         style="width: 85px"
                                         required></td>
                        <td>
                            <br/> <input type='number' name='case_price[]' placeholder='Case Price' id="case_price"
                                         class='calculate form-control' min="0.000" step=".001" value="0.000"
                                         style="width: 85px"
                                         required></td>
                        <td><br/> <input type='number' name='qty[]' placeholder='Quantity'

                                         class='calculate form-control qty' min="0" step="1" id="qty" value="00"
                                         required></td>
                        <td class="game" style="display:none">
                            <br/> <input type='hidden' name='game[]' id='game_0'>
                        </td>
                        <input type='hidden' name='product_id[]' id="product_id">
                        <input type='hidden' name='request_id[]' id="request_id">
                        <td><br/><input type="text" name="total" value="" readonly class="form-control"/></td>
                        <td align="center"><br/>
                            <button onclick=" $(this).parents('.clonedInput').remove(); calculateSum();decreaseCounter(); return false"
                                    class="remove btn btn-xs btn-danger">-
                            </button>
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


            <div style="
    padding-left: 865px;
    padding-right: 105px;
">

                {{--<td class="game"></td>--}}
                <td></td>
                <td colspan="6" class="text-left"><strong> Subtotal ( $ ) </strong></td>
                <td><input type="text" name="Subtotal"
                           value="{{number_format($data['order_total'],\App\Models\Order::ORDER_PERCISION) }}" readonly
                           class="form-control"/>

                    <div id="error-meesage-amount">

                    </div>
                </td>


            </div>

            <br>
            <hr>
            <div style="clear:both"></div>

            <div class="form-group" style="margin-bottom:50px">
                <label class="col-sm-4 text-right">&nbsp;</label>

                <div class="col-sm-8">
                    <button  type="submit" class="btn btn-primary btn-sm " id="submit_btn"><i
                                class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
                    <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm">
                        <i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <?php

    ?>
    </div>
    <script type="text/javascript">

        var minOrderAmount = 0.00;
        var PRECISION = '<?php echo  \App\Models\Order::ORDER_PERCISION?>';
        $('#alt_ship_to').on('change', function () {
                    hideShowAltLocation();
                }
        );
        function hideShowAltLocation() {
            if ($("#alt_ship_to").is(':checked'))
                $("#ship_address").show();
            else
                $("#ship_address").hide();
        }
        function calculateSum() {


            var Subtotal = 0.00;
            $('table tr.clone ').each(function (i) {
                Qty = $(this).find("input[name*='qty']").val();
                Price = $(this).find("input[name*='price']").val();
                if (Price == 0) {
                    Price = $(this).find("input[name*='case_price']").val();

                }
                sum = Qty * Price;
                Subtotal += sum;
                sum = sum.toFixed(PRECISION);
                $(this).find("input[name*='total']").val(sum);
            });

            Subtotal = Subtotal.toFixed(PRECISION);
            $("input[name='Subtotal']").val(Subtotal);
            $("#total_cost").val(Subtotal);
        }
        var games_options_js = "{{ json_encode($games_options) }}";
        //console.log(JSON.stringify(games_options_js));
        games_options_js=games_options_js.replace(/&amp;/g, '&');
        games_options_js=games_options_js.replace(/&#039;/g, "'");
        games_options_js=games_options_js.replace(/\\/g, "\\\\");
        games_options_js = $.parseJSON(games_options_js.replace(/&quot;/g, '"'));
        $(document).ready(function () {

            var inc = 1;
            hideShowAltLocation();
           var mode="{{ $data['prefill_type'] }}";
            if(mode != "edit") {
                $("#submit_btn").attr('disabled','disabled');
                checkPOValidity();
            }
            $("#item_num").val(inc);

            $('.test').val(0.00);

            $('#icon').click(function () {
                $(document).ready(function () {
                    $("#my-datepicker").datepicker().focus();
                });
            });

            $("#location_id").jCombo("{{ URL::to('order/comboselect?filter=location:id:id|location_name ') }}",
                    {
                        selected_value: '{{ $data["order_location_id"] }}',
                        initial_text: '-------- Select Location --------'
                    });

            $("#vendor_id").jCombo("{{ URL::to('order/comboselect?filter=vendor:id:vendor_name') }}",
                    {
                        selected_value: '{{ $data["order_vendor_id"] }}',
                        initial_text: '-------- Select Vendor --------'
                    });

            $("#freight_type_id").jCombo("{{ URL::to('order/comboselect?filter=freight:id:freight_type') }}",
                    {
                        selected_value: '{{ $data['order_freight_id'] }}',
                        initial_text: '-------- Select Freight Type --------'
                    });

            $("#order_type_id").jCombo("{{ URL::to('order/comboselect?filter=order_type:id:order_type') }}",
                    {selected_value: '{{ $data["order_type"] }}', initial_text: '-------- Select Order Type --------'});
            $("[id^=game_0]").select2({
                dataType: 'json',
                data: {results: games_options_js},
                placeholder: "For Various Games", width: "98%"
            });
            $('#vendor_id').on('change', function () {
                var vendor_id = $(this).val();
                if (vendor_id != '') {
                    $.ajax({
                        url: '{{url()}}/order/min-order-amount/' + vendor_id,
                        method: 'get',
                        dataType: 'json',
                        success: function (result) {
                            minOrderAmount = result.min_order_amount;
                            if (result.status == "success" && result.min_order_amount > 0) {
                                $('#order-info-message').css('color', 'green');
                                $("#order-info-message").text(result.message);
                            }
                            else {
                                $('#order-info-message').css('color', '#ffffff');
                                $("#order-info-message").text('');
                            }

                        }
                    });
                }
            });


            $("input[name*='total'] ").attr('readonly', '1');
            $(" input[name*='bulk_Price'] ").addClass('calculate');
            var ele = document.getElementsByClassName(".calculate");

            $(".calculate").keyup(function () {
                calculateSum();
            });
            $('.remove').click(function () {
                calculateSum();
            });
            $('.selectpicker').selectpicker();
            $('.addC').relCopy({});
            $('.editor').summernote();
            $('.previewImage').fancybox();
            $('.tips').tooltip();
            $("select.select3").select2({width: "98%"});
            $('.date').datepicker({format: 'mm/dd/yyyy', autoClose: true})
            $('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
            $('.removeCurrentFiles').on('click', function () {
                var removeUrl = $(this).attr('href');
                $.get(removeUrl, function (response) {
                });
                $(this).parent('div').empty();
                return false;
            });
            var form = $('#ordersubmitFormAjax');
            form.parsley();
            form.submit(function () {

                if (form.parsley('isValid') == true) {
                    var total_cost = $("#total_cost").val();
                    if (total_cost >= minOrderAmount) {
                        var options = {
                            dataType: 'json',
                            beforeSubmit: showRequest,
                            success: showResponse
                        }
                        $(this).ajaxSubmit(options);
                    }
                    else {
                        $('#error-meesage-amount').css('color', 'red');
                        $('#error-meesage-amount').show().delay(5000).fadeOut();
                        $("#error-meesage-amount").text('Sorry! you can not create a order request. Please recheck your order price.');
                    }
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
            var item_name_array =<?php echo json_encode($data['itemNameArray']) ?>;
            var sku_num_array =<?php echo json_encode($data['skuNumArray']) ?>;
            var game_ids_array =<?php echo json_encode($data['gameIdsArray']) ?>;
            var item_case_price =<?php echo json_encode($data['itemCasePrice']) ?>;
            var item_retail_price =<?php echo json_encode($data['itemRetailPrice'])?>;
            var item_total = 0;

            for (var i = 0; i < requests_item_count; i++) {

                $('input[name^=item_num]').eq(i).val(i + 1);
                $('textarea[name^=item]').eq(i).val(order_description_array[i]);
                $('input[name^=sku]').eq(i).val(sku_num_array[i]);
                if (order_price_array[i] == "" || order_price_array[i] == null) {
                    $('input[name^=price]').eq(i).val(0.00);
                }
                else {
                    $('input[name^=price]').eq(i).val(order_price_array[i]);

                }
                if (game_ids_array[i] == "" || game_ids_array[i] == null) {
                    $('input[name^=game]').eq(i).val("");
                }
                else {

                    $('input[name^=game]').eq(i).val(game_ids_array[i]);

                }
                if (order_qty_array[i] == "" || order_qty_array[i] == null) {
                    $('input[name^=qty]').eq(i).val(00);
                }
                else {
                    $('input[name^=qty]').eq(i).val(order_qty_array[i]);

                }


                if (order_product_id_array[i] == "" || order_product_id_array[i] == null) {
                    $('input[name^=product_id]').eq(i).val(0);
                }
                else {
                    $('input[name^=product_id]').eq(i).val(order_product_id_array[i]);

                }
                $('input[name^=request_id]').eq(i).val(order_request_id_array[i]);
                $('input[name^=item_name]').eq(i).val(item_name_array[i]);

                if (item_case_price[i] == "" || item_case_price[i] == null) {
                    $('input[name^=case_price]').eq(i).val(0.00);
                }
                else {
                    $('input[name^=case_price]').eq(i).val(item_case_price[i]);

                }
                if (item_retail_price[i] == "" || item_retail_price[i] == null) {

                    $('input[name^=retail_price]').eq(i).val(0.00);
                }
                else {
                    $('input[name^=retail_price]').eq(i).val(item_retail_price[i]);

                }
                if (i < requests_item_count - 1) //COMPENSATE FOR BEGINNING WITH ONE INPUT
                {

                    $("#add_new_item").trigger("click");
                }

            }
            $(".calculate").keyup(function () {
                calculateSum();
            });
            calculateSum();
            if (game_ids_array.length > 0) {
                $.ajax({
                    type:"GET",
                    url:"{{ url() }}/order/games-dropdown",
                    data:{ 'location':"<?php echo $data["order_location_id"] ?>" } ,
                    success: function(data){
                        $("[id^=game_]").select2({
                            dataType: 'json',
                            data: {results: data},
                            placeholder: "For Various Games", width: "98%"
                        });
                    }
                });

            }

        });


        function showRequest() {
            $('.ajaxLoading').show();
        }
        function showResponse(data) {

            if (data.status == 'success') {
                var url = location.href;
                location.href = "{{ url() }}/order/save-or-send-email";
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
            } else {
                if(data.message == "PO taken")
                {
                    data.message="PO is taken already please Try another PO.";
                    $("#po_message").html('<b style="color:red;margin:5px 0px">*PO# is taken, try another number..</b>');
                    $("#submit_btn").attr('disabled','disabled');
                }
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }
        var games_dropdown=[];
        $("#location_id").click(function () {
            $("#po_1").val($(this).val());
            validatePONumber();
            $.ajax({
                type:"GET",
                url:"{{ url() }}/order/games-dropdown",
                data:{'location':$(this).val()},
                success: function(data){
                    games_options_js=data;
                    $("[id^=game_]").select2({
                        dataType: 'json',
                        data: {results: data},
                        placeholder: "For Various Games", width: "98%"
                    });
                }
            });
        });

        $('#po_3').on("keyup", function () {
            checkPOValidity();
        });

        // -----------------for checking and validating PO number.... -----------------------//
        var poajax;
        function checkPOValidity()
        {
            if (poajax) {
                if (poajax.abort) {
                    poajax.abort();
                }
            }
            var $elm = $('#po_3');
            if (editmode && $elm.val().trim() == $elm.data('original')) {
                $("#po_message").html('');
                $("#submit_btn").removeAttr('disabled');
                return;
            }
            if ($elm.val().trim() === '') {
                $("#po_message").html('');
                $("#submit_btn").attr('disabled','disabled');
                return;
            }

            validatePONumber();
        }
        function validatePONumber() {
            var base_url =<?php echo  json_encode(url()) ?>;
            po_1 = $('#po_1').val().trim();
            po_2 = $('#po_2').val().trim();
            po_3 = $('#po_3').val().trim();
            if (po_3.length >= 1) {
                // $('.ajaxLoading').show();
            }
            else {
                //$('.ajaxLoading').hide();
            }
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
                    $('.ajaxLoading').hide();
                    poajax = null;
                    if (msg == 'taken') {
                        $("#po_message").html('<b style="color:red;margin:5px 0px">*PO# is taken, try another number..</b>');
                        $("#submit_btn").attr('disabled','disabled');
                    }
                    else {
                        $("#po_message").html('<b style="color:green;margin:5px 0px">*PO# is Available!</b>');
                        $("#submit_btn").removeAttr('disabled');
                    }

                }
            });
        }
        $('#order_type_id').change(function () {
            gameShowHide();
        });
        function gameShowHide() {

            /* var user_level =
            <?php //echo json_encode($user_level) ?>;
             if(user_level !== 'partner' && user_level !== 'partnerplus')
             {*/

            if ($('#order_type_id').val() == 1) // Parts Order
            {
                $('.game').show();
            }
            else {
                $('.game').hide();
            }
            //}
        }

        $("#add_new_item").click(function () {
            handleItemCount('add');
            $(".calculate").keyup(function () {
                calculateSum();
            });
          var location_id= $("#location_id").val();
            if(location_id != 0) {
                $.ajax({
                    type: "GET",
                    url: "{{ url() }}/order/games-dropdown",
                    data: {'location': location_id},
                    success: function (data) {
                        games_options_js = data;
                        $("[id^=game_]").select2({
                            dataType: 'json',
                            data: {results: data},
                            placeholder: "For Various Games", width: "98%"
                        });
                    }
                });

            }
            else {
                $("[id^=game_]").select2({
                    dataType: 'json',
                    data: {results: games_options_js},
                    placeholder: "For Various Games", width: "98%"
                });
            }
        });


        function decreaseCounter() {

            handleItemCount('remove');
        }
        function handleItemCount(mode) {
            $('input[name^=item_num]').each(function (index, value) {

                $(value).val(index + 1);
            });
            /*


             if(mode == "add")
             {
             counter = 1;
             }
             $('input[name^=item_num]').each(function () {
             if(mode == "add") {
             console.log(counter);
             counter = counter + 1;
             $('input[name^=item_num]').eq(counter-1).val(counter);

             }
             else if (mode == "remove")
             {
             if(counter >0)
             counter = counter-1;
             $('input[name^=item_num]').eq(counter-1).val(counter);

             console.log(counter);
             }

             });*/


            // init("item_name"+counter);
        }

    </script>
    <style type="text/css">
        tr.invHeading th {
            background: #d9d9d9 !important;
            pading-top: 10px !important;
            padding-bottom: 10px !important;
        }

        table.itemstable tbody tr:first-of-type td:last-of-type a {
            display: none;
        }

        #alt_ship_to {
            transition-property: all;
            transition-duration: .5s;
            transition-timing-function: ease-in;
        }
    </style>
    <script>
        function init(id, obj) {
            var cache = {},
                    lastXhr;

            var trid = $(obj).closest('tr').attr('id');
            var skuid = $("#" + trid + "  input[id^='sku_num']").attr('id');
            var priceid = $("#" + trid + "  input[id^='price']").attr('id');
            var casepriceid = $("#" + trid + "  input[id^='case_price']").attr('id');
            var qtyid = $("#" + trid + "  input[id^='qty']").attr('id');
            var itemid = $("#" + trid + "  textarea[name^=item]").attr('id');
            var retailpriceid = $('#' + trid + "  input[name^=retail]").attr('id');
            var selectorProductId = $('#' + trid + "  input[name^=product_id]").attr('id');


            $(obj).autocomplete({
                minLength: 2,
                source: function (request, response) {
                    var term = request.term;
                    lastXhr = $.getJSON("order/autocomplete", request, function (data, status, xhr) {
                        cache[term] = data;
                        if(data.value == "No Match")
                        {
                            $('[name^=item_name]:focus').closest('tr').find('.sku').removeAttr('readonly');
                        }
                        else
                        {
                           // $('[name^=item_name]:focus').closest('tr').find('.sku').attr('disabled',true);
                            $('[name^=item_name]:focus').closest('tr').find('.sku').attr('readonly',true);
                            $('[name^=item_name]:focus').closest('tr').find('.sku').val('');

                        }
                        if (xhr === lastXhr) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    $.ajax({
                        url: "order/productdata",
                        type: "get",
                        dataType: 'json',
                        data: {'product_id': ui.item.value},
                        success: function (result) {
                            if (result.unit_price == 0 && result.case_price == 0) {
                                notyMessageError("Retail Price and Case Price Unavailable...");
                                exit;
                            }
                            if (result.sku) {
                                $("#" + skuid).val(result.sku);
                            }
                            else {
                                $("#" + skuid).val('N/A');
                            }

                            if (result.unit_price) {
                                $("#" + priceid).val(result.unit_price);
                            }
                            else {
                                $("#" + priceid).val(0.00);
                            }
                            if (result.case_price) {
                                $("#" + casepriceid).val(result.case_price);
                            }
                            else {
                                $("#" + casepriceid).val(0.00);
                            }
                            if (result.retail_price) {
                                $("#" + retailpriceid).val(result.retail_price);
                            }
                            else {
                                $("#" + casepriceid).val(0.00);
                            }
                            $("#" + itemid).val(result.item_description);
                            $("#" + selectorProductId).val(result.id);
                            $("#" + qtyid).val(0.00);
                            calculateSum();
                        }
                    });
                }
            });
        }
        ;
        //init();
        $(function () {
            $("#experiment").trigger('click');
            $(".calculate").live("change", function () {
                calculateSum();
            });
        });
        function ajaxViewClose1(id) {

            location.href = "{{ url() }}/order";
        }
    </script>
    <style>
        .ui-corner-all {
            width: 25% !important;
        }

        [id^="s2id_game_0"]:first-of-type {
            display: none !important;
        }

        #s2id_game_0 {
            display: inline-block !important;
        }

        [id^="game_0"] {
            width: 90%;
        }
    </style>

    <script>

    </script>

