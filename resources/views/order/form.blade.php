@if($setting['form-method'] =='native')
    <div class="sbox">
        <div class="sbox-title">
            <h4><i class="fa fa-table"></i> <?php echo $pageTitle;?>
                <small>{{ $pageNote }}</small>
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
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
                                <
                                <input type="text" name="to_add_zip" id="to_add_zip" class="form-control"
                                       value="{{ $data['alt_zip'] }}" style="width:25%;float:left;margin-left:3px"/>

                            </div>
                        </div>
                        <div class="form-group  ">
                            <br/><br/>
                            <label for="to_add_notes" class=" control-label col-md-4 text-left">
                                Shipping Notes </label>

                            <div class="col-md-8">
                                <textarea  name="to_add_notes" id="to_add_notes" rows="6" cols="50" class="form-control">
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

                    <div class="form-group  ">
                        <label for="order_type_id" class=" control-label col-md-4 text-left">
                            Order Type </label>

                        <div class="col-md-8">
                            <select name='order_type_id' rows='5' id='order_type_id' class='select3'
                                    onchange="gameShowHide()" required></select>
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
                            Frieght Type </label>

                        <div class="col-md-8">
                            <select name='freight_type_id' rows='5' id='freight_type_id' class='select3 '
                                    required></select>
                        </div>

                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="date_orederd" class=" control-label col-md-4 text-left">
                            Date Orederd</label>

                        <div class="col-md-8">
                            <div class="input-group m-b" style="width:150px !important;">
                                <input type="text" class="form-control date" name="date_ordered"
                                       value="{{ $data['today'] }}" required="required"/>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>

                    </div>
                    <div class="form-group  ">
                        <br/><br/>
                        <label for="total_cost" class=" control-label col-md-4 text-left">
                            Total Cost ( $ )</label>

                        <div class="col-md-8">
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
                        <label class="label-control col-md-3" for="notes"> Order Notes **Will be on PO**</label>

                        <div class="col-md-9">
                            <textarea id="notes" name='po_notes' cols="50" rows="9"
                                      placeholder='Additional Notes'>{{ $data['po_notes'] }}</textarea>
                        </div>
                    </div>

                    <div style="clear:both"></div>
                    <input type="hidden" id="hidden_num_items" name="hidden_num_items">
                    <input type="hidden" id="form_type" name="form_type" value="">
                    <input type="hidden" id="where_in_expression" name="where_in_expression"
                           value="">
                    <input type="hidden" id="SID_string" name="SID_string" value="">
                    <input type="hidden" id="order_id" name="order_id" value="{{ $id }}">
                    <input type="hidden" id="editmode" name="editmode" value="{{ $data['prefill_type'] }}">
                </fieldset>
            </div>
            <hr/>
            <div class="clr clear"></div>
            <div style="visibility: hidden">
            <input type="button" value="hit me" id="experiment" >

            <h5> Item Details </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-striped itemstable" onload="calculatetest()">
                    <thead>
                    <tr class="invHeading">
                        <th width="50"> Item #</th>
                        <th width="200">Item Name</th>
                        <th width="200">Item Description</th>
                        <th>Price Per Unit</th>
                        <th>Case Price</th>
                        <th>Quantity</th>
                        <th class="game" width="150">Game</th>
                        <th>Total ( $ )</th>
                        <th>Remove</th>


                    </tr>

                    </thead>

                    <tbody>
                    <tr class="clone clonedInput">
                        <td><br/><input type="text"  id="item_num" name="item_num[]" disabled readonly
                                   style="width:30px;border:none;background:none"/></td>
                        <td><br/> <input type="text" name='item_name[]' placeholder='Item  Name' id="item_name"
                                       class='form-control item_name mysearch'  maxlength="225" required>
                        </td>
                        <td>
                            <textarea name='item[]' placeholder='Item  Description' id="item"
                                      class='form-control item' cols="30" rows="4" maxlength="225" required></textarea>
                        </td>

                        <td><br/> <input type='number' name='price[]' placeholder='Unit Price' id="price"
                                         class='form-control' min="0.00" step=".01" value="0.00"
                                         required></td>
                        <td>
                            <br/> <input type='number' name='case_price[]' placeholder='Case Price' id="case_price"
                                         class='form-control' min="0.00" step=".01" value="0.00"
                                         required></td>
                        </td>
                        <td><br/> <input type='number' name='qty[]' placeholder='Quantity'
                                         class='form-control qty' min="1" step="1" id="qty" value="00"
                                         required></td>
                        <td class="game" style="display:none"><br/>
                            <select name='game[]' id='game_0' class='game  form-control'>
                                <option value="">For Various Games</option>
                                @foreach( \SiteHelpers::getGamesName() as $game_title)
                                    <option value="{{ $game_title->id }}"> {{ $game_title->game_name }}</option>
                                @endforeach
                            </select>
                            <input type='hidden' name='product_id[]' id="product_id">
                        </td>
                        <input type='hidden' name='request_id[]' id="request_id">
                        <td><br/><input type="text" name="total" value="" readonly class="form-control"/></td>
                        <td><br/> <a onclick=" $(this).parents('.clonedInput').remove(); calculateSum(); return false"
                                     href="#" class="remove btn btn-xs btn-danger">-</a>
                            <input type="hidden" name="counter[]">
                        </td>
                    </tr>
                    </tr>
                    <tr>
                        <td class="game"></td>
                        <td colspan="6" class="text-right"><strong> Subtotal ( $ ) </strong></td>
                        <td><input type="text" name="Subtotal" value="{{ $data['order_total'] }}" readonly
                                   class="form-control"/></td>
                        <td></td>

                    </tr>
                    </tbody>

                </table>
                <input type="hidden" name="enable-masterdetail" value="true">
            </div>
            <br/><br/>

            <a href="javascript:void(0);" class="addC btn btn-xs btn-info" rel=".clone" id="add_new_item"><i
                        class="fa fa-plus"></i>
                New Item</a>
            <hr/>


            <div style="clear:both"></div>

            <div class="form-group" style="margin-bottom:50px">
                <label class="col-sm-4 text-right">&nbsp;</label>

                <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary btn-sm "><i
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
                sum = Qty * Price;
                Subtotal += sum;
                sum = sum.toFixed(2);
                $(this).find("input[name*='total']").val(sum);
            });

            Subtotal = Subtotal.toFixed(2);
            $("input[name='Subtotal']").val(Subtotal);
            $("#total_cost").val(Subtotal);
        }

        $(document).ready(function () {

            hideShowAltLocation();
            $("#item_num").val('1');
            $("#submit_btn").hide();
            $("#location_id").jCombo("{{ URL::to('order/comboselect?filter=location:id:id|location_name ') }}",
                    {selected_value: '{{ $data["order_loc_id"] }}',initial_text:'-------- Select Location --------'});

            $("#vendor_id").jCombo("{{ URL::to('order/comboselect?filter=vendor:id:vendor_name') }}",
                    {selected_value: '{{ $data["order_vendor_id"] }}',initial_text:'-------- Select Vendor --------'});

            $("#freight_type_id").jCombo("{{ URL::to('order/comboselect?filter=freight:id:freight_type') }}",
                    {selected_value: '{{ $data['order_freight_id'] }}',initial_text:'-------- Select Freight Type --------'});

            $("#order_type_id").jCombo("{{ URL::to('order/comboselect?filter=order_type:id:order_type') }}",
                    {selected_value: '{{ $data["order_type"] }}',initial_text:'-------- Select Order Type --------'});
            $("input[name*='total'] ").attr('readonly', '1');
            $("input[name*='qty'] , input[name*='bulk_Price'] ").addClass('calculate');
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
            $(".select3").select2({width: "98%"});
            $('.date').datepicker({format: 'yyyy-mm-dd', autoClose: true})
            $('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});
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
            var item_name_array=<?php echo json_encode($data['itemNameArray']) ?>;
            var item_case_price=<?php echo json_encode($data['itemCasePrice']) ?>;
            var item_total = 0;
            for (var i = 0; i < requests_item_count; i++) {

                $('input[name^=item_num]').eq(i).val(i + 1);
                $('textarea[name^=item]').eq(i).val(order_description_array[i]);
                $('input[name^=price]').eq(i).val(order_price_array[i]);
                $('input[name^=qty]').eq(i).val(order_qty_array[i]);
                $('input[name^=product_id]').eq(i).val(order_product_id_array[i]);
                $('input[name^=request_id]').eq(i).val(order_request_id_array[i]);
                $('input[name^=item_name]').eq(i).val(item_name_array[i]);
                $('input[name^=case_price]').eq(i).val(item_case_price[i]);
                if (i < requests_item_count - 1) //COMPENSATE FOR BEGINNING WITH ONE INPUT
                {

                    $("#add_new_item").trigger("click");
                }

            }
            calculateSum();


        });

    $("#experiment").click(function(){
       init();
    });

        function showRequest() {
            $('.ajaxLoading').show();
        }
        function showResponse(data) {

            if (data.status == 'success') {
                var url = location.href;
                if (url.indexOf('submitorder') != -1) {
                    location.href = "{{ url() }}/managefegrequeststore";
                }
                else {
                    ajaxViewClose('#{{ $pageModule }}');
                    ajaxFilter('#{{ $pageModule }}', '{{ $pageUrl }}/data');
                }
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }
        $("#location_id").click(function () {
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
                $("#submit_btn").fadeOut();
                return;
            }

            validatePONumber();
        });
        // -----------------for checking and validating PO number.... -----------------------//
        var poajax;
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
        function calculatetest() {
            alert("Image is loaded");
        }
        $("#add_new_item").click(function () {

          /*  $("textarea[name^='item']").each(function(){
                id=$(this).attr('id');
             testauto(id);
            });*/
            counter = 0;
            $('input[name^=item_num]').each(function () {
                counter = counter + 1;
            });
            $('#item_num' + counter).val(counter);
            init();

        });

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
        var init = function (){
            var cache = {},
                    lastXhr;
            console.log($(".mysearch").length);
            $("input.item_name,.copy1 input.item_name, .copy2 input.item_name,.copy3 input.item_name,.copy3 input.item_name,.copy4 input.item_name,.copy5 input.item_name,.copy6 input.item_name,.copy7 input.item_name,.copy8 input.item_name,.copy9 input.item_name,.copy10 input.item_name,.copy11 input.item_name,.copy12 input.item_name,.copy13 input.item_name,.copy14 input.item_name,.copy15 input.item_name,.copy16 input.item_name,.copy17 input.item_name,.copy18 input.item_name,.copy19 input.item_name,.copy20 input.item_name").on("focus", function(){
                $(this).autocomplete({
                    minLength: 2,
                    source: function( request, response ) {
                        var term = request.term;
                        lastXhr = $.getJSON( "order/autocomplete", request, function( data, status, xhr ) {
                            cache[ term ] = data;

                            if ( xhr === lastXhr ) {
                                response( data );
                            }
                        });
                    },
                    select: function( event, ui ) {
                        $.ajax({url: "order/productdata",type:"get",dataType:'json',data:{'product_id':ui.item.value}, success: function(result){
                           alert(result.item_description);
                            $(this).parent('tr').text(result.item_descriptoin);
                            if(result.unit_price) {
                                $("#price").val(result.unit_price);
                            }
                            else
                            {
                                $("#price").val(0.00);
                            }
                            if(result.case_price) {
                                $("#case_price").val(result.case_price);
                            }
                            else
                            {
                                $("#case_price").val(0.00);
                            }
                            $("#product_id").val(result.id);
                        }});
                    }
                });
            });


            /*$( "input[id^='item_name']").each(function(index){
                console.log($(this));
                $(this).autocomplete({
                    minLength: 2,
                    source: function( request, response ) {
                        var term = request.term;

                        if ( term in cache ) {
                            response( cache[ term ] );
                            return;
                        }
                        lastXhr = $.getJSON( "order/autocomplete", request, function( data, status, xhr ) {
                            cache[ term ] = data;

                            if ( xhr === lastXhr ) {
                                response( data );
                            }
                        });
                    },
                    select: function( event, ui ) {
                        $.ajax({url: "order/productdata",type:"get",dataType:'json',data:{'product_id':ui.item.value}, success: function(result){
                            $("#item").val(result.item_description);
                            if(result.unit_price) {
                                $("#price").val(result.unit_price);
                            }
                            else
                            {
                                $("#price").val(0.00);
                            }
                            if(result.case_price) {
                                $("#case_price").val(result.case_price);
                            }
                            else
                            {
                                $("#case_price").val(0.00);
                            }
                            $("#product_id").val(result.id);
                        }});
                    }
                });
            })*/



        };
        //init();
$(function()
        {
            $("#experiment").trigger('click');
        });
    </script>
    <style>
        .ui-corner-all
        {
            width:25%!important;
        }
    </style>
