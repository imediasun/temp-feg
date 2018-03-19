<?php usort($tableGrid, "SiteHelpers::_sort");
$ExpenseCategories = array_map(function ($ExpenseCategories) {
    $dataArray['expense_category'] = $ExpenseCategories->mapped_expense_category;
    $dataArray['expense_category_field'] = $ExpenseCategories->expense_category_field;
    return $dataArray;
}, $ExpenseCategories);  $jsonRowData = json_encode($ExpenseCategories);
?>
<div class="sbox">
    <div class="sbox-title">

        <h5><i class="fa fa-table"></i></h5>
        <div class="sbox-tools">
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search"
               onclick="reloadData('#{{ $pageModule }}','product/data?search=')"><i class="fa fa-trash-o"></i> Clear
                Search </a>
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data"
               onclick="reloadData('#{{ $pageModule }}','product/data?return={{ $return }}')"><i
                        class="fa fa-refresh"></i></a>
            @if(Session::get('gid') == \App\Models\Core\Groups::SUPPER_ADMIN)
                <a href="{{ url('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips"
                   title=" {{ Lang::get('core.btn_config') }}"><i class="fa fa-cog"></i></a>
            @endif
        </div>
    </div>
    <div class="sbox-content">
        @if($setting['usesimplesearch']!='false')
            <?php $simpleSearchForm = SiteHelpers::configureSimpleSearchForm($tableForm); ?>
            @if(!empty($simpleSearchForm))
                <div class="simpleSearchContainer clearfix">
                    @foreach ($simpleSearchForm as $t)
                        <div class="sscol {{ $t['widthClass'] }}" style="{{ $t['widthStyle'] }}">
                            {!! SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) !!}
                            {!! SiteHelpers::transForm($t['field'] , $simpleSearchForm) !!}
                        </div>
                    @endforeach
                    <div class="sscol-submit"><br/>
                        <button type="button" name="search" class="doSimpleSearch btn btn-sm btn-primary"> Search
                        </button>
                    </div>
                </div>
            @endif
        @endif
        @include( $pageModule.'/toolbar',['colconfigs' => SiteHelpers::getRequiredConfigs($module_id),'prod_list_type'=>$product_list_type,'active'=>$active_prod])

        <?php echo Form::open(array('url' => 'product/delete/', 'class' => 'form-horizontal', 'id' => 'SximoTable', 'data-parsley-validate' => ''));?>
        <div class="table-responsive">
            @if(count($rowData)>=1)
                <table class="table table-striped datagrid " id="{{ $pageModule }}Table">
                    <thead>
                    <tr>
                        @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                            <th width="35"> No</th>
                        @endif
                        @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                            <th width="30"><input type="checkbox" class="checkall"/></th>
                        @endif
                        @if($setting['view-method']=='expand')
                            <th></th> @endif
                        <?php foreach ($tableGrid as $t) :
                            if ($t['view'] == '1'):
                                $limited = isset($t['limited']) ? $t['limited'] : '';
                                if (SiteHelpers::filterColumn($limited)) {
                                    $sortBy = $param['sort'];
                                    $orderBy = strtolower($param['order']);
                                    $colField = $t['field'];
                                    $colIsSortable = $t['sortable'] == '1';
                                    $colIsSorted = $colIsSortable && $colField == $sortBy;
                                    $colClass = $colIsSortable ? ' dgcsortable' : '';
                                    $colClass .= $colIsSorted ? " dgcsorted dgcorder$orderBy" : '';
                                    $th = '<th' .
                                            ' class="' . $colClass . '"' .
                                            ' data-field="' . $colField . '"' .
                                            ' data-sortable="' . $colIsSortable . '"' .
                                            ' data-sorted="' . ($colIsSorted ? 1 : 0) . '"' .
                                            ' data-sortedOrder="' . ($colIsSorted ? $orderBy : '') . '"' .
                                            ' style=text-align:' . $t['align'] .
                                            ' width="' . $t['width'] . '"';
                                    $th .= '>';
                                    $th .= \SiteHelpers::activeLang($t['label'], (isset($t['language']) ? $t['language'] : array()));
                                    $th .= '</th>';
                                    echo $th;
                                }
                            endif;
                        endforeach; ?>
                        @if($setting['disablerowactions']=='false')
                            <th width="105"><?php echo Lang::get('core.btn_action');?></th>
                        @endif
                    </tr>
                    </thead>


                    <tbody>
                    @if(($access['is_add'] =='1' || $access['is_edit']=='1' ) && $setting['inline']=='true' )
                        <tr id="form-0" style="display: none">
                            <td> #</td>
                            @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                                <td></td>
                            @endif
                            @if($setting['view-method']=='expand')
                                <td></td> @endif
                            @foreach ($tableGrid as $t)
                                @if(isset($t['inline']) && $t['inline'] =='1')
                                    <?php $limited = isset($t['limited']) ? $t['limited'] : ''; ?>
                                    @if(SiteHelpers::filterColumn($limited ))
                                        <td data-form="{{ $t['field'] }}"
                                            data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
                                            {!! SiteHelpers::transInlineForm($t['field'] , $tableForm) !!}
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                            <td>
                                <button onclick="saved('form-0')" class="btn btn-primary btn-xs" type="button"><i
                                            class="fa  fa-save"></i></button>
                            </td>
                        </tr>
                    @endif

                    <?php
                    $vendor_description= "";
                    $product_id = "";
                    foreach ($rowData as $row) :
                    $id = $row->id;
                    if($vendor_description !=$row->vendor_description){
                        $product_id="product-".$id;
                        $vendor_description =$row->vendor_description;
                    }
                    ?>
                    {{--commented calculateUnitPrice() function call to allow user to edit unit price--}}
                    <tr @if($access['is_edit']=='1' && $setting['inline']=='true' )class="editable"
                        @endif product-id="{!! $product_id !!}"
                        onkeyup="//calculateUnitPrice({{ $row->id }})" id="form-{{ $row->id }}"
                        data-id="{{ $row->id }}"
                        @if($setting['inline']!='false' && $setting['disablerowactions']=='false') @if($access['is_edit']=='1' && $setting['inline']=='true' )ondblclick="showFloatingCancelSave(this); editedProduct('{!! $product_id !!}',this);" @endif @endif>
                        <input type="hidden" name="numberOfItems" value="{{$row->num_items}}"/>
                        <input id="sku-{{ $row->id }}" type="hidden" name="old-sku" value="{{$row->sku}}"/>
                        <input id="vd-{{ $row->id }}" type="hidden" name="old-vd" value="{{$row->vendor_description}}"/>
                        @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                            <td class="number"> <?php echo ++$i;?>  </td>
                        @endif
                        @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                            <td><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id;?>"/></td>
                        @endif
                        @if($setting['view-method']=='expand')
                            <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}"
                                   data-url="{{ url('product/show/'.$id) }}"><i class="fa fa-plus "></i></a></td>
                        @endif
                        <?php foreach ($tableGrid as $field) :
                        if($field['view'] == '1') :
                        $conn = (isset($field['conn']) ? $field['conn'] : array());
                        $value = AjaxHelpers::gridFormater($row->$field['field'], $row, $field['attribute'], $conn, isset($field['nodata']) ? $field['nodata'] : 0);
                        ?>
                        <?php $limited = isset($field['limited']) ? $field['limited'] : ''; ?>
                        @if(SiteHelpers::filterColumn($limited ))
                            <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}"
                                data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">

                                @if($field['field']=='img')
                                    <?php
                                    echo SiteHelpers::showUploadedFile($value, '/uploads/products/', 50, false, $row->id)
                                    ?>
                                @elseif($field['field']=='details')

                                    <?php

                                    $trimValue = preg_replace('/\s+/', ' ', $value);


                                    if (strlen($trimValue) > 20) {

                                        echo substr($trimValue, 0, 20);
                                        echo '<br><a href="javascript:void(0)" onclick="showModal(10,this)">Read more</a>';
                                    } else {


										 	echo $value;
										 }
										 ?>
									 @elseif($field['field']=='is_default_expense_category')

										 <input type='checkbox' name="mycheckbox" @if($value == 1) checked
												@endif data-field="is_default_expense_category" data-size="mini"
												data-animate="true" data-on-text="Yes" data-off-text="No"
												data-handle-width="50px" class="toggle" data-id="{{$row->id}}"
												id="is_default_expense_{{$row->id}}" onSwitchChange="trigger()"/>
                                     @elseif($field['field']=='inactive')
                                         <input type='checkbox' name="mycheckbox" @if($value == "Yes") checked @endif data-field="inactive" data-size="mini" data-animate="true"
												data-on-text="Inactive" data-name="{{ $value }}" data-off-text="Active"
												data-handle-width="50px" class="toggle" data-id="{{$row->id}}"
												id="toggle_trigger_{{$row->id}}" onSwitchChange="trigger()" />
									 @elseif($field['field']=='exclude_export')
										 <input type='checkbox' name="mycheckbox" @if($value == 1) checked  @endif data-field="exclude_export"	data-size="mini" data-animate="true" data-on-text="Yes" data-off-text="No" data-handle-width="50px" class="toggle" data-id="{{$row->id}}" id="exclude_export_{{$row->id}}" onSwitchChange="trigger()" />
                                @else
                                    {!! $value !!}
                                @endif

                            </td>
                        @endif
                        <?php endif;
                        endforeach;
                        ?>
                        <td data-values="action" data-key="<?php echo $row->id;?>">
                            {!! AjaxHelpers::GamestitleButtonAction('product',$access,$id ,$setting) !!}
                            <a href="{{ URL::to('product/upload/'.$row->id)}}" class="tips btn btn-xs btn-white"
                               title="Upload Image"><i class="fa fa-picture-o" aria-hidden="true"></i></a>


                        </td>
                    </tr>


                    @if($setting['view-method']=='expand')
                        <tr style="display:none" class="expanded" id="row-{{ $row->id }}">
                            <td class="number"></td>
                            <td></td>
                            <td></td>
                            <td colspan="{{ $colspan}}" class="data"></td>
                            <td></td>
                        </tr>
                    @endif
                    <?php endforeach;?>

                    </tbody>

                </table>
                @if($setting['inline']!='false' && $setting['disablerowactions']=='false')
                    @foreach ($rowData as $row)
                        {!! AjaxHelpers::buttonActionInline($row->id,'id') !!}
                    @endforeach
                @endif
            @else

                <div style="margin:100px 0; text-align:center;">

                    <p> No Record Found </p>
                </div>

            @endif

        </div>
        <?php echo Form::close();?>
        @include('ajaxfooter',array('product_list_type'=>$product_list_type,'sub_type'=>$sub_type,'active'=>$active_prod))

    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Product Details</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Dismiss</button>

            </div>
        </div>
    </div>
</div>
@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
    var jsonExpenseCategory = [];
    function fixdeci(value) {
        places = 2;
        var val = getFlooredFixed($.trim(value), 5);

        if (val.indexOf('.') == -1) {
            val = val + '.00000';
        }
        val = val + '00';
        val = val.slice(0, (val.indexOf(".")) + 6);
        val = val.split('.');
        var number = 0;
        if (val[1]) {
            var fixed = val[1].substring(0, places);
            var decimalSection = (val[1].substring(places)).rtrim();
            number = val[0] + '.' + fixed + '' + decimalSection;
        } else {
            number = val[0];
        }
        return number;
    }
    <?php if(!empty($jsonRowData)){ ?>
            jsonExpenseCategory = JSON.parse('<?php echo $jsonRowData ?>');
    <?php } ?>
    function setExpenseCategoryName(ID) {
        for (var i = 0; i < jsonExpenseCategory.length; i++) {
            var ExpenseCategory = jsonExpenseCategory[i].expense_category;
            var ExpenseCategoryField = jsonExpenseCategory[i].expense_category_field;
            if (ExpenseCategory > 0 && ExpenseCategory != '') {
                if (ID !== "" && Number(ID) != 0 && ID !== "0") {
                    if (ID == ExpenseCategory) {
                        if (ExpenseCategoryField != '' && ExpenseCategoryField != '0') {
                            return ExpenseCategoryField;
                        } else {
                            return ExpenseCategory;
                        }
                        var ECat = $("tr td[data-field='expense_category'][data-values='" + ExpenseCategory + "'");
                        ECat.text($.trim(ExpenseCategoryField));
                    }
                } else if (ID == 0) {
                    var ECat = $("tr td[data-field='expense_category'][data-values='" + ExpenseCategory + "'");
                    if (ExpenseCategoryField != '' && Number(ExpenseCategoryField) != '0') {
                        ECat.text($.trim(ExpenseCategoryField));
                    }
                }
            }
        }
        return 0;
    }
    $(function () {
        setExpenseCategoryName(0);
    });
    var EditedProductId=0;
    var singleRowObjectId=0;
    function editedProduct(id,singleobject){
        EditedProductId=id;
        singleRowObjectId=$(singleobject).attr("data-id");

    }

    function showModal(id, obj) {
        $('#myModal').modal('show');

        var content = $(obj).parent().attr("data-values");
        $('#myModal .modal-body').text(content);

    }

$(document).ready(function() {
	//$(".sel-search").select2({ width:"100%"});
    $("[id^='toggle_trigger_']").on('switchChange.bootstrapSwitch', function(event, state) {
        productId=$(this).data('id');
        $.ajax(
            {
                type:'POST',
                url:'product/trigger',
                data:{isActive:state,productId:productId},
                success:function(data){
                    if($('select[name="product_list_type"] :selected').val() == 'productsindevelopment' && state == false)
                    {
                        //window.location.reload();
                        $('#form-'+productId).hide(800);
                    }
                    if(data.status == "error"){
                        //notyMessageError(data.message);
                    }
                }
            }
        );
    });
	$("[id^='exclude_export_']").on('switchChange.bootstrapSwitch', function(event, state) {
		productId=$(this).data('id');
		$.ajax(
				{
					type:'POST',
					url:'product/exclude',
					data:{excludeExport:state,productId:productId},
					success:function(data){
						$('.btn.btn-search[data-original-title="Reload Data"]').trigger("click");
						/*if($('select[name="product_list_type"] :selected').val() == 'productsindevelopment' && state == false)
						{
							//window.location.reload();
							$('#form-'+productId).hide(800);
						}
						if(data.status == "error"){
							//notyMessageError(data.message);
						}*/
					}
				}
		);
	});
	$("[id^='is_default_expense_']").on('switchChange.bootstrapSwitch', function (event, state) {
		productId = $(this).data('id');
		console.log(state);
		if (state === true) {
			state = 1;
		} else {
			state = 0;
		}
		$.ajax(
				{
					type: 'POST',
					url: 'product/setdefaultcategory',
					data: {isdefault: state, productId: productId},
					success: function (data) {
						if (data.status == "error") {
							notyMessageError(data.message);
						}
						$('.btn.btn-search[data-original-title="Reload Data"]').trigger("click");
					}
				}
		);
	});

    $("[id^='toggle_trigger_']").bootstrapSwitch( {onColor: 'default', offColor:'primary'});
    $("[id^='exclude_export_']").bootstrapSwitch();
	$("[id^='is_default_expense_']").bootstrapSwitch();
	$('.tips').tooltip();
	$('input[type="checkbox"],input[type="radio"]').not('.toggle').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue'
	});	
	$('#{{ $pageModule }}Table .checkall').on('ifChecked',function(){
		$('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('check');
	});
	$('#{{ $pageModule }}Table .checkall').on('ifUnchecked',function(){
		$('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('uncheck');
	});	
	
	$('#{{ $pageModule }}Paginate .pagination li a').click(function() {
		var url = $(this).attr('href');
		reloadData('#{{ $pageModule }}',url);		
		return false ;
	});

                <?php if ($setting['view-method'] == 'expand') :
                    echo AjaxHelpers::htmlExpandGrid();
                endif;
                ?>

        var simpleSearch = $('.simpleSearchContainer');
        if (simpleSearch.length) {
            initiateSearchFormFields(simpleSearch);
            simpleSearch.find('.doSimpleSearch').click(function (event) {
                performSimpleSearch.call($(this), {
                    moduleID: '#{{ $pageModule }}',
                    url: "{{ $pageUrl }}",
                    event: event,
                    container: simpleSearch
                });
            });
        }

        initDataGrid('{{ $pageModule }}', '{{ $pageUrl }}');
    });


    function calculateUnitPrice(id) {
        var case_price = $('#form-' + id + ' input[name = "case_price"]').val();
        var quantity = $('#form-' + id + ' input[name = "numberOfItems"]').val();
        var unit_price = case_price / quantity;
        if (quantity != 0 && unit_price != 0) {
            $('#form-' + id + ' input[name = "unit_price"]').val(unit_price);
            $('#form-' + id + ' input[name = "unit_price"]').blur();
        }
        else {
            $('#form-' + id + ' input[name = "unit_price"]').val(0.000);
        }

    }

    $('a[data-original-title=Edit]').click(function () {
        $("[id^='toggle_trigger_']").bootstrapSwitch('destroy');
        showAction();
    });

    $('a[data-original-title=View]').click(function () {
        showAction();
    });

    $('a[data-original-title="Upload Image"]').click(function () {
        showAction();
    });

    $(document).ajaxComplete(function (event, xhr, settings) {

        var $urlArray = settings.url.split('/');
//console.log("index Of:"+(settings.url).indexOf('product/data'));
        if ((settings.url).indexOf('product/data')) {

        }

        if (typeof($urlArray[2]) != "undefined" && $urlArray[2] !== null) {
            if (settings.url === "product/save/" + $urlArray[2]) {
                singleRowObjectId=$urlArray[2];
                settings.url = "";

                EditedProductId = $("tr#form-"+$urlArray[2]).attr("product-id");


                var responsetext = JSON.parse(xhr.responseText)
                if(responsetext.message!=='A product with same Product Type & Sub Type already exist' && responsetext.status !=='error'){
                    var mainRow = $('#form-' + $urlArray[2]);
                    var detailText = mainRow.children('td[data-field="details"]').text();
                    if (detailText.length >= 20) {
                        var new_details = detailText.substr(0, 20) + '<br><a href="javascript:void(0)" onclick="showModal(10,this)">Read more</a>';
                        mainRow.children('td[data-field="details"]').empty();
                        mainRow.children('td[data-field="details"]').html(new_details);
                    }
                    var old_sku = $('#sku-' + $urlArray[2]).val();
                    var old_vd = $('#vd-' + $urlArray[2]).val();
                    var count = 1;
                    $("tr[product-id='"+EditedProductId+"']").each(function (key, row) {
                        row = $(row);
                        if (row.attr('id') != undefined) {
                            //divOverlay_6442
                         //   console.log($("#divOverlay_"+idSplited[1]).children('a[data-original-title="Cancel"]').click())
                          //  cancelInlineEdit("'"+row.attr('id')+"'", event, this,0)
                            if (1==1) {
                                var requestdata = decodeURIComponent(settings.data);
                                var requestArray = requestdata.split("&");
                                //	console.log(requestArray);
                                for (var i = 0; i < requestArray.length; i++) {
                                    var requestElement = (requestArray[i]).split("=");
                                    var key = $.trim(requestElement[0]);
                                    var value = (requestElement[1]).replace(/\+/g, " ");
                                    // console.log(key + " : " + value);
                                    var idSplited = (row.attr('id')).split("-");
                                    if(key=="expense_category") {
                                       // console.log(row.attr("data-id") === singleRowObjectId);
                                    }
                                    if (key == "expense_category") {
                                        value = setExpenseCategoryName($.trim(value));
                                    }

                                //    if(idSplited[1]$urlArray[2]) {

                                        $("#divOverlay_" + idSplited[1]).children('a[data-original-title="Cancel"]').click();
                                 //   }
                                   // console.log(key+" = "+value);

                                    if (key == "unit_price" && value > 0) {
                                        value = "$ " + fixdeci(value);

                                    }
                                    if (key == "retail_price" && value > 0) {
                                        value = "$ " + fixdeci(value);
                                    }
                                    if (key == "case_price" && value > 0) {
                                        value = "$ " + fixdeci(value);
                                    }
                                    if((key == "hot_item" || key == "is_reserved") && value===""){
                                        value="No Data";
                                    }
                                    if (key == "is_reserved" && value == 0) {
                                        value = "No";
                                    } else if (key == "is_reserved" && value == 1) {
                                        value = "Yes";
                                    }
                                    //hot_item

                                    if (key == "hot_item" && value == 0) {
                                        value = "No";
                                    } else if (key == "hot_item" && value == 1) {
                                        value = "Yes";
                                    }

                                    if (key == "vendor_id" && value !== '' && value > 0) {
                                        value = $("select#vendor_id option[value='" + value + "']").text()
                                    }
                                    if (key == "prod_type_id" && value !== '' && value > 0) {
                                        value = $("select.prod_type_id option[value='" + value + "']").eq(0).text()
                                    }
                                    if (key == "prod_sub_type_id" && value !== '' && value > 0) {
                                        value = $("select#prod_sub_type_id option[value='" + value + "']").text()
                                    }
                                    if (value == '' || value == '0') {
                                        value = "No Data";
                                    }



                                        if (key !== "mycheckbox") {

                                            if (key == "prod_type_id" || key == "prod_sub_type_id") {
                                                if (row.attr('data-id') == $urlArray[2]) {
                                                    row.find('td[data-field="' + key + '"]').text($.trim(value));
                                                }
                                            } else {
                                                if ((key === "ticket_value" || key === "retail_price")) {

                                                    if (row.attr("data-id") === singleRowObjectId) {

                                                        row.find('td[data-field="' + key + '"]').text($.trim(value));
                                                    }
                                                } else {
                                                    if(key=="details" && $.trim(value).length>19){
                                                        row.find('td[data-field="' + key + '"]').attr({"data-values":$.trim(value),"data-format":$.trim(value)});
                                                        value  = $.trim(value).slice(0,19)+'<br><a href="javascript:void(0)" onclick="showModal(10,this)">Read more</a>';
                                                        row.find('td[data-field="' + key + '"]').html(value);
                                                    }else {
                                                        row.find('td[data-field="' + key + '"]').text($.trim(value));
                                                    }
                                                }
                                            }


                                        }
                                }

                                //console.log($("select[name='vendor_id'] option:selected").text());
                                //	row.find('td[data-field="vendor_description"]').text($.trim(mainRow.children('td[data-field="vendor_description"]').text()));
                                //	row.find('td[data-field="sku"]').text($.trim(mainRow.children('td[data-field="sku"]').text()));
                                //expense_category
                                //row.find('td[data-field="expense_category"]').text($.trim(mainRow.children('td[data-field="expense_category"]').attr("data-format")));
                                //	row.find('td[data-field="vendor_id"]').text($.trim(mainRow.children('td[data-field="vendor_id"]').attr("data-format")));

                                //row.find('td[data-field="item_description"]').text($.trim(mainRow.children('td[data-field="item_description"]').text()));
                                //row.find('td[data-field="size"]').text($.trim(mainRow.children('td[data-field="size"]').text()));
                                //row.find('td[data-field="unit_price"]').text($.trim(mainRow.children('td[data-field="unit_price"]').text()));
                                //row.find('td[data-field="case_price"]').text($.trim(mainRow.children('td[data-field="case_price"]').text()));
                                //row.find('td[data-field="details"]').text($.trim(mainRow.children('td[data-field="details"]').text()));
                                //row.find('td[data-field="hot_item"]').text($.trim(mainRow.children('td[data-field="hot_item"]').text()));
                                //	row.find('td[data-field="reserved_qty"]').text($.trim(mainRow.children('td[data-field="reserved_qty"]').text()));
                                //row.find('td[data-field="is_reserved"]').text($.trim(mainRow.children('td[data-field="is_reserved"]').text()));
                                //	$('#vd-'+$urlArray[2]).val($.trim(mainRow.children('td[data-field="vendor_description"]').text()));
                                //	$('#sku-'+$urlArray[2]).val($.trim(mainRow.children('td[data-field="sku"]').text()));
                            }
                        }
                    });
                }
            }
        }
    });
    $(document).on("blur", "input[name='case_price']", function () {
        $(this).val($(this).fixDecimal());
    });

    $(document).on("keyup change", "input[name='case_price']", function () {
        calculateUnitPrice($(this).parents('tr').data('id'));
    });

    $(document).on("blur", "input[name='unit_price']", function () {
        $(this).val($(this).fixDecimal());
    });

    $(document).on("blur", "input[name='retail_price']", function () {
        $(this).val($(this).fixDecimal());
    });

    $(function(){

        $.ajax({
            type:"GET",
            data:{DATATEST:1},
            dataType:"HTML",
            url:'product/expense-category-ajax',
            success:function(response){
                // console.log(response);
                $(".expense_category").html(response);
                $(".expense_category").change();
            },
            error:function(res){
                console.log(res);
            }
        });
    });

</script>

<style>
    .table th.right {
        text-align: right !important;
    }

    .table th.center {
        text-align: center !important;
    }

    .btn-imagee {

        font-size: 10px;
        padding: 7px 11px;
        border: 1px solid transparent;
        border-radius: 0px;
        background-color: #428bca;
        border-color: #2a6496;
        white-space: nowrap;
        font-family: 'Lato', sans-serif;
    }

</style>
