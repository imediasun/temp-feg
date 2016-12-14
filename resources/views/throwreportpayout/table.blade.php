<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
    <div class="sbox-title">
        <h5><i class="fa fa-table"></i></h5>
        <div class="sbox-tools">
            <!--
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','throwreportpayout/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','throwreportpayout/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
			-->
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
                        <button type="button" name="search" class="doSimpleSearch btn btn-sm btn-primary"> Search
                        </button>
                    </div>
                </div>
            @endif
        @endif

        @include( $pageModule.'/toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])

        <?php echo Form::open(array('url' => 'throwreportpayout/delete/', 'class' => 'form-horizontal', 'id' => 'SximoTable', 'data-parsley-validate' => ''));?>
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
                                            ' align="' . $t['align'] . '"' .
                                            ' width="' . $t['width'] . '"';
                                    $th .= '>';
                                    $th .= \SiteHelpers::activeLang($t['label'], (isset($t['language']) ? $t['language'] : array()));
                                    $th .= '</th>';
                                    echo $th;
                                }
                            endif;
                        endforeach; ?>
                        <th width="100" align="left">
                            Payout %
                        </th>
                        <th width="100" align="left">
                            Overall payout
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @if($access['is_add'] =='1' && $setting['inline']=='true')
                        <tr id="form-0">
                            <td> #</td>
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
                        @if($setting['view-method']=='expand')
                            <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}"
                                   data-url="{{ url('throwreportpayout/show/'.$id) }}"><i class="fa fa-plus "></i></a>
                            </td>
                        @endif
                        <?php foreach ($tableGrid as $field) :
                        if($field['view'] == '1') :
                        $conn = (isset($field['conn']) ? $field['conn'] : array());


                        $value = AjaxHelpers::gridFormater($row->$field['field'], $row, $field['attribute'], $conn);
                        ?>
                        <?php $limited = isset($field['limited']) ? $field['limited'] : ''; ?>
                        @if(SiteHelpers::filterColumn($limited ))
                            <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}"
                                data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
                                @if($field['field']=='product_id')
                                    <?php
                                    $product_ids = json_decode($value);
                                    foreach ($product_ids as $index => $product_id) {
                                        if(!empty($product_id))
                                            echo ++$index . '. ' . \SiteHelpers::getProductName($product_id) . '<br/>';
                                    }
                                    ?>
                                @elseif($field['field']=='retail_price')
                                    {{ '$'.number_format($row->retail_price,2) }}
                                @elseif($field['field']=='product_cogs_1')
                                    {{ '$'.number_format($row->product_cogs_1,2) }}
                                @elseif($field['field']=='game_earnings')
                                    {{ '$'.number_format($row->game_earnings,2) }}
                                @elseif($field['field']=='price_per_play')
                                    {{ '$'.number_format($row->product_cogs_1,2) }}
                                @elseif($field['field']=='product_throw_1')
                                    {{ $row->product_throw_1 }}
                                @else
                                    {!! $value !!}
                                @endif
                            </td>
                        @endif
                        <?php
                        endif;
                        endforeach;
                        ?>
                        <td width="100" align="left">
                            @if($row->game_earnings != 0.00)
                                {{ number_format(($row->product_cogs_1 / $row->game_earnings),4).'%' }}
                            @else
                                0.00
                            @endif
                        </td>
                        <td width="100" align="left">
                            @if($total_revenue != 0)
                                {{ number_format($row->game_earnings/$total_revenue,4).'%' }}
                            @else
                                0.00
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
        <div class="table-footer">
            <div class="row">
                @if(!isset($setting['disablepagination']) || $setting['disablepagination'] == 'false')
                    <div class="col-md-5 col-md-offset-7" id="<?php echo $pageModule;?>Paginate">
                        {!! $pagination->appends($pager)->render() !!}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script src="https://cdn.jsdelivr.net/momentjs/2.10.6/moment.min.js"></script>
<script>
    $(document).ready(function () {

        var lastWeekDate = moment(new Date()).weekday(-1).format('MM/DD/YYYY');
        $(".weeklyDatePicker").datepicker({endDate: lastWeekDate, autoclose: true});
        $(".weeklyDatePicker").val('{{ $setDate }}');
        $('.weeklyDatePicker').datepicker().on('change', function (e) {
            var value = $(".weeklyDatePicker").val();
            var firstDate = moment(value, "MM/DD/YYYY").day(0).format("MM/DD/YYYY");
            var lastDate = moment(value, "MM/DD/YYYY").day(6).format("MM/DD/YYYY");
            selectedDate = firstDate + " - " + lastDate;
            $(".weeklyDatePicker").val(firstDate + " - " + lastDate);
            reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?search=date_start:equal:' + firstDate + '|date_end:equal:' + lastDate + '&config_id=' + $("#col-config").val());
            $('.weeklyDatePicker').datepicker("hide");
        });
        var defaultWeekValue = '{{$setDate}}';
        $('.weeklyDatePicker').datepicker().on('click blur hide', function (e) {
            $(".weeklyDatePicker").val(defaultWeekValue);
        });


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
</script>
<style>
    .table th.right {
        text-align: right !important;
    }

    .table th.center {
        text-align: center !important;
    }

</style>
