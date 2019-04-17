<style> .form-group{ position: static; } </style>
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
            @include('product.form.form',['actionUrl' => $actionUrl, 'fields' => $fields, 'row' => $row, 'variations' => $variations, 'productTypes' => $productTypes, 'id' => $id,'pageModule' => $pageModule ])

            @if($setting['form-method'] =='native')
        </div>
    </div>
@endif



//
<script type="text/javascript">
    $(function(){
        showRequest();
        $('select[name="excluded_locations_and_groups[]"]').attr('multiple','multiple');
        $('select[name="excluded_locations_and_groups[]"]').change();

        var productTypeId = '{{ $row['prod_type_id'] }}';
        $.ajax({
            url: '/product/location-and-groups/{{ $row['id'] }}',
            data: {productTypeId:productTypeId},
            type: 'GET',
            success: function (response) {
                var optionHTML = '<option value="select_all">Select All</option>'+response.groups;
                optionHTML +=response.locations;
                var selectedValues = response.selectedValues;

                $('select[name="excluded_locations_and_groups[]"]').attr('multiple','multiple');
                $('select[name="excluded_locations_and_groups[]"]').select2({
                    width: '100%',
                    closeOnSelect: false
                });
                $('select[name="excluded_locations_and_groups[]"]').html(optionHTML);
                $('select[name="excluded_locations_and_groups[]"]').change();
                $('select[name="excluded_locations_and_groups[]"]').val(selectedValues).change();

                updateDropdownsGroups("excluded_locations_and_groups[]");

                 }
        });

    });

    var types_counter = 1;
    $(document).ready(function () {

        numberFieldValidationChecks($("#qty_input"));
        var form = $('#productFormAjax');

        form.parsley();
        $('input[type="checkbox"],input[type="radio"]').not('.test').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
        renderDropdown($(".select2"), {width: "100%"});

        @if(count($variations) > 0)
        <?php $variationCount = 1; ?>
        @foreach($variations as $variation)


            $("#prod_sub_type_id_{{ $variationCount }}").jCombo("{{ URL::to('product/comboselect?filter=product_type:id:type_description') }}&parent=request_type_id:{{ $variation["prod_type_id"] }}",
                    {selected_value: '{{ $variation['prod_sub_type_id'] }}'});

        $("#expense_category_{{ $variationCount }}").jCombo("{{ URL::to('product/expense-category-groups') }}",
                {selected_value: '{{ $variation['expense_category'] }}'});
        setTicketAndRetailFields('{{ $variation["prod_type_id"] }}','{{ $variationCount }}');
        <?php
            $variationCount++;
        ?>
        @endforeach
        $('.ajaxLoading').hide();

    @else
    $('.ajaxLoading').hide();
        if('{{ $row["prod_type_id"] }}')
        {
            $("#prod_sub_type_id_1").jCombo("{{ URL::to('product/comboselect?filter=product_type:id:type_description') }}&parent=request_type_id:{{ $row["prod_type_id"] }}",
                    {selected_value: '{{ $row["prod_sub_type_id"] }}'});
        }
        $("#expense_category_1").jCombo("{{ URL::to('product/expense-category-groups') }}",
                {selected_value: '{{ $row["expense_category"] }}'});
        @endif

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

                var productSubType = $(this).parents('.product_types').find("select.prod_sub_type");
                var docElm = document.getElementById(productSubType.attr('id'));
                var productSubTypeId = Number(docElm.value) > 0 ? docElm.value:'0';

                console.log('Sub Type Id:'+productSubTypeId);

                    $(docElm).jCombo("{{ URL::to('product/comboselect?filter=product_type:id:type_description') }}&parent=request_type_id:" + selectedType + "&limit=WHERE:deleted_at:is:NULL",
                            {selected_value: productSubTypeId});


                var productTypeId = selectedType;
                if(productTypeId > 0) {
                    $('.ajaxLoading').show();
                    $.ajax({
                        url: '/product/location-and-groups/{{ $row['id'] }}',
                        data: {productTypeId: productTypeId,mode:'changingType'},
                        type: 'GET',
                        success: function (response) {

                            var productTypeSelectedValues = response.productTypeSelectedValues;
                            $('select[name="product_type_excluded_data[]"]').val(productTypeSelectedValues).change();
                            updateDropdownsGroups("product_type_excluded_data[]");
                            $('.ajaxLoading').hide();
                        }
                    });
                }



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
            var itemId = $(this).attr('data-remove-id');
            var  removedItemIds = document.getElementById('removedItemIds');
            if(Number(itemId) > 0){
                var IdString = '';
                if(removedItemIds.value == ''){
                    IdString = itemId;
                }else{
                    IdString = removedItemIds.value +','+ itemId;
                }
                removedItemIds.value = IdString;
            }
            count = "#remove_me_"+count;
            $("#add_more_types").show();
            $(count).remove();

            var total_types = document.getElementsByClassName('product_types').length;
            for(var i =0; i<total_types; i++ ){

                $('.excludeHidden').eq(i).attr('name','exclude_export['+i+']');
                $('.excludeCHK').eq(i).attr('name','exclude_export['+i+']');
                $('.excludeCHK').eq(i).iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue'
                });
            }

            var productForm = document.getElementById('productFormAjax');
            reInitFormValidatorParsley(productForm);
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
        var totalBox = document.getElementsByClassName('product_types').length + 1;
        if(types_counter < totalBox){
            types_counter = totalBox+1;
        }else{
            types_counter++;
        }
        var events = ' data-count="'+types_counter+'" ';
        var isDefaultExpenseCategoryInput = ' <label class="checked checkbox-inline">';
        isDefaultExpenseCategoryInput += '<input type="hidden" id="isDefaultExpenseCategory_'+types_counter+'"   name="is_default_expense_category[]" value="0"/>';
        isDefaultExpenseCategoryInput += '<input type="checkbox"  name="isdefault[]" class="isDefaultExpenseCategoryElm" id="isDefaultExpenseCategoryElm_'+types_counter+'" '+events+'  value="1"  /> Make Default</label>';
            @if(empty($row['id']))
        isDefaultExpenseCategoryInput = '';
                @endif

        var excludeExportChk = '<div class="form-group">';
        excludeExportChk += '<label for="Exclude From Export" class="control-label col-md-4 text-left">Exclude from Export</label>';
        excludeExportChk += '<div class="col-md-6 check-no">';
        excludeExportChk += '<label class="checked checkbox-inline">';
        excludeExportChk += '<input type="hidden" name="exclude_export[' + (types_counter - 2)+ ']" class="excludeHidden" value="0"/>';
        excludeExportChk += '<input type="checkbox" name="exclude_export[' + (types_counter - 2)+ ']" value="1" class="excludeCHK" /> </label>';
        excludeExportChk += '</div>';
        excludeExportChk += '<div class="col-md-2">';
        excludeExportChk += '</div>';
        excludeExportChk += '</div>';

        var more_types_html = '<span class="product_types productTypeBox" id="remove_me_'+types_counter+'"><div class="form-group  "> <input type="hidden" name="itemId[]" value="0">' +
                '<label for="Prod Type Id" class=" control-label col-md-4 text-left">{!! SiteHelpers::activeLang("Product Type", (isset($fields["prod_type_id"]["language"])? $fields["prod_type_id"]["language"] : array())) !!}</label> ' +
                '<div class="col-md-6"> <select data-previous="0" name="prod_type_id[]" rows="5" data-counter="'+types_counter+'" id="prod_type_id_'+types_counter+'" class="prod_type select2 "required="required"></select>' +
                ' </div> <div class="col-md-2"> <button data-count="'+types_counter+'" data-remove-id="0"  class="remove_me pull-right btn btn-xs btn-danger"><i class="fa fa fa-times"></i></button></div> </div> <div class="form-group  "> ' +
                '<label for="Prod Sub Type Id" class=" control-label col-md-4 text-left">{!! SiteHelpers::activeLang("Product Subtype",(isset($fields["prod_sub_type_id"]["language"])? $fields["prod_sub_type_id"]["language"] : array())) !!} </label>' +
                ' <div class="col-md-6"> <select name="prod_sub_type_id[]" rows="5" data-counter="'+types_counter+'" id="prod_sub_type_id_'+types_counter+'" class="prod_sub_type select2 "></select>' +
                ' </div> <div class="col-md-2"> </div> </div> ' +
                '<div class="form-group"> <label for="Expense Category" class=" control-label col-md-4 text-left">{!! SiteHelpers::activeLang("Expense Category", (isset($fields["expense_category"]["language"])? $fields["expense_category"]["language"] : array())) !!}</label> ' +
                '<div class="col-md-6"><select name="expense_category[]" rows="5" id="expense_category_' + types_counter + '" class="select2" required></select> ' +
                '</div> <div class="col-md-2">'+isDefaultExpenseCategoryInput+'</div> </div>' +
                '<div class="form-group" id="retail_price_'+types_counter+'"> <label for="Retail Price" class="control-label col-md-4 text-left addcolon">Retail Price </label> ' +
                '<div class="col-md-6"> ' +
                '<div class="input-group ig-full"> <span class="input-group-addon">$</span> <input class="form-control parsley-validated retail_prices" placeholder="0.00" type="text" parsley-min="0" step="1" id="retail_input_'+types_counter+'" name="retail_price[]" value=""> </div> </div>' +
                ' <div class="col-md-2"> </div> </div>' +
                '<div class="form-group ticket_values " id="ticket_value_'+types_counter+'"> <label for="Ticket Value" class="control-label col-md-4 text-left addcolon">Ticket Value </label> ' +
                '<div class="col-md-6"> <input class="form-control" placeholder="" id="ticket_input_'+types_counter+'" name="ticket_value[]" type="text" value=""> </div> <div class="col-md-2">  </div> </div>' +
                excludeExportChk+
                '</span>';
        //console.log(more_types_html);
        $("#more_types_container").append(more_types_html);

        excludedProductTypes = '{!! $excludedProductTypes !!}';
        excludedProductTypes = $.parseJSON('[' + excludedProductTypes + ']');

        $("#prod_type_id_"+types_counter).jCombo("{{ URL::to('product/comboselect?filter=order_type:id:order_type:can_request:1') }}", {excludeItems: excludedProductTypes});
        $("#expense_category_"+types_counter).jCombo("{{ URL::to('product/expense-category-groups') }}");
        $("#prod_type_id_"+types_counter).select2({width: "100%"});
        $("#expense_category_"+types_counter).select2({width: "100%"});
        $("#prod_sub_type_id_"+types_counter).select2({width: "100%"});
        $('#isDefaultExpenseCategoryElm_'+types_counter).iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
        // renderDropdown($(".select2"), {width: "100%"});
        <?php $NETSUITE_PRODUCT_MAX_LENGTH = config('app.NETSUITE_PRODUCT_MAX_LENGTH'); ?>
      <?php if($NETSUITE_PRODUCT_MAX_LENGTH !=''){ ?>
        if(Number(document.getElementsByClassName('product_types').length) >= Number(<?php echo $NETSUITE_PRODUCT_MAX_LENGTH; ?>)){
            $(this).hide();
        }
        <?php } ?>

        var total_types = document.getElementsByClassName('product_types').length;
        for(var i =0; i<total_types; i++ ){

            $('.excludeHidden').eq(i).attr('name','exclude_export['+i+']');
            $('.excludeCHK').eq(i).attr('name','exclude_export['+i+']');
            $('.excludeCHK').eq(i).iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
        }

        var productForm = document.getElementById('productFormAjax');
        reInitFormValidatorParsley(productForm);
    });
    $(".fixDecimal").blur(function () {
        $(this).val($(this).fixDecimal());
    });

    function uniqueBarcode(productId){
        $('.ajaxLoading').show();
        $.ajax({
            type:"POST",
            url:"product/generate-unique-barcode",
            data:{id:productId},
            success:function(response){
                $("#upc_barcode").val(response.barcode);
                $('.ajaxLoading').hide();
            }
        });
        return false;
    }
    $(document).on('ifChecked','.isDefaultExpenseCategoryElm',function(){
        $(this).trigger('change');
    });

    $(document).off('change','.isDefaultExpenseCategoryElm').on('change','.isDefaultExpenseCategoryElm',function(){

        if($(this).is(':checked')) {
            $("input.isDefaultExpenseCategoryElm").each(function(){
                $('#isDefaultExpenseCategory_'+$(this).attr('data-count')).val('0');
            });
            $("input.isDefaultExpenseCategoryElm").not('#'+$(this).attr('id')).iCheck('uncheck');
            $('#isDefaultExpenseCategory_'+$(this).attr('data-count')).val('1');
        }
    });
    /**
     *
     * @param selectedType
     * @param counter
     */
    function setTicketAndRetailFields(selectedType, counter) {
        var form = $('#productFormAjax');
        var sku = 'input[name="sku"]';
        var retail_price = '#retail_input_' + counter;
        var retail_price_div = '#retail_price_' + counter;
        var ticket_input = '#ticket_input_' + counter;
        var ticket_input_div = '#ticket_value_' + counter;
       setTimeout(function(){
           $('input[name="isdefault[]"]:checked').trigger('change');
       },1000);

        if (selectedType == 1 || selectedType == 4 || selectedType == 20) {
            form.parsley().destroy();
            $(sku).removeAttr('required');
            form.parsley();
        }
        else {
            form.parsley().destroy();
            $(sku).attr('required', 'required');
            form.parsley();
        }

        if (selectedType == "8") {
            $(retail_price_div).show(300);
            $(retail_price).attr('required', 'required');
        }
        else {
            $(retail_price_div).hide(300);
            $(retail_price).removeAttr('required');
        }
        if (selectedType == "7") {
            $(ticket_input_div).show(300);
            $(ticket_input).attr('required', 'required');
        }
        else if (selectedType == "8") {
            $(ticket_input_div).show(300);
        }
        else {
            $(ticket_input_div).hide(300);
            $(ticket_input).removeAttr('required');
        }

    }
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
