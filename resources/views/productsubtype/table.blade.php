<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<style>
    ul.list-group.list-group-striped li:nth-of-type(even){
        background: white !important;
    }
    ul.list-group.list-group-striped li:nth-of-type(odd){
        background: lightgray !important;
    }
</style>
<div class="sbox" xmlns="http://www.w3.org/1999/html">
    <div class="sbox-title">
        <h5><i class="fa fa-table"></i></h5>
        <div class="sbox-tools">
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search"
               onclick="reloadData('#{{ $pageModule }}','productsubtype/data?search=')"><i class="fa fa-trash-o"></i>
                Clear Search </a>
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data"
               onclick="reloadData('#{{ $pageModule }}','productsubtype/data?return={{ $return }}')"><i
                        class="fa fa-refresh"></i></a>
            @if(Session::get('gid') ==  \App\Models\Core\Groups::SUPPER_ADMIN)
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
                            {!! SiteHelpers::transForm($t['field'] , $simpleSearchForm,false,'',[],false,true) !!}
                        </div>
                    @endforeach
                    {!! SiteHelpers::generateSimpleSearchButton($setting) !!}
                </div>
            @endif
        @endif

        @include( $pageModule.'/toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])

        <?php echo Form::open(array('url' => 'productsubtype/delete/', 'class' => 'form-horizontal', 'id' => 'SximoTable', 'data-parsley-validate' => ''));?>
        <div class="table-responsive">
            @if(!empty($topMessage))
                <h5 class="topMessage">{{ $topMessage }}</h5>
            @endif
            @if(count($rowData)>=1)
                <table class="table table-striped datagrid " id="{{ $pageModule }}Table" data-module="{{ $pageModule }}"
                       data-url="{{ $pageUrl }}">
                    <thead>
                    <tr>
                        @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                            <th width="35"> No</th>
                        @endif
                        @if($setting['disableactioncheckbox']=='false')
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
                            <th width="70"><?php echo Lang::get('core.btn_action');?></th>
                        @endif
                    </tr>
                    </thead>

                    <tbody>
                    @if(($access['is_add'] =='1' || $access['is_edit']=='1' ) && $setting['inline']=='true' )
                        <tr id="form-0">
                            <td> #</td>
                            @if($setting['disableactioncheckbox']=='false')
                                <td></td>
                            @endif
                            @if($setting['view-method']=='expand')
                                <td></td> @endif
                            @foreach ($tableGrid as $t)
                                @if($t['view'] =='1')
                                    <?php $limited = isset($t['limited']) ? $t['limited'] : ''; ?>
                                    @if(SiteHelpers::filterColumn($limited ))
                                        <td data-form="{{ $t['field'] }}"
                                            data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
                                            <input class="form-control" name="{{$t['field']}}"
                                                    {{$t['field'] == 'id' ? 'readonly': ''}}
                                            >
                                            {{--{!! SiteHelpers::transForm($t['field'] , $tableForm) !!}--}}
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
                    <tr class="editable" id="form-{{ $row->id }}" data-id="{{ $row->id }}" id="form-{{ $row->id}}"
                        @if($setting['inline']!='false' && $setting['disablerowactions']=='false') ondblclick="showFloatingCancelSave(this)" @endif>
                        @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                            <td class="number"> <?php echo ++$i;?>  </td>
                        @endif
                        @if($setting['disableactioncheckbox']=='false')
                            <td><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id;?>"/></td>
                        @endif
                        @if($setting['view-method']=='expand')
                            <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}"
                                   data-url="{{ url('productsubtype/show/'.$id) }}"><i class="fa fa-plus "></i></a></td>
                        @endif
                        <?php foreach ($tableGrid as $field) :
                        if($field['view'] == '1') :
                        $conn = (isset($field['conn']) ? $field['conn'] : array());


                        $value = AjaxHelpers::gridFormater($row->$field['field'], $row, $field['attribute'], $conn, $field['nodata']);
                        ?>
                        <?php $limited = isset($field['limited']) ? $field['limited'] : ''; ?>
                        @if(SiteHelpers::filterColumn($limited ))
                            <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}"
                                data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
                                {!! str_replace("\'", "'",$value) !!}
                            </td>
                        @endif
                        <?php
                        endif;
                        endforeach;
                        ?>
                        @if($setting['disablerowactions']=='false')
                            <td data-values="action" data-key="<?php echo $row->id;?>">
                                {!! AjaxHelpers::buttonAction('productsubtype',$access,$id ,$setting) !!}
                                <a  onclick='deleteProductSubtype("{{ URL::to('productsubtype/removal/'.$row->id)}}", "{{$row->type_description}}", "{{$row->id}}", this)'
                                    data-id="{{$row->id}}"
                                    data-action="removal"
                                    class="tips btn btn-xs btn-white productsubtypeRemovalRequestAction"
                                    title="Product Subtype Removal">
                                    <i class="fa fa-trash-o " aria-hidden="true"></i>
                                </a>
                            </td>
                        @endif
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
            @else

                <div style="margin:100px 0; text-align:center;">
                    @if(!empty($message))
                        <p class='centralMessage'>{{ $message }}</p>
                    @else
                        <p class='centralMessage'> No Record Found </p>
                    @endif
                </div>

            @endif
            @if(!empty($bottomMessage))
                <h5 class="bottomMessage">{{ $bottomMessage }}</h5>
            @endif

        </div>
        <?php echo Form::close();?>
        @include('ajaxfooter')

    </div>
</div>
<div class="modal" id="removeProductSubtypeModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div id="mycontent" class="modal-content">
            <div id="myheader" class="modal-header">
                <button type="button " class="btn-xs collapse-close btn btn-danger pull-right"
                        data-dismiss="modal" aria-hidden="true"><i class="fa fa fa-times"></i>
                </button>
                <h4>Remove Product Subtype</h4>
            </div>
            <div class="modal-body col-md-offset-1 col-md-10">
                {!! Form::open(array('url'=>'',
                'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>'
                ','id'=>'removeProductSubtypeFormAjax')) !!}
                <div class="form-group">
                    <div class="col-md-12">
                        <p style="font-size: 140%">Do you want &nbsp;&nbsp;&nbsp;<b><span id="thisProductSubtype">this Product Sub type</span></b>&nbsp;&nbsp;&nbsp; to be removed?</p>
                        {{--<p style="font-size: 120%">If yes please select another Sub Type to change the Category of associated Products.</p>--}}
                        <div class="showIfSubTypeHasAnyProductsAssociated">
                            <p style="font-size: 120%">Following are some of the products related to this sub type.</p>
                            <ul id="listOfProducts" class="list-group list-group-striped">
                                <li>Product 1</li>
                                <li>Product 2</li>
                                <li>Product 3</li>
                                <li>Product 4</li>
                            </ul>
                            <p style="font-size: 120%">If yes please select another Sub Type to change the Category of associated Products.</p>
                        </div>
                    </div>
                </div>
                <div class="form-group showIfSubTypeHasAnyProductsAssociated">
                    <label class="control-label col-md-4" for="message">New Product Subtype</label>
                    <div class="col-md-8">
                        <select class="form-control" cols="5" rows="6" name="newProductSubtype" id="newProductSubtype" required/>
                        </select>
                    </div>
                </div>
                <div class="col-md-offset-6 col-md-6">
                    <div class="form-group" style="margin-top:10px;">
                        <div class="form-group" style="margin-top:10px;">
                            <button type="button" name="submit"  style="float: right" onclick="checkTheFormForValueAndSubmit(true)" class=" btn  btn-lg btn-success" title="REMOVE PRODUCT SUBTYPE" id="remove_product_subtype">
                                <i class="fa  fa-trash" aria-hidden="true"></i>
                                &nbsp {{ Lang::get('core.sb_remove_product_subtype') }}
                            </button>
                            <button type="submit" name="submit" hidden id="removing_product_subtype">
                                {{ Lang::get('core.sb_remove_product_subtype') }}
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="clearfix"></div>

        </div>

    </div>
</div>
@if($setting['inline']!='false' && $setting['disablerowactions']=='false')
    @foreach ($rowData as $row)
        {!! AjaxHelpers::buttonActionInline($row->id,'key') !!}
    @endforeach
@endif
@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>

    var productSubtypeRowToBeDeleted = null;
    function checkTheFormForValueAndSubmit(checkForValue){
        if(checkForValue){
            if($('#newProductSubtype').val() == ''){
                notyMessage('Please Select New Product Sub Type!', [], 'error', 'Error!');
            }else{
                removeTheSubtypeAjaxCall();
                // $('#removing_product_subtype').trigger('click');
            }
        }else{
            removeTheSubtypeAjaxCall();
            // $('#removing_product_subtype').trigger('click');
        }
    }
    function removeTheSubtypeAjaxCall(){
        var url                 = $('#removeProductSubtypeFormAjax').attr('action');
        var newProductSubtype   = $('#newProductSubtype').val();
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                newProductSubtype: newProductSubtype
            },
            beforeSend: function(){
                $('.ajaxLoading').show();
            },
            success: function (data){
                $('.ajaxLoading').hide();
                notyMessage(data.message, [], data.status, data.status.replace(/^\w/, c => c.toUpperCase())+'!');
                if(data.status == 'success'){
                    productSubtypeRowToBeDeleted.remove();
                }
                $('#removeProductSubtypeModal').modal('hide');
            },
            error: function (exception) {
                $('.ajaxLoading').hide();
                $('#removeProductSubtypeModal').modal('hide');
                console.log(exception);
            }
        });
    }

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

        <?php if ($setting['view-method'] == 'expand') :
        echo AjaxHelpers::htmlExpandGrid();
    endif;
        ?>

        // configure simple search is available
        var simpleSearch = $('.simpleSearchContainer');
        if (simpleSearch.length) {
            initiateSearchFormFields(simpleSearch);
            simpleSearch.find('.doSimpleSearch').click(function (event) {
                performSimpleSearch.call($(this), {
                    moduleID: '#{{ $pageModule }}',
                    url: "{{ $pageUrl }}",
                    event: event,
                    ajaxSearch: true,
                    container: simpleSearch
                });
            });
        }

        // Configure data grid columns for sorting
        initDataGrid('{{ $pageModule }}', '{{ $pageUrl }}');

    });

    function deleteProductSubtype(url, name, id, thisRow) {

        productSubtypeRowToBeDeleted = $(thisRow).closest('tr');
        $.ajax({
            url: "{{ URL::to('product/first-ten-products-by-sub-type') }}/"+id,
            method: "GET",
            beforeSend: function(){
                $('.ajaxLoading').show();
            },
            success: function (data) {

                $('#listOfProducts').html('');
                var newProductSubtype = $("select[name='newProductSubtype']");

                if(Object.values(data.products).length >= 1){
                    $('.ajaxLoading').hide();
                    $.each(data.products, function (key, val) {
                        $('#listOfProducts').append('<li>'+val+'</li>');
                    });
                    $('#removeProductSubtypeModal').modal('show');
                    $('#removeProductSubtypeFormAjax').attr('action', url);
                    $('#thisProductSubtype').html(name);
                    newProductSubtype.jCombo("{{ URL::to('productsubtype/comboselect?filter=product_type:id:type_description') }}"
                        +"&parent=request_type_id:"+data.product_type_id+"&limit=WHERE:deleted_at:is:NULL", {selected_value: '', excludeItems: [id]});
                    newProductSubtype.select2();
                }
                else
                {
                    $('#removeProductSubtypeFormAjax').attr('action', url);
                    setTimeout(function () {
                        checkTheFormForValueAndSubmit(false);
                    }, 1000);
                }
            },
            error: function (exception) {
                console.log(exception);
            }
        });

    }

    App.autoCallbacks.registerCallback('inline.row.save.after', function (params) {
       reloadData('#productsubtype','productsubtype/data?search=')
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
