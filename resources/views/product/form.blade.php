@if($setting['form-method'] =='native')
    <div class="sbox">
        <div class="sbox-title">
            <h4>
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
            </h4>
        </div>

        <div class="sbox-content">
            @endif
            {!! Form::open(array('url'=>'product/save/'.$row['id'], 'class'=>'form-horizontal','files' => true ,
            'parsley-validate'=>'','novalidate'=>' ','id'=> 'productFormAjax')) !!}
            <div class="col-md-12">
                <fieldset>
                    <legend> FEG Store Products</legend>


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
                        <label for="SKU" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('SKU', (isset($fields['sku']['language'])?
                            $fields['sku']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::text('sku', $row['sku'],array('class'=>'form-control', 'placeholder'=>'',
                            'required'=>'required')) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    <div class="form-group  ">
                        <label for="Prod Type Id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Product Type', (isset($fields['prod_type_id']['language'])?
                            $fields['prod_type_id']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">

                            <select name='prod_type_id' rows='5' id='prod_type_id' class='select2 '
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
                            <select name='prod_sub_type_id' rows='5' id='prod_sub_type_id' class='select2 '></select>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Quantity Per Case" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Quantity Per Case', (isset($fields['num_items']['language'])?
                            $fields['num_items']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::number('num_items', $row['num_items'],array('class'=>'form-control',
                            'placeholder'=>'','step'=>1,'min'=>1,'placeholder'=>'0', 'id'=>'qty_input')) !!}
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
                            <div class="input-group" style="float: left;width: 100%;">
                                <span class="input-group-addon" style="float: left;width: 33px !important;padding: 8px;">$</span>
                                {!! Form::text('case_price',
                                number_format((double)$row['case_price'],3),array('class'=>'form-control',
                                'placeholder'=>'','required'=>'required','type'=>'number','min' => '0','step'=>'1','id'=>'case_price_input','style'=>"float: left;width: 94%;" ))
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
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('unit_price',
                                number_format((double)$row['unit_price'],3),array('class'=>'form-control',
                                'placeholder'=>'','required'=>'required','type'=>'number','min' => '0','step'=>'1', 'id'=>'unit_price_input' ))
                                !!}
                            </div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group" id="retail_price">
                        <label for="Retail Price" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Retail Price', (isset($fields['retail_price']['language'])?
                            $fields['retail_price']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('retail_price',
                                number_format((double)$row['retail_price'],3),array('class'=>'form-control',
                                'placeholder'=>'','type'=>'number','min' => '0','step'=>'1','id'=>'retail_input' )) !!}
                            </div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  " id="ticket_value">
                        <label for="Ticket Value" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])?
                            $fields['ticket_value']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::text('ticket_value', $row['ticket_value'],array('class'=>'form-control',
                            'placeholder'=>'','id'=>'ticket_input')) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="vendor_id" class="control-label col-md-4 text-left">
                            Vendor </label>

                        <div class="col-md-6">
                            <select name='vendor_id' id='vendor_id' class='select2' required></select>
                        </div>
                        <div class="col-md-2"></div>
                    </div>

                    <div class="form-group  ">
                        <label for="Is Reserved" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Is Reserved', (isset($fields['is_reserved']['language'])?
                            $fields['is_reserved']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
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
                    <div class="form-group  ">
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

                            <div class="col-md-2" style="padding-top: 3px;">
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

                    <div class="col-md-6">
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

                    <div class="col-md-6">
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

                    <div class="col-md-6">
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
                </fieldset>
            </div>


            <div style="clear:both"></div>

            <div class="form-group">
                <label class="col-sm-4 text-right">&nbsp;</label>

                <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary btn-sm "><i
                                class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
                    <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm">
                        <i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
                </div>
            </div>
            {!! Form::close() !!}


            @if($setting['form-method'] =='native')
        </div>
    </div>
@endif




<script type="text/javascript">
    $(document).ready(function () {
        numberFieldValidationChecks($("#qty_input"));
        $("#vendor_id").jCombo("{{ URL::to('product/comboselect?filter=vendor:id:vendor_name:hide:0:status:1') }}",
                {selected_value: '{{ $row["vendor_id"] }}'});

        $("#prod_type_id").jCombo("{{ URL::to('product/comboselect?filter=order_type:id:order_type:can_request:1') }}",
                {selected_value: '{{ $row["prod_type_id"] }}'});


        $("#prod_sub_type_id").jCombo("{{ URL::to('product/comboselect?filter=product_type:id:type_description') }}&parent=request_type_id:",
                {parent: '#prod_type_id', selected_value: '{{ $row["prod_sub_type_id"] }}'});
// for Redemption Prizes show Ticket Value
        if ("{{$row["prod_type_id"] }}" == 7 || "{{$row["prod_type_id"] }}" == 8) {
            $("#ticket_value").show();
            $("#ticket_input").attr('required','required');
        }

// for Instant win Prizes show Retail Price
        if ("{{$row["prod_type_id"] }}" == 8) {
            $("#retail_price").show();
            $("#retail_input").attr('required','required');
        }

        $('.editor').summernote();
        $('.previewImage').fancybox();
        $('.tips').tooltip();
        renderDropdown($(".select2"), {width: "100%"});
        $('.date').datepicker({format: 'mm/dd/yyyy', autoclose: true})
        $('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
        $('input[type="checkbox"],input[type="radio"]').not('.test').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
        $('.removeCurrentFiles').on('click', function () {
            var removeUrl = $(this).attr('href');
            $.get(removeUrl, function (response) {
            });
            $(this).parent('div').empty();
            return false;
        });
        var form = $('#productFormAjax');
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
    $("#prod_type_id").click(function () {
        if ($(this).val() == "8") {
            $("#retail_price").show(300);
            $("#retail_price").attr('required','required');
        }
        else {
            $("#retail_price").hide(300);
            $("#retail_price").removeAttr('required');
        }
        if ($(this).val() == "7" || $(this).val() == "8" ) {
            $("#ticket_value").show(300);
            $("#ticket_value").attr('required','required');
        }
        else {
            $("#ticket_value").hide(300);
            $("#ticket_value").removeAttr('required');
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
$('#qty_input,#case_price_input').on('keyup',function(){
    var case_price = $("#case_price_input").val();
    var quantity = $("#qty_input").val();
    var unit_price = case_price/quantity;
    if(quantity != 0 && unit_price != 0) {
        $('#unit_price_input').val(unit_price.toFixed(3));
    }
    else
    {
        $('#unit_price_input').val(0.000);
    }
});
</script>
<style>
    #ticket_value, #retail_price {
        display: none;
    }
</style>
