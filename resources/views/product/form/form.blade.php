{!! Form::open(array('url'=>$actionUrl, 'class'=>'form-horizontal','files' => true ,'parsley-validate'=>'','novalidate'=>' ','id'=> 'productFormAjax')) !!}
<div class="col-md-12">
    <fieldset>
        @if(!empty($rowId))
            <input type="hidden" value="{{ $rowId }}" name="rowId">
        @endif
        <div class="form-group  ">
            <label for="Item Name" class=" control-label col-md-4 text-left">
                {!! SiteHelpers::activeLang('Item Name', (isset($fields['vendor_description']['language'])?
                $fields['vendor_description']['language'] : array())) !!}
            </label>

            <div class="col-md-6">
                <?php
                $fieldMeta = ['class' => 'form-control', 'placeholder' => '',
                        'required' => 'required',];
                if (!empty($fromOrder)) {
                    $fieldMeta['readonly'] = '';
                }
                ?>
                {!! Form::text('vendor_description', $row['vendor_description'],$fieldMeta) !!}
            </div>
            <div class="col-md-2">

            </div>
        </div>
        <div class="form-group  ">
            <label for="SKU" class=" control-label col-md-4 text-left">
                {!! SiteHelpers::activeLang('SKU', (isset($fields['sku']['language'])?
                $fields['sku']['language'] : array())) !!}
            </label>

            <div class="col-md-6">
                <input type="text" name="sku" id="sku" @if (!empty($fromOrder)) readonly @endif  value="{{$row['sku']}}"
                       class="form-control"
                       @if(!(in_array($row['prod_type_id'], [1,4,20]))) required='required' @endif>
            </div>
            <div class="col-md-2">

            </div>
        </div>
        <div class="form-group  ">
            <label for="UPC/Barcode" class=" control-label col-md-4 text-left">
                {!! SiteHelpers::activeLang('UPC/Barcode', (isset($fields['upc_barcode']['language'])?
                $fields['upc_barcode']['language'] : array())) !!}
            </label>

            <div class="col-md-4">
                <input type="text" name="upc_barcode" id="upc_barcode" value="{{$row['upc_barcode']}}"
                       class="form-control">
            </div>
            <div class="col-md-2">
                <span onclick="uniqueBarcode('{{ $row['id'] }}');" class="btn btn-primary">Generate UPC/Barcode</span>
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
        <div class="form-group  ">
            <label for="Case Price" class=" control-label col-md-4 text-left">

                {!! SiteHelpers::activeLang('Case Price', (isset($fields['case_price']['language'])?
                $fields['case_price']['language'] : array())) !!}
            </label>

            <div class="col-md-6">
                <div class="input-group ig-full">
                    <?php
                    $fieldMeta = array('class' => 'form-control fixDecimal',
                            'placeholder' => '0.00', 'required' => 'required', 'type' => 'number', 'parsley-min' => '0', 'step' => '1', 'id' => 'case_price_input');
                    if (!empty($fromOrder)) {
                        $fieldMeta['readonly'] = '';
                    } ?>
                    <span class="input-group-addon">$</span>
                    {!! Form::text('case_price',
                    $row['case_price'] == ''?'':(double)$row['case_price'],$fieldMeta)
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
                    <?php
                    $fieldMeta = array('class' => 'form-control fixDecimal',
                            'placeholder' => '0.00', 'required' => 'required', 'type' => 'number', 'parsley-min' => '0', 'step' => '1', 'id' => 'unit_price_input');
                    if (!empty($fromOrder)) {
                        $fieldMeta['readonly'] = '';
                    } ?>
                    <span class="input-group-addon">$</span>
                    {!! Form::text('unit_price',
                    $row['unit_price'] == ''?'':(double)$row['unit_price'],$fieldMeta)
                    !!}
                </div>
            </div>
            <div class="col-md-2">

            </div>
        </div>
        <?php
        $variationCount = 1;
        ?>
        @if(count($variations) > 0)
            @foreach($variations as $variation)

                @if($variationCount == 1)

                    <span class="product_types">
                                <input type="hidden" name="itemId[]" value="{{ $variation['id'] }}">
                    <div class="form-group  ">
                        <label for="Prod Type Id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Product Type', (isset($fields['prod_type_id']['language'])?
                            $fields['prod_type_id']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">

                            <select name='prod_type_id[]' data-previous="{{$row['prod_type_id']}}" rows='5'
                                    data-counter="1" id='prod_type_id_1' class='select2 prod_type'
                                    required='required'>
                                <option value="">--select--</option>
                                @foreach($productTypes as $productType)
                                    <option @if($variation['prod_type_id'] == $productType['id']) selected
                                            @endif value="{{ $productType['id'] }}">{{ $productType['order_type'] }}</option>
                                @endforeach
                            </select>

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
                            <select name='prod_sub_type_id[]' data-counter="1" rows='5' id='prod_sub_type_id_1'
                                    class='select2 prod_sub_type'></select>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Expense Category" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Expense Category', (isset($fields['expense_category']['language'])? $fields['expense_category']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <select name='expense_category[]' rows='5' id='expense_category_1' class='select2'
                                    required></select>
                            {{--<input class="form-control" placeholder="" parsley-type="number" required="required" id="expense_category_1" name="expense_category[1]" type="text" value="{{$row['expense_category']}}">--}}
                        </div>
                        <div class="col-md-2">
                           {{-- @if(!empty($variation['id']))--}}
                                <?php
                                $disabledcheckbox = '';
                                if ($variation['is_default_expense_category']) {
                                    $disabledcheckbox = 'disabled="disabled"';
                                }
                                ?>
                                <label class='checked checkbox-inline'>
                                    <input type="hidden" id="isDefaultExpenseCategory_{{ $variationCount }}"
                                           name="is_default_expense_category[]" value="{{ (count($variations) <= 1) ? '1':'0' }}">
                                    <input type='checkbox' data-count="{{ $variationCount }}"
                                           value='1' name="isdefault[]" class='isDefaultExpenseCategoryElm'
                                           id="isDefaultExpenseCategoryElm_{{ $variationCount }}"
                                           @if($variation['is_default_expense_category']==1 || count($variations) <= 1) checked @endif /> Make Default</label>
                           {{-- @endif--}}
                        </div>
                    </div>
                    <div class="form-group" id="retail_price_{{ $variationCount }}" style="display: none;">
                        <label for="Retail Price" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Retail Price', (isset($fields['retail_price']['language'])?
                            $fields['retail_price']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <div class="input-group ig-full">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('retail_price[]',
                                $variation['retail_price'] == ''?'':(double)$variation['retail_price'],array('class'=>'form-control',
                                'placeholder'=>'0.00','type'=>'number','parsley-min' => '0','step'=>'1','id'=>'retail_input_'.$variationCount )) !!}
                            </div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  " id="ticket_value_{{ $variationCount }}" style="display: none;">
                        <label for="Ticket Value" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])?
                            $fields['ticket_value']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::text('ticket_value[]', $variation['ticket_value'],array('class'=>'form-control',
                            'placeholder'=>'','id'=>'ticket_input_'.$variationCount)) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    </span>

                    <span id="more_types_container">
                        @else
                            <span class="product_types productTypeBox" id="remove_me_{{ $variationCount }}">
                                        <input type="hidden" name="itemId[]" value="{{ $variation['id'] }}">
                                        <div class="form-group  ">
                <label for="Prod Type Id" class=" control-label col-md-4 text-left">
                    {!! SiteHelpers::activeLang("Product Type", (isset($fields["prod_type_id"]["language"])? $fields["prod_type_id"]["language"] : array())) !!}
                </label>
                <div class="col-md-6">
                    <select data-previous="0" name="prod_type_id[]" rows="5"
                            data-counter="{{ $variationCount }}"
                            id="prod_type_id_{{ $variationCount }}" class="prod_type select2 "
                            required="required">
                        @foreach($productTypes as $productType)
                            <option @if($variation['prod_type_id'] == $productType['id']) selected
                                    @endif value="{{ $productType['id'] }}">{{ $productType['order_type'] }}</option>
                        @endforeach
                    </select>
                </div>
                                            <div class="col-md-2">
                                                <button data-remove-id="{{ $variation['id'] }}"
                                                        data-count="{{ $variationCount }}"
                                                        class="remove_me pull-right btn btn-xs btn-danger"><i
                                                            class="fa fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="form-group  ">
                <label for="Prod Sub Type Id" class=" control-label col-md-4 text-left">
                    {!! SiteHelpers::activeLang("Product Subtype",(isset($fields["prod_sub_type_id"]["language"])? $fields["prod_sub_type_id"]["language"] : array())) !!}
                </label>
                <div class="col-md-6">
                    <select name="prod_sub_type_id[]" rows="5" data-counter="{{ $variationCount }}"
                            id="prod_sub_type_id_{{ $variationCount }}" class="prod_sub_type select2 "></select>
                </div>
                                            <div class="col-md-2">
                                            </div>
                                        </div>
                <div class="form-group">
                    <label for="Expense Category" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang("Expense Category", (isset($fields["expense_category"]["language"])? $fields["expense_category"]["language"] : array())) !!}
                    </label>
                <div class="col-md-6">
                    <select name="expense_category[]" rows="5"
                            id="expense_category_{{ $variationCount }}" class="select2"
                            required></select>
                </div>
                    <div class="col-md-2">

                        {{-- @if(!empty($variation['id']))--}}
                            <?php

                            $disabledcheckbox = '';
                            if ($variation['is_default_expense_category'] == 1) {
                                $disabledcheckbox = 'disabled="disabled"';
                            }
                            ?>
                            <label class='checked checkbox-inline'>
                                <input type="hidden" id="isDefaultExpenseCategory_{{ $variationCount }}"
                                       name="is_default_expense_category[]" value="0">

                                    <input type='checkbox' data-count="{{ $variationCount }}"
                                           class="isDefaultExpenseCategoryElm"
                                           value='1' name="isdefault[]"
                                           id="isDefaultExpenseCategoryElm_{{ $variationCount }}"
                                           @if($variation['is_default_expense_category']==1) checked @endif /> Make Default</label>
                        {{--@endif--}}

                    </div>
                </div>
                <div class="form-group" id="retail_price_{{ $variationCount }}" style="display: none;">
                        <label for="Retail Price" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Retail Price', (isset($fields['retail_price']['language'])?
                            $fields['retail_price']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <div class="input-group ig-full">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('retail_price[]',
                                $variation['retail_price'] == ''?'':(double)$variation['retail_price'],array('class'=>'form-control',
                                'placeholder'=>'0.00','type'=>'number','parsley-min' => '0','step'=>'1','id'=>'retail_input_'.$variationCount )) !!}
                            </div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  " id="ticket_value_{{ $variationCount }}" style="display: none;">
                        <label for="Ticket Value" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])?
                            $fields['ticket_value']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::text('ticket_value[]', $variation['ticket_value'],array('class'=>'form-control',
                            'placeholder'=>'','id'=>'ticket_input_'.$variationCount)) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                </span>
                        @endif
                        <?php
                        $variationCount++;
                        ?>
                        @endforeach
                            </span>
                @else
                    <span class="product_types">
                                    <input type="hidden" name="itemId[]" value="{{ $row['id'] }}">
                    <div class="form-group  ">
                        <label for="Prod Type Id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Product Type', (isset($fields['prod_type_id']['language'])?
                            $fields['prod_type_id']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">

                            <select name='prod_type_id[]' data-previous="{{$row['prod_type_id']}}" rows='5'
                                    data-counter="1" id='prod_type_id_1' class='select2 prod_type'
                                    required='required'>
                                <option value="">--select--</option>
                                @foreach($productTypes as $productType)
                                    <option @if($row['prod_type_id'] == $productType['id']) selected
                                            @endif value="{{ $productType['id'] }}">{{ $productType['order_type'] }}</option>
                                @endforeach
                            </select>

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
                            <select name='prod_sub_type_id[]' data-counter="1" rows='5' id='prod_sub_type_id_1'
                                    class='select2 prod_sub_type'></select>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Expense Category" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Expense Category', (isset($fields['expense_category']['language'])? $fields['expense_category']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <select name='expense_category[]' rows='5' id='expense_category_1' class='select2'
                                    required></select>
                        </div>
                        <div class="col-md-2">
                            @if(!empty($id))
                                <?php
                                $product = new \App\Models\product();
                                $products = $product->checkProducts($id);
                                $disabledcheckbox = '';
                                if (count($products) > 1 && $row['is_default_expense_category']) {
                                    $disabledcheckbox = 'disabled="disabled"';
                                }
                                ?>
                                <label class='checked checkbox-inline'>
                                    <input type="hidden" id="isDefaultExpenseCategory_1"
                                           name="is_default_expense_category[]"
                                           value="{!! (count($variations) <= 1) ? '1':'0' !!}"/>
                                    <input type='checkbox' name="isdefault[]" class="isDefaultExpenseCategoryElm"
                                           id="isDefaultExpenseCategoryElm_1"
                                           value='1' data-count="1"
                                           @if($row['is_default_expense_category']==1 || count($variations) <= 1) checked @endif /> Make Default</label>
                            @endif
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
                                {!! Form::text('retail_price[]',
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
                            {!! Form::text('ticket_value[]', $row['ticket_value'],array('class'=>'form-control',
                            'placeholder'=>'','id'=>'ticket_input_1')) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    </span>

                    <span id="more_types_container"></span>
                @endif

                <input type="hidden" id="removedItemIds" name="removedItemIds" value=""/>
                <a id="add_more_types" class="btn btn-primary pull-right">Add More</a>

                <div class="form-group clearfix">
                    <label for="vendor_id" class="control-label col-md-4 text-left">
                        Vendor </label>

                    <div class="col-md-6">
                        @if (!empty($fromOrder))
                            <input type="hidden" name="vendor_id" value="{{ $row['vendor_id'] }}"
                                   style="display: none;">
                        @endif
                        <select name='vendor_id' productVendor='1' @if (!empty($fromOrder)) disabled
                                style="cursor: not-allowed" @endif data-seprate='true' id='vendor_id' class='select2'
                                required></select>
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
                        {!! SiteHelpers::activeLang('Reserved Quantity', (isset($fields['reserved_qty']['language'])?
                        $fields['reserved_qty']['language'] : array())) !!}
                    </label>

                    <div class="col-md-6">
                        {!! Form::text('reserved_qty', $row['reserved_qty'],array('class'=>'form-control',
                        'placeholder'=>'','reserved-quantity'=> !empty($row['reserved_qty']) ? $row['reserved_qty']:0)) !!}
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div class="form-group">
                    <label for="allow_negative_reserve_qty" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('Allow Negative Reserved Qty', (isset($fields['allow_negative_reserve_qty']['language'])?
                        $fields['is_reserved']['language'] : array())) !!}
                    </label>

                    <div class="col-md-6 check-no">
                        <?php $allow_negative_reserve_qty = explode(",", $row['allow_negative_reserve_qty']); ?>
                        <label class='checked checkbox-inline'>
                            <input type="hidden" name="allow_negative_reserve_qty" value="0"/>
                            <input type='checkbox' name='allow_negative_reserve_qty' value='1' class=''
                                   @if(in_array('1',$allow_negative_reserve_qty))checked @endif
                            /> </label>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>




                <div class="form-group">
                    <label for="Reserved Qty Limit" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('Reserved Quantity Par Amount', (isset($fields['reserved_qty_limit']['language'])?
                        $fields['reserved_qty_limit']['language'] : array())) !!}
                    </label>

                    <div class="col-md-6">
                        {!! Form::text('reserved_qty_limit', $row['reserved_qty_limit'],array('class'=>'form-control',
                        'placeholder'=>'', )) !!}
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div class="form-group">
                    <label for="Excluded Locations and Groups" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('Excluded Locations and Groups', (isset($fields['excluded_locations_and_groups']['language'])?
                        $fields['excluded_locations_and_groups']['language'] : array())) !!}
                    </label>

                    <div class="col-md-6">
                        <select name='excluded_locations_and_groups[]' data-seprate='true'
                                id='excluded_locations_and_groups' class='select2' multiple></select>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>


                <div class="form-group  ">
                    <label for="Img" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('Image', (isset($fields['img']['language'])?
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
            {!! SiteHelpers::activeLang('Exclude from Export', (isset($fields['exclude_export']['language'])?
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
                    class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }} </button>
        <?php
            $itemFormView = '';
        if(empty($itemForm)){
            $itemFormView = '';
        }else{
            $itemFormView = $itemForm;
        }
        ?>
        <button type="button" onclick="ajaxViewClose('#{{ $pageModule.$itemFormView }}'); " class="btn btn-success btn-sm cancelOrderProduct">
            <i class="fa  fa-arrow-circle-left "></i> {{ Lang::get('core.sb_cancel') }} </button>
    </div>
</div>
{!! Form::close() !!}