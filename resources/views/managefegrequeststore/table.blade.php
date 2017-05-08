<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
    <div class="sbox-title">
        <h5> <i class="fa fa-table"></i> </h5>
        <div class="sbox-tools" >
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}', 'managefegrequeststore/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','managefegrequeststore/data?view=manage&return={{ $return }}')"><i class="fa fa-refresh"></i></a>
            @if(Session::get('gid') ==10)
            <a href="{{ url('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
            @endif
        </div>
    </div>
    <div class="sbox-content">
        <?php
        $searched=\Request::get('search');

        $searched=explode("|",$searched);
        $searchedParams=[];
        foreach($searched as $t)
        {
            $searchedParams[]=explode(':',$t);
        }
            $location_id=\Request::get('v2');
        ?>
        @if($setting['usesimplesearch']!='false')
            <?php $simpleSearchForm = SiteHelpers::configureSimpleSearchForm($tableForm); ?>
            @if(!empty($simpleSearchForm))
                <div class="simpleSearchContainer clearfix">
                    @foreach ($simpleSearchForm as $t)
                        <div class="sscol {{ $t['widthClass'] }}" style="{{ $t['widthStyle'] }}">
                            <?php
                            $fv="";
                            foreach($searchedParams as $f)
                            {
                                $fv=in_array($t['field'],$f)?$f[2] :"";
                                if($fv != "")
                                {
                                    break;
                                }
                                if($t['field']=='location_id' && !empty($location_id))
                                {
                                    $fv=$location_id;
                                }
                            }
                            ?>

                            {!! SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) !!}
                            {!! SiteHelpers::transForm($t['field'] , $simpleSearchForm,false,$fv) !!}
                        </div>
                    @endforeach
                    <div class="sscol-submit"><br/>
                        <button type="button" name="search" class="doSimpleSearch btn btn-sm btn-primary"> Search </button>
                    </div>
                </div>
            @endif
        @endif
        @include( $pageModule.'/toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id),'manageRequestInfo' => $manageRequestInfo,'view'=>$view,'TID'=> $TID,'LID'=>$LID,'VID'=>$VID])

        <?php echo Form::open(array('url' => 'managefegrequeststore/delete/', 'class' => 'form-horizontal', 'id' => 'SximoTable', 'data-parsley-validate' => '')); ?>
        <div class="table-responsive">
            @if(count($rowData)>=1)
            <table class="table table-striped datagrid " id="{{ $pageModule }}Table">
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

                        <th width="200">Vendor</th>
                        <th width="100">Price</th>
                        <th width="140" style="background-color:red;color:#FFF">Remaining Reserved Qty</th>
                        <th width="100">Order Type</th>
                        @if($setting['disablerowactions']=='false')
                            <th width="105"><?php echo Lang::get('core.btn_action') ;?></th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @if($access['is_add'] =='1' && $setting['inline']=='true')
                    <tr id="form-0" >
                        <td> # </td>
                        @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                            <td> </td>
                        @endif
                        @if($setting['view-method']=='expand') <td> </td> @endif
                        @foreach ($tableGrid as $t)
                            @if(isset($t['inline']) && $t['inline'] =='1')
                        <?php $limited = isset($t['limited']) ? $t['limited'] : ''; ?>
                        @if(SiteHelpers::filterColumn($limited ))
                        <td data-form="{{ $t['field'] }}" data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
                            {!! SiteHelpers::transInlineForm($t['field'] , $tableForm) !!}
                        </td>
                        @endif
                        @endif
                        @endforeach
                        <td >
                            <button onclick="saved('form-0')" class="btn btn-primary btn-xs" type="button"><i class="fa  fa-save"></i></button>
                        </td>
                    </tr>
                    @endif

                    <?php
                    foreach ($rowData as $row) :
                        $id = $row->id;
                        ?>
                        <tr class="editable" id="form-{{ $row->id }}" @if($setting['inline']!='false' && $setting['disablerowactions']=='false') data-id="{{ $row->id }}" ondblclick="showFloatingCancelSave(this)" @endif>
                            @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                                <td class="number"> <?php echo ++$i;?>  </td>
                            @endif
                                @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                                <td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
                            @endif
                            @if($setting['view-method']=='expand')
                            <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('managefegrequeststore/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
                            @endif
                            <?php
                            foreach ($tableGrid as $field) :
                                if ($field['view'] == '1') :
                                    $conn = (isset($field['conn']) ? $field['conn'] : array() );


                                    $value = AjaxHelpers::gridFormater($row->$field['field'], $row, $field['attribute'], $conn);
                                    ?>
            <?php $limited = isset($field['limited']) ? $field['limited'] : ''; ?>
                                    @if(SiteHelpers::filterColumn($limited ))
                                    <td align="<?php echo $field['align']; ?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
                                       @if($field['field'] == 'price')
                                            {!!number_format((float)2, '.','',$value) !!}
                                        @else
                                            {!! $value !!}

                                        @endif

                                    </td>
                                    @endif
                                    <?php
                                endif;
                            endforeach;
                            ?>
                            <td>{{ \DateHelpers::formatZeroValue($row->vendor_name) }}</td>
                            <td>{{CurrencyHelpers::formatPrice($row->case_price)}} </td>
                            <td align="center">{{ \DateHelpers::formatZeroValue($row->reserved_difference) }}</td>
                            <td> {{ \DateHelpers::formatZeroValue($row->order_type) }}</td>
                            <td data-values="action" data-key="<?php echo $row->id; ?>">
                                {!! AjaxHelpers::buttonAction('managefegrequeststore',$access,$id ,$setting) !!}
                               @if($view == "manage" && $access['is_edit'] == 1 )
                                <a href="#"  class="tips btn btn-xs btn-white" data-id="{{ $row->id }}" title="Deny Request" onclick="denyRequest(this);"><i class="fa fa-ban" aria-hidden="true"></i></a>
                            @endif
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
<?php endforeach; ?>

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

<?php echo Form::close(); ?>
        @include('ajaxfooter',array('V1'=>$TID,'V2'=>$LID,'V3'=>$VID,'view'=>$view))

    </div>
</div>

@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
            $(document).ready(function() {
    $('.tips').tooltip();
            $('input[type="checkbox"],input[type="radio"]').iCheck({
    checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square_green'
    });
            $('#{{ $pageModule }}Paginate .pagination li a').click(function() {
    var url = $(this).attr('href');
            reloadData('#{{ $pageModule }}', url);
            return false;
    });
<?php
if ($setting['view-method'] == 'expand') :
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
        params.data.exclude['v1'] = true;
        params.data.exclude['v2'] = true;
        params.data.exclude['v3'] = true;
        params.data.force['view'] = 'manage';

    });
function denyRequest(ele)
{
    $('.ajaxLoading').show();
    var requestId=$(ele).data('id');
    var url="{{ url() }}/managefegrequeststore/deny";
    $.post(url,{request_id:requestId},function(data){

        if(data.status == 'success')
        {
            notyMessage(data.message);
            reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?view=manage');
        } else {
            notyMessageError(data.message);
            $('.ajaxLoading').hide();
            return false;
        }
    });
}
</script>
<style>
    .table th.right {
        text-align: right !important;
    }

    .table th.center {
        text-align: center !important;
    }

</style>

