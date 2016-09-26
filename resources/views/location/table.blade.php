<?php
$colconfigs = SiteHelpers::getRequiredConfigs($module_id);
if (!$colconfigs) {
    usort($tableGrid, "SiteHelpers::_sort");
}?>
<div class="sbox">
    <div class="sbox-title">
        <h5><i class="fa fa-table"></i></h5>

        <div class="sbox-tools">
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search"
               onclick="reloadData('#{{ $pageModule }}','location/data?search=')"><i class="fa fa-trash-o"></i> Clear
                Search </a>
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data"
               onclick="reloadData('#{{ $pageModule }}','location/data?return={{ $return }}')"><i
                        class="fa fa-refresh"></i></a>
            @if(Session::get('gid') ==1)
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
                        <button type="button" name="search" class="doSimpleSearch btn btn-sm btn-primary"> Search </button>
                    </div>
                </div>
            @endif
        @endif
        @include( $pageModule.'/toolbar',['colconfigs' => $colconfigs])

        <?php echo Form::open(array('url' => 'location/delete/', 'class' => 'form-horizontal', 'id' => 'SximoTable', 'data-parsley-validate' => ''));?>
        <div class="table-responsive">
            @if(count($rowData)>=1)
                <table class="table table-striped datagrid  " id="{{ $pageModule }}Table" style="table-layout: fixed">
                    <thead>
                    <tr>
                        @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                            <th width="35"> No </th>
                        @endif
                        @if($setting['disableactioncheckbox']=='false')
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
                                            ' align="'.$t['align'].'"'.
                                            ' width="'.$t['width'].'"';
                                    $th .= '>';
                                    $th .= \SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array()));
                                    $th .= '</th>';
                                    echo $th;
                                }
                            endif;
                        endforeach; ?>
                        @if($setting['disablerowactions']=='false')
                            <th width="70"><?php echo Lang::get('core.btn_action') ;?></th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @if($access['is_add'] =='1' && $setting['inline']=='true')
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
                                                {!! SiteHelpers::transForm($t['field'] , $tableForm) !!}
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

                    <tr class="editable" id="form-{{ $row->id }}">
                        @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                        <td class="number"> <?php echo ++$i;?>  </td>
                        @endif
                        @if($setting['disableactioncheckbox']=='false')
                        <td><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id;?>"/></td>
                        @endif
                        @if($setting['view-method']=='expand')
                            <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}"
                                   data-url="{{ url('location/show/'.$id) }}"><i class="fa fa-plus "></i></a></td>
                        @endif
                        <?php foreach ($tableGrid as $field) :
                        if($field['view'] == '1') :
                        $conn = (isset($field['conn']) ? $field['conn'] : array());

                        $value = AjaxHelpers::gridFormater($row->$field['field'], $row, $field['attribute'], $conn);
                        ?>
                        <?php $limited = isset($field['limited']) ? $field['limited'] : ''; ?>
                        @if(SiteHelpers::filterColumn($limited ))
                            @if($field['field'] != 'bill_token_detail' && $field['field'] != 'bill_license_detail' && $field['field'] != 'bill_attraction_detail')
                                    <td align="<?php echo $field['align'];?>" data-values="{{ isset($row->$field['field'])?$row->$field['field']:"" }}"
                                    data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
                                    @if($field['field'] == 'date_opened')

                                        {!! date("m/d/Y", strtotime($value)) !!}

                                    @elseif($field['field'] == 'date_closed')

                                        {!! date("m/d/Y", strtotime($value)) !!}

                                    @elseif($field['field'] == 'bill_debit_amt')


                                       {{number_format($value, 2)}}



                                    @else
                                        {!! $value !!}

                                  @endif
                                </td>
                            @endif
                        @endif
                        <?php
                        endif;
                        endforeach;
                        ?>
                        <td data-values="action" data-key="<?php echo $row->id;?>">
                            {!! AjaxHelpers::buttonAction('location',$access,$id ,$setting) !!}
                            {!! AjaxHelpers::buttonActionInline($row->id,'id') !!}
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
            @else

                <div style="margin:100px 0; text-align:center;">

                    <p> No Record Found </p>
                </div>

            @endif

        </div>
        <?php echo Form::close();?>
        @include('ajaxfooter')

    </div>
</div>

@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
    $(document).ready(function () {
        $('.tips').tooltip();
        $('input[type="checkbox"],input[type="radio"]').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
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
</script>
<style>
    .table th.right {
        text-align: right !important;
    }

    .table th.center {
        text-align: center !important;
    }

</style>
	