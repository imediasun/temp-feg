@if($setting['form-method'] =='native')
    <style>
        .multiselect-container.dropdown-menu
        {
            overflow-y: scroll;
            height: 300px;
        }
        .multiselect-item .input-group span.input-group-addon
        {
            float: none !important;
        }
        .input-group span.input-group-addon {
            /*float: none !important;*/
            width: 4.5%;
            padding: 8px 10px;
        }
        .multiselect-clear-filter{
            margin-left: -10px !important;
            height: 35px;
            margin-right: 8px;
        }
        .multiselect-search{
            height: 35px;
        }
        .productTypeBox:first-child
        {
            border-top: 1px solid #ddd;
            padding-top: 14px;
        }
        .productTypeBox
        {
            border-bottom: 1px solid #ddd;
            float: left;
            width: 100%;
            margin-bottom: 14px;
        }
    </style>
    <div class="sbox">
        <div class="sbox-title">
            <h4>@if($id)
                    <i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit FEG Store Product
                @else
                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Create New FEG Store Product
                @endif &nbsp;&nbsp;
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose('#{{ $pageModule }}');"><i class="fa fa fa-times"></i></a>
            </h4>
        </div>

        <div class="sbox-content">
            @endif
            {!! Form::open(array('url'=>'product/save/'.$row['id'], 'class'=>'form-horizontal','files' => true ,
            'parsley-validate'=>'','novalidate'=>' ','id'=> 'productFormAjax')) !!}
            <div class="col-md-12">
                <fieldset>

                    <div class="form-group  ">
                        <label for="Item Name" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Item Name', (isset($fields['vendor_description']['language'])?
                            $fields['vendor_description']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::text('vendor_description',
                            $row['vendor_description'],array('class'=>'form-control', 'placeholder'=>'',
                            'required'=>'required' )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Item Description" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Item Description',
                            (isset($fields['item_description']['language'])? $fields['item_description']['language'] :
                            array())) !!}
                        </label>

                        <div class="col-md-6">
					  <textarea name='item_description' rows='5' id='item_description' class='form-control '
                              >{{ $row['item_description'] }}</textarea>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Size" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Size', (isset($fields['size']['language'])?
                            $fields['size']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::text('size', $row['size'],array('class'=>'form-control', 'placeholder'=>'', )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Addl Details" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Add\'l Details', (isset($fields['details']['language'])?
                            $fields['details']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <textarea name='details' rows='5' id='details'
                                      class='form-control '>{{ $row['details'] }}</textarea>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Quantity Per Case" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Quantity Per Case', (isset($fields['num_items']['language'])?
                            $fields['num_items']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::number('num_items', $row['num_items'],array('class'=>'form-control',
                            'placeholder'=>'','step'=>1,'parsley-min'=>1,'placeholder'=>'0', 'id'=>'qty_input')) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <span class="product_types">
                    <div class="form-group  ">
                        <label for="Prod Type Id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Product Type', (isset($fields['prod_type_id']['language'])?
                            $fields['prod_type_id']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">

                            <select name='prod_type_id[]' data-previous="{{$row['prod_type_id']}}" rows='5' data-counter="1" id='prod_type_id_1' class='select2 prod_type'
                                    required='required'></select>

                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    <div class="form-group  ">
                        <label for="Prod Sub Type Id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Product Subtype',
                            (isset($fields['prod_sub_type_id']['language'])? $fields['prod_sub_type_id']['language'] :
                            array())) !!}
                        </label>

                        <div class="col-md-6">
                            <select name='prod_sub_type_id[1]' data-counter="1" rows='5' id='prod_sub_type_id_1' class='select2 prod_sub_type'></select>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Expense Category" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Expense Category', (isset($fields['expense_category']['language'])? $fields['expense_category']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <select name='expense_category[1]' rows='5' id='expense_category_1' class='select2'></select>
                            {{--<input class="form-control" placeholder="" parsley-type="number" required="required" id="expense_category_1" name="expense_category[1]" type="text" value="{{$row['expense_category']}}">--}}
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group" id="retail_price_1">
                        <label for="Retail Price" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Retail Price', (isset($fields['retail_price']['language'])?
                            $fields['retail_price']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <div class="input-group ig-full">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('retail_price[1]',
                                $row['retail_price'] == ''?'':(double)$row['retail_price'],array('class'=>'form-control',
                                'placeholder'=>'0.00','type'=>'number','parsley-min' => '0','step'=>'1','id'=>'retail_input_1' )) !!}
                            </div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  " id="ticket_value_1">
                        <label for="Ticket Value" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])?
                            $fields['ticket_value']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::text('ticket_value[1]', $row['ticket_value'],array('class'=>'form-control',
                            'placeholder'=>'','id'=>'ticket_input_1')) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    </span>
                    @if(!$id)
                        <span id="more_types_container">

                        </span>
                        <a id="add_more_types" class="btn btn-primary pull-right">Add More</a>
                    @endif
                    <div class="form-group  ">
                        <label for="SKU" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('SKU', (isset($fields['sku']['language'])?
                            $fields['sku']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <input type="text" name="sku" id="sku" value="{{$row['sku']}}" class="form-control"  @if(!(in_array($row['prod_type_id'], [1,4,20]))) required='required' @endif>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Case Price" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Case Price', (isset($fields['case_price']['language'])?
                            $fields['case_price']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <div class="input-group ig-full">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('case_price',
                                $row['case_price'] == ''?'':(double)$row['case_price'],array('class'=>'form-control fixDecimal',
                                'placeholder'=>'0.00','required'=>'required','type'=>'number','parsley-min' => '0','step'=>'1','id'=>'case_price_input' ))
                                !!}
                            </div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Unit Price" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Unit Price',
                            (isset($fields['unit_price']['language'])? $fields['unit_price']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <div class="input-group ig-full">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('unit_price',
                                $row['unit_price'] == ''?'':(double)$row['unit_price'],array('class'=>'form-control fixDecimal',
                                'placeholder'=>'0.00','required'=>'required','type'=>'number','parsley-min' => '0','step'=>'1', 'id'=>'unit_price_input' ))
                                !!}
                            </div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="vendor_id" class="control-label col-md-4 text-left">
                            Vendor </label>

                        <div class="col-md-6">
                            <select name='vendor_id' data-seprate='true' id='vendor_id' class='select2' required></select>
                        </div>
                        <div class="col-md-2"></div>
                    </div>

                    <div class="form-group">
                        <label for="Is Reserved" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Is Reserved', (isset($fields['is_reserved']['language'])?
                            $fields['is_reserved']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6 check-no">
                            <?php $is_reserved = explode(",", $row['is_reserved']); ?>
                            <label class='checked checkbox-inline'>
                                <input type="hidden" name="is_reserved" value="0"/>
                                <input type='checkbox' name='is_reserved' value='1' class=''
                                       @if(in_array('1',$is_reserved))checked @endif
                                        /> </label>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Reserved Qty" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Reserved Qty', (isset($fields['reserved_qty']['language'])?
                            $fields['reserved_qty']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::text('reserved_qty', $row['reserved_qty'],array('class'=>'form-control',
                            'placeholder'=>'', )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Img" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Img', (isset($fields['img']['language'])?
                            $fields['img']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">

                            <!--<a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles('img')"><i class="fa fa-plus"></i></a>-->

                            <div class="imgUpl">
                                <input type='file' name='img'/>
                            </div>

                            <div class="col-md-2 row" style="padding-top: 3px;">
                                <?php
                                echo SiteHelpers::showUploadedFile($row['img'], '/uploads/products/', 30, false)
                                ?>
                            </div>
                        </div>
                    </div>
                </fieldset>


                <div class="form-group  ">
                    <label for="Inactive" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('Inactive', (isset($fields['inactive']['language'])?
                        $fields['inactive']['language'] : array())) !!}
                    </label>

                    <div class="col-md-6 check-no">
                        <?php $inactive = explode(",", $row['inactive']); ?>
                        <label class='checked checkbox-inline'>
                            <input type="hidden" name="inactive" value="0"/>
                            <input type='checkbox' name='inactive' value='1' class=''
                                   @if(in_array('1',$inactive))checked @endif
                                    /> </label>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div class="form-group  ">
                    <label for="In Development" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('In Development', (isset($fields['in_development']['language'])?
                        $fields['in_development']['language'] : array())) !!}
                    </label>

                    <div class="col-md-6 check-no">
                        <?php $indevelopment = explode(",", $row['in_development']); ?>
                        <label class='checked checkbox-inline'>
                            <input type="hidden" name="in_development" value="0"/>
                            <input type='checkbox' name='in_development' value='1' class=''
                                   @if(in_array('1',$indevelopment))checked @endif
                                    /> </label>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div class="form-group  ">
                    <label for="Hot Item" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('Hot Item', (isset($fields['hot_item']['language'])?
                        $fields['hot_item']['language'] : array())) !!}
                    </label>

                    <div class="col-md-6 check-no">
                        <?php $hot_item = explode(",", $row['hot_item']); ?>
                        <label class='checked checkbox-inline'>
                            <input type="hidden" name="hot_item" value="0"/>
                            <input type='checkbox' name='hot_item' value='1' class=''
                                   @if(in_array('1',$hot_item))checked @endif
                                    /> </label>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div class="form-group  ">
                    <label for="Exclude From Export" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('Exclude From Export', (isset($fields['exclude_export']['language'])?
                        $fields['exclude_export']['language'] : array())) !!}
                    </label>
                    <div class="col-md-6 check-no">
                        <label class='checked checkbox-inline'>
                            <input type="hidden" name="exclude_export" value="0"/>
                            <input type='checkbox' name='exclude_export' value="1" class=''
                                   @if($row['exclude_export'] == 1)checked @endif
                            /> </label>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                </fieldset>
            </div>


            <div style="clear:both"></div>

            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-primary btn-sm "><i
                                class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
                    <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}');" class="btn btn-success btn-sm">
                        <i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
                </div>
            </div>
            {!! Form::close() !!}


            @if($setting['form-method'] =='native')
        </div>
    </div>
@endif



//
<script type="text/javascript">
    var types_counter = 1;
    $(document).ready(function () {

        numberFieldValidationChecks($("#qty_input"));
        var form = $('#productFormAjax');

        form.parsley();
        $('input[type="checkbox"],input[type="radio"]').not('.test').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });

            $("#prod_type_id_1").jCombo("{{ URL::to('product/comboselect?filter=order_type:id:order_type:can_request:1') }}",
            {selected_value: '{{ $row["prod_type_id"] }}'});

        renderDropdown($(".select2"), {width: "100%"});
        if('{{ $row["prod_type_id"] }}')
        {
            $("#prod_sub_type_id_1").jCombo("{{ URL::to('product/comboselect?filter=product_type:id:type_description') }}&parent=request_type_id:{{ $row["prod_type_id"] }}",
                    {selected_value: '{{ $row["prod_sub_type_id"] }}'});
        }
        $("#expense_category_1").jCombo("{{ URL::to('product/expense-category-groups') }}",
                {selected_value: '{{ $row["expense_category"] }}'});
        /*$("#prod_type_id_1").click(function () {
            $("#prod_sub_type_id_1").jCombo("{{ URL::to('product/comboselect?filter=product_type:id:type_description') }}&parent=request_type_id:"+$('#prod_type_id_1').val()+"",
                    {selected_value: '{{ $row["prod_sub_type_id"] }}'});
            if($(this).val()) {
                //need to uncomment after discussion
                getExpenseCategory($(this).val(),null,1);
            }
            if($('#prod_type_id_1').val() == 1 || $('#prod_type_id_1').val() == 4 || $('#prod_type_id_1').val() == 20)
            {
                form.parsley().destroy();
                $('input[name="sku"]').removeAttr('required');
                form.parsley();
            }
            else
            {
                form.parsley().destroy();
                $('input[name="sku"]').attr('required','required');
                form.parsley();
            }

            if ($(this).val() == "8") {
                $("#retail_price_1").show(300);
                $("#retail_input_1").attr('required','required');
            }
            else {
                $("#retail_price_1").hide(300);
                $("#retail_input_1").removeAttr('required');
            }
            if ($(this).val() == "7") {
                $("#ticket_value_1").show(300);
                $("#ticket_input_1").attr('required','required');
            }else if($(this).val() == "8"){
                $("#ticket_value_1").show(300);
            }
            else {
                $("#ticket_value_1").hide(300);
                $("#ticket_input_1").removeAttr('required');
            }
        });*/
        //need to uncomment after discussion
//        $("#prod_sub_type_id_1").click(function () {
//            if($(this).val()) {
//                getExpenseCategory($("#prod_type_id_1").val(),$(this).val(),1);
//            }
//        });

        $("#vendor_id").jCombo("{{ URL::to('product/comboselect?filter=vendor:id:vendor_name:hide:0:status:1') }}",
                {selected_value: '{{ $row["vendor_id"] }}' ,
                    <?php $row["vendor_id"] == '' ? '': print_r("onLoad:addInactiveItem('#vendor_id[data-seprate=true]', ".$row['vendor_id']." , 'Vendor', 'status' , 'vendor_name')") ?>
                });
        // for Redemption Prizes show Ticket Value
        if ("{{$row["prod_type_id"] }}" == 7) {
            $("#ticket_value_1").show();
            $("#ticket_input_1").attr('required','required');
        }

        // for Instant win Prizes show Retail Price
        if ("{{$row["prod_type_id"] }}" == 8) {
            $("#retail_price_1").show();
            $("#retail_input_1").attr('required','required');
        }

        // for Instant win Prizes show Ticket Value
        if ("{{$row["prod_type_id"] }}" == 8) {
            $("#ticket_value_1").show();
            //$("#ticket_input_1").attr('required','required');
        }

        $('.editor').summernote();
        $('.previewImage').fancybox();
        $('.tips').tooltip();


        $('.date').datepicker({format: 'mm/dd/yyyy', autoclose: true})
        $('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
        $('.removeCurrentFiles').on('click', function () {
            var removeUrl = $(this).attr('href');
            $.get(removeUrl, function (response) {
            });
            $(this).parent('div').empty();
            return false;
        });

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

        $(document).on('change',"select.prod_type",function(){

            var previous = $(this).attr('data-previous');
            var selectedType = $(this).val();
            var counter = $(this).attr('data-counter');
            if(selectedType && selectedType != previous){

                $(this).parents('.product_types').find("select.prod_sub_type").jCombo("{{ URL::to('product/comboselect?filter=product_type:id:type_description') }}&parent=request_type_id:"+selectedType+"");
                //renderDropdown($(".select2"), {width: "100%"});

                getExpenseCategory(selectedType,null,counter);
                var sku = 'input[name="sku"]';
                var retail_price = '#retail_input_'+counter;
                var retail_price_div = '#retail_price_'+counter;
                var ticket_input = '#ticket_input_'+counter;
                var ticket_input_div = '#ticket_value_'+counter;
                if(selectedType == 1 || selectedType == 4 || selectedType == 20)
                {
                    form.parsley().destroy();
                    $(sku).removeAttr('required');
                    form.parsley();
                }
                else
                {
                    form.parsley().destroy();
                    $(sku).attr('required','required');
                    form.parsley();
                }

                if (selectedType == "8") {
                    $(retail_price_div).show(300);
                    $(retail_price).attr('required','required');
                }
                else {
                    $(retail_price_div).hide(300);
                    $(retail_price).removeAttr('required');
                }
                if (selectedType == "7") {
                    $(ticket_input_div).show(300);
                    $(ticket_input).attr('required','required');
                }
                else if(selectedType == "8"){
                    $(ticket_input_div).show(300);
                }
                else {
                    $(ticket_input_div).hide(300);
                    $(ticket_input).removeAttr('required');
                }
            }
            $(this).attr('data-previous' , selectedType);
        });
        //need to uncomment after discussion
//    $(document).on('change',"select.prod_sub_type",function(){
//        console.log('here2');
//        if($(this).val()) {
//            getExpenseCategory($("#prod_type_id_"+$(this).attr('data-counter')).val(),$(this).val(),types_counter);
//        }
//    });
        $(document).on('click',".remove_me",function(){
            count = $(this).attr('data-count');
            count = "#remove_me_"+count;
           // console.log(count);
            $("#add_more_types").show();
            $(count).remove();
        });
        $("[id^='toggle_trigger_']").bootstrapSwitch( {onColor: 'default', offColor:'primary'});
    });

    function showRequest() {
        $('.ajaxLoading').show();
    }
    function showResponse(data) {

        if (data.status == 'success') {
            $('.btn.btn-search[data-original-title="Reload Data"]').trigger("click");
          //  ajaxFilter('#{{ $pageModule }}', '{{ $pageUrl }}/data');
            notyMessage(data.message);
            ajaxViewClose('#{{ $pageModule }}');
            $('#sximo-modal').modal('hide');
        } else {
            notyMessageError(data.message);
            $('.ajaxLoading').hide();
            return false;
        }
    }
    $('#qty_input,#case_price_input').on('keyup mouseup change',function(){
        var case_price = $("#case_price_input").val();
        var quantity = $("#qty_input").val();
        var unit_price = case_price/quantity;
        if(quantity != 0 && unit_price != 0) {
            $('#unit_price_input').val(unit_price);
            $('#unit_price_input').blur();
        }
        else
        {
            $('#unit_price_input').val(0.000);
        }
    });
    $("#prod_type_id_1").click(function () {

    });
    function getExpenseCategory(order_type_id,product_type_id,count)
    {
        var expence_field = $("#expense_category_"+count);
        expence_field.val('');
        expence_field.trigger('change');
        if(product_type_id === null)
        {
            product_type_id="";
        }
        $.get('product/expense-category',{'order_type':order_type_id,'product_type':product_type_id},function(data){
            if(data.expense_category)
            {
                expence_field.val(data.expense_category);
                expence_field.trigger('change');
            }
        },'json');
    }


    $("#add_more_types").click(function () {
        types_counter++;

        var more_types_html = '<span class="product_types productTypeBox" id="remove_me_'+types_counter+'"><div class="form-group  "> ' +
                '<label for="Prod Type Id" class=" control-label col-md-4 text-left">{!! SiteHelpers::activeLang("Product Type", (isset($fields["prod_type_id"]["language"])? $fields["prod_type_id"]["language"] : array())) !!}</label> ' +
                '<div class="col-md-6"> <select data-previous="0" name="prod_type_id[]" rows="5" data-counter="'+types_counter+'" id="prod_type_id_'+types_counter+'" class="prod_type select2 "required="required"></select>' +
                ' </div> <div class="col-md-2"> <button data-count="'+types_counter+'" class="remove_me pull-right btn btn-xs btn-danger"><i class="fa fa fa-times"></i></button></div> </div> <div class="form-group  "> ' +
                '<label for="Prod Sub Type Id" class=" control-label col-md-4 text-left">{!! SiteHelpers::activeLang("Product Subtype",(isset($fields["prod_sub_type_id"]["language"])? $fields["prod_sub_type_id"]["language"] : array())) !!} </label>' +
                ' <div class="col-md-6"> <select name="prod_sub_type_id['+types_counter+']" rows="5" data-counter="'+types_counter+'" id="prod_sub_type_id_'+types_counter+'" class="prod_sub_type select2 "></select>' +
                ' </div> <div class="col-md-2"> </div> </div> ' +
                '<div class="form-group"> <label for="Expense Category" class=" control-label col-md-4 text-left">{!! SiteHelpers::activeLang("Expense Category", (isset($fields["expense_category"]["language"])? $fields["expense_category"]["language"] : array())) !!}</label> ' +
                '<div class="col-md-6"><select name="expense_category['+types_counter+']" rows="5" id="expense_category_'+types_counter+'" class="select2"></select> ' +
                '</div> <div class="col-md-2"></div> </div>' +
                '<div class="form-group" id="retail_price_'+types_counter+'"> <label for="Retail Price" class="control-label col-md-4 text-left addcolon">Retail Price </label> ' +
                '<div class="col-md-6"> ' +
                '<div class="input-group ig-full"> <span class="input-group-addon">$</span> <input class="form-control parsley-validated retail_prices" placeholder="0.00" type="text" parsley-min="0" step="1" id="retail_input_'+types_counter+'" name="retail_price['+types_counter+']" value=""> </div> </div>' +
                ' <div class="col-md-2"> </div> </div>' +
                '<div class="form-group ticket_values " id="ticket_value_'+types_counter+'"> <label for="Ticket Value" class="control-label col-md-4 text-left addcolon">Ticket Value </label> ' +
                '<div class="col-md-6"> <input class="form-control" placeholder="" id="ticket_input_'+types_counter+'" name="ticket_value['+types_counter+']" type="text" value=""> </div> <div class="col-md-2">  </div> </div>' +
                '</span>';
        //console.log(more_types_html);
        $("#more_types_container").append(more_types_html);

        $("#prod_type_id_"+types_counter).jCombo("{{ URL::to('product/comboselect?filter=order_type:id:order_type:can_request:1') }}");
        $("#expense_category_"+types_counter).jCombo("{{ URL::to('product/expense-category-groups') }}");
        renderDropdown($(".select2"), {width: "100%"});
        <?php $NETSUITE_PRODUCT_MAX_LENGTH = config('app.NETSUITE_PRODUCT_MAX_LENGTH'); ?>
      <?php if($NETSUITE_PRODUCT_MAX_LENGTH !=''){ ?>
        if(types_counter >= Number(<?php echo $NETSUITE_PRODUCT_MAX_LENGTH; ?>)){
            $(this).hide();
        }
        <?php } ?>

        console.log('debug');
        console.log(types_counter);
    });
    $(".fixDecimal").blur(function () {
        $(this).val($(this).fixDecimal());
    });

</script>
<style>

    #ticket_value, #retail_price {
        display: none;
    }
    .form-horizontal .control-label, .form-horizontal .radio, .form-horizontal .checkbox, .form-horizontal .radio-inline, .form-horizontal .checkbox-inline {
        padding-top: 0px;
        margin-top: 0;
        margin-bottom: 0;
    }
</style>
