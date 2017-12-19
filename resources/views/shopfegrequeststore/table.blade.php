<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
    <div class="sbox-title">
        <h5><i class="fa fa-table"></i></h5>

        <div class="sbox-tools">
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search"
               onclick="reloadData('#{{ $pageModule }}','shopfegrequeststore/data?search=')"><i
                        class="fa fa-trash-o"></i> Clear Search </a>
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data"
               onclick="reloadData('#{{ $pageModule }}','shopfegrequeststore/data?type=store&active_inactive=active&return={{ $return }}')"><i
                        class="fa fa-refresh"></i></a>
            @if(Session::get('gid') == \App\Models\Core\Groups::SUPPER_ADMIN)
                <a href="{{ url('feg/module/config/'.$pageModule) }}" 
                   class="btn btn-xs btn-white tips openModuleConfig"
                   title=" {{ Lang::get('core.btn_config') }}"><i class="fa fa-cog"></i></a>
    			<a href="{{ url('feg/module/special-permissions/'.$pageModule.'/solo') }}" 
                   class="btn btn-xs btn-white tips openSpecialPermissions"
                   title="Special Permissions"
                   ><i class="fa fa-sliders"></i></a>

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
                    {!! SiteHelpers::generateSimpleSearchButton($setting) !!}
                </div>
            @endif
        @endif
        @include( $pageModule.'/toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id),'order_type'=>$order_type,'product_type' => $product_type,'cart'=>$cart])

        <?php echo Form::open(array('url' => 'shopfegrequeststore/delete/', 'class' => 'form-horizontal', 'id' => 'SximoTable', 'data-parsley-validate' => ''));?>
        <div class="table-responsive">
            @if(count($rowData)>=1)
                <table class="table table-striped  datagrid" id="{{ $pageModule }}Table">
                    <thead>
                    <tr>
                        @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                            <th width="35"> No </th>
                        @endif
                            @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                            <th width="30"> <input type="checkbox" class="checkall" /></th>
                        @endif
                        @if($setting['view-method']=='expand') <th>  </th> @endif
                        <?php foreach ($tableGrid as $t) :
                            if($t['view'] =='1'):
                                $limited = isset($t['limited']) ? $t['limited'] :'';
                                if(SiteHelpers::filterColumn($limited ))
                                {
                                    $sortBy = $param['sort'];
                                    $orderBy = strtolower($param['order']);
                                    $colField = $t['field'];
                                    $colIsSortable = $t['sortable'] == '1';
                                    $colIsSorted = $colIsSortable && $colField == $sortBy;
                                    $colClass = $colIsSortable ? ' dgcsortable' : '';
                                    $colClass .= $colIsSorted ? " dgcsorted dgcorder$orderBy" : '';
                                    $th = '<th'.
                                            ' class="'.$colClass.'"'.
                                            ' data-field="'.$colField.'"'.
                                            ' data-sortable="'.$colIsSortable.'"'.
                                            ' data-sorted="'.($colIsSorted?1:0).'"'.
                                            ' data-sortedOrder="'.($colIsSorted?$orderBy:'').'"'.
                                            ' style=text-align:'.$t['align'].
                                            ' width="'.$t['width'].'"';
                                    $th .= '>';
                                    $th .= \SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array()));
                                    $th .= '</th>';
                                    echo $th;
                                }
                            endif;
                        endforeach; ?>
                            @if($setting['disablerowactions']=='false')
                                <th width="75"><?php echo Lang::get('core.btn_action') ;?></th>
                            @endif
                            <th width="130">Add To Cart</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if($access['is_add'] =='1' && $setting['inline']=='true')
                        <tr id="form-0">
                            <td> #</td>
                            @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                                <td> </td>
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

                    <?php foreach ($rowData as $row) :
                    $id = $row->id;
                    ?>
                    <tr class="editable" id="form-{{ $row->id }}" @if($setting['inline']!='false') data-id="{{ $row->id }}" ondblclick="showFloatingCancelSave(this,1)" @endif>
                        @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                            <td class="number"> <?php echo ++$i;?>  </td>
                        @endif
                            @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                            <td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
                        @endif
                        @if($setting['view-method']=='expand')
                            <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}"
                                   data-url="{{ url('shopfegrequeststore/show/'.$id) }}"><i class="fa fa-plus "></i></a>
                            </td>
                        @endif
                        <?php foreach ($tableGrid as $field) :
                        if($field['view'] == '1') :
                        $conn = (isset($field['conn']) ? $field['conn'] : array());
                        $value = AjaxHelpers::gridFormater($row->$field['field'], $row, $field['attribute'], $conn,isset($field['nodata'])?$field['nodata']:0);
                        ?>
                        <?php $limited = isset($field['limited']) ? $field['limited'] : ''; ?>
                        @if(SiteHelpers::filterColumn($limited ))
                            <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}"
                                data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
                                @if($field['field']=='img')
                                    <?php
                                    echo SiteHelpers::showUploadedFile($value, '/uploads/products/', 50, false);
                                    ?>
                                @else
                                    {!! $value !!}
                                @endif
                            </td>
                        @endif
                        <?php
                        endif;
                        endforeach;
                        ?>
                            @if($setting['disablerowactions']=='false')
                                <td data-values="action" data-key="<?php echo $row->id ;?>">
                                    {!! AjaxHelpers::buttonAction('shopfegrequeststore',$access,$id ,$setting) !!}
                                </td>
                            @endif
                        <td>@if($row->inactive == 0 && $row->vendor_hide == 0 && $row->vendor_status == 1)
                                <input type="number" title="Quantity" value="1" min="1" onkeyup="if(!this.checkValidity()){this.value='';alert('Please Enter a Non Zero Number')};" name="item_quantity" class="form-control" style="width:70px;display:inline" id="item_quantity_{{$row->id}}" min="0"  />
                                <a href="javascript:void(0)" value="{{$row->id}}" class=" addToCart tips btn btn-sm btn-white pull-right"  title="Add to Cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                            @else
                                Not Avail.
                            @endif</td>

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
                @if($setting['inline']!='false')
                    @foreach ($rowData as $row)
                        {!! AjaxHelpers::buttonActionInline($row->id,'id',1) !!}
                    @endforeach
                @endif
            @else

                <div style="margin:100px 0; text-align:center;">

                    <p> No Record Found </p>
                </div>

            @endif

        </div>
        <?php echo Form::close();?>
        @include('ajaxfooter',array('isactive'=>$active_inactive,'order_type'=>$order_type,'product_type'=>$product_type,'type'=>$type))

    </div>
</div>

@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
    $(document).ready(function () {
        $('.tips').tooltip();
        $('input[type="checkbox"],input[type="radio"]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
        $('#{{ $pageModule }}Table .checkall').on('ifChecked', function () {
            $('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('check');
        });
        $('#{{ $pageModule }}Table .checkall').on('ifUnchecked', function () {
            $('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('uncheck');
        });

        $('#{{ $pageModule }}Paginate .pagination li a').click(function () {
            var url = $(this).attr('href');
            reloadData('#{{ $pageModule }}', url);
            return false;
        });
        $("[name^=item_]").on('keyup',function(){
            if(!$(this).val())
            {
               $(this).next('a').attr('disabled',true);
            }
            else
            {
                $(this).next('a').attr('disabled',false);
            }
        });

        $('.addToCart').on('click',function(){
            var base_url = <?php echo  json_encode(url()) ?>;
            var addId = $(this).attr('value');
            var qty=$("#item_quantity_"+addId).val();

            if(!qty)
            {

                $(this).next('.qty-error').show();
                return false;
            }
            else {
                $.ajax({
                    type: "GET",
                    url: base_url + '/shopfegrequeststore/popup-cart/' + addId + "/" + qty,
                    data: {},
                    success: function (response) {
                        $("#update_text_to_add_cart").text(response.total_cart);
                        showResponse(response);
                        getCartTotal();
                    }
                });
            }
        });
        function showResponse(data)  {

            if(data.status == 'success')
            {
                notyMessage(data.message);

            } else {
                notyMessageError(data.message);
                return false;
            }
        }
    <?php if($setting['view-method'] =='expand') :
                echo AjaxHelpers::htmlExpandGrid();
            endif;
         ?>
        var simpleSearch = $('.simpleSearchContainer');
        if (simpleSearch.length) {
            initiateSearchFormFields(simpleSearch);
            simpleSearch.find('.doSimpleSearch').click(function(event){
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
    
    App.autoCallbacks.registerCallback('beforeclearsearch', function (params) {
        //params.data.include
        //params.data.exclue
        //params.data.force
        //params.data.blind
        params.data.exclude['order_type'] = true;
        params.data.exclude['product_type'] = true;
        params.data.force['type'] = 'store';
        params.data.force['active_inactive'] = 'active';
        
    });
    console.log('debug me');
    function calculateUnitPrice(id){
        var case_price = $('#form-'+id+' input[name = "case_price"]').val();
        var quantity = $('#form-'+id+' input[name = "num_items"]').val();
        var unit_price = case_price/quantity;
        if(quantity != 0 && unit_price != 0) {
            $('#form-'+id+' input[name = "unit_price"]').val(unit_price);
            $('#form-'+id+' input[name = "unit_price"]').blur();
        }
        else
        {
            $('#form-'+id+' input[name = "unit_price"]').val(0.000);
        }

    }
    var executeonce = true;
    $( document ).ajaxComplete(function( event, xhr, settings ) {
        console.log(settings);
        var $urlArray = settings.url.split('/');
        console.log($urlArray);
        $('tr td[data-field="expense_category"]').each(function () {
            var ids = $.trim($(this).text());
            ids = ids.trim();
            if (ids !== "No Data") {
                ids = ids.split(" ");
                $(this).text(Number(ids[0]));

            }
        });

        if($('#field_expense_category select[name="expense_category"]').length){
            $.ajax({
                type:"GET",
                data:{DATATEST:1},
                dataType:"HTML",
                url:'product/expense-category-ajax',
                success:function(response){
                    if(executeonce==true) {
                        $(this).html(response);
                        $(this).change();
                        executeonce=false;
                    }
                },
                error:function(res){

                }
            });
        }
    });


    $(function(){

        $.ajax({
            type:"GET",
            data:{DATATEST:1},
            dataType:"HTML",
            url:'product/expense-category-ajax',
            success:function(response){

                $(".expense_category").html(response);
                $(".expense_category").change();
            },
            error:function(res){

            }
        });
    });
    $(document).on("blur", "input[name='case_price']", function () {
        $(this).val($(this).fixDecimal());
    });

    $(document).on("keyup change", "input[name='case_price']", function () {
        calculateUnitPrice($(this).parents('tr').data('id'));
    });

    $(document).on("keyup", "input[name='num_items']", function () {
        calculateUnitPrice($(this).parents('tr').data('id'));
    });

    $(document).on("blur", "input[name='unit_price']", function () {
        $(this).val($(this).fixDecimal());
    });

    $(document).on("blur", "input[name='retail_price']", function () {
        $(this).val($(this).fixDecimal());
    });
</script>
<style>
    .table th.right {
        text-align: right !important;
    }

    .table th.center {
        text-align: center !important;
    }

</style>
