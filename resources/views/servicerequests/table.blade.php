<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<style>
    .select2-drop{
        z-index: 600000000 !important;
    }
</style>
<div class="sbox">
    <div class="sbox-title">
        <h5><i class="fa fa-table"></i></h5>

        <div class="sbox-tools">
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips clearSearchButton" title="Clear Search"
               onclick="reloadData('#{{ $pageModule }}','servicerequests/data?ticket_type={{$ticketType}}','',{},undefined,true);"><i class="fa fa-trash-o"></i> Clear
                Search </a>
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips reloadDataButton" title="Reload Data"
               onclick="reloadData('#{{ $pageModule }}','servicerequests/data?return={{ $return }}')"><i
                        class="fa fa-refresh"></i></a>
            @if(Session::get('gid') == \App\Models\Core\Groups::SUPPER_ADMIN)
                <a href="{{ url('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips"
                   title=" {{ Lang::get('core.btn_config') }}"><i class="fa fa-cog"></i></a>
            @endif
        </div>
    </div>
    <div class="sbox-content">
        <div class="settingTabContainer">
            <?php $isActive1 = 1; $isActive2 = 0;  ?>
            @if($ticketType == 'game-related')
                <?php $isActive2 = 1; $isActive1 = 0; ?>
                @else
                <?php $isActive1 = 1; $isActive2 = 0; ?>
                @endif

            <div class="setting-tab @if( $isActive1 == 1) setting-tab-active @endif" is-active='{{ $isActive1 }}' data-type="debit-card-related" onclick="return serverRequestTabsSelect(this,'setting-tab','','',true);">Debit Card Related</div>
            <div class="setting-tab setting-tab-second @if( $isActive2 == 1) setting-tab-active @endif" is-active='{{ $isActive2 }}' data-type="game-related" onclick="return serverRequestTabsSelect(this,'setting-tab','','',true);">Game Related</div>
        </div>
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
                        <div class="sscol col-md-2">
                            <input type="hidden" name="ticket_type" value="{{ $ticketType }}">
                            <span style="width: 100%;margin-top:22px;float: left;margin-bottom: 5px;margin-left: 3px;"><input type="checkbox" name="showAll" id="showAll" class="form-control checkbox" data-simplesearch="1" @if(\Illuminate\Support\Facades\Session::get('showAllChecked') == true) checked @endif>&nbsp;&nbsp; <label for="showAll">Display Closed</label></span>
                        </div>
                    {!! SiteHelpers::generateSimpleSearchButton($setting) !!}
                </div>
            @endif
        @endif
       @include( $pageModule.'/toolbar',['colconfigs' => SiteHelpers::getRequiredConfigs($module_id,$ticketType)])

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
                        <?php  foreach ($tableGrid as $t) :
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
                                            ' grid-type="'.$ticketType.'"'.
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
                    </tr>
                    </thead>
                    <tbody>

                    @if(($access['is_add'] || $access['is_edit'] =='1') =='1' && $setting['inline']=='true')
                        <tr id="form-0" >
                            <td> # </td>
                            @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                            <td> </td>
                            @endif
                            @if($setting['view-method']=='expand') <td> </td> @endif
                            @foreach ($tableGrid as $t)
                              @if ($canChangeStatus || !in_array($t['field'],['Status', 'closed']))
                                @if(isset($t['inline']) && $t['inline'] =='1')
                                    <?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
                                    @if(SiteHelpers::filterColumn($limited ))
                                        <td data-form="{{ $t['field'] }}" data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
                                            {!! SiteHelpers::transInlineForm($t['field'] , $tableForm) !!}
                                        </td>
                                    @endif
                                @endif
                              @endif
                            @endforeach
                            <td >
                                <button onclick="saved('form-0')" class="btn btn-primary btn-xs" type="button"><i class="fa  fa-save"></i></button>
                            </td>
                        </tr>
                    @endif
                    <?php foreach ($rowData as $row) :
                    $id = strip_tags($row->TicketID);
                    ?>
                    <tr @if($access['is_edit']=='1' && $setting['inline']=='true' )class="editable"
                        @endif id="form-{{ strip_tags($row->TicketID) }}"
                        @if($setting['inline']!='false' && $setting['disablerowactions']=='false') data-id="{{ strip_tags($row->TicketID) }}"
                        @if($access['is_edit']=='1' && $setting['inline']=='true' )ondblclick="showFloatingCancelSave(this)" @endif @endif>
                        @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                            <td class="number"> <?php echo ++$i;?>  </td>
                        @endif
                            @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                                <td><input type="checkbox" class="ids" name="ids[]" value="<?php echo strip_tags($row->TicketID);?>"/></td>
                        @endif

                        @if($setting['view-method']=='expand')
                            <td>
                                <a href="javascript:void(0)" class="expandable" rel="#row-{{ strip_tags($row->TicketID) }}"
                                   data-url="{{ url('servicerequests/show/'.$id) }}"><i class="fa fa-plus "></i></a>
                            </td>
                        @endif
                        <?php foreach ($tableGrid as $field) :
                        if($field['view'] == '1') :
                        $conn = (isset($field['conn']) ? $field['conn'] : array());
                        $value = AjaxHelpers::gridFormater($row->$field['field'], $row, $field['attribute'], $conn,isset($field['nodata'])?$field['nodata']:0);
                            if(strtolower($value)=='urgent'){
                                $value = strtoupper($value);
                            }
                        ?>
                        <?php $limited = isset($field['limited']) ? $field['limited'] : ''; ?>
                        @if(SiteHelpers::filterColumn($limited ))
                                <td align="<?php echo $field['align'];?>" data-values="{{strip_tags($row->$field['field']) }}"
                                    data-field="{{ $field['field'] }}" data-format="{{ htmlentities(strip_tags($value)) }}">
                                     @if($field['field']=='urgent')
                                        <?php
                                        $value = strtoupper($field['field']);
                                        ?>
                                        {!! $value !!}
                                    @else
                                        {!! $value !!}
                                    @endif
                                </td>
                        @endif
                        <?php endif;
                        endforeach;
                        ?>
                        <td data-values="action" data-key="<?php echo strip_tags($row->TicketID);?>">
                            <div class=" action dropup">

                                <a href="{{ url('servicerequests/show/'.strip_tags($row->TicketID)."?ticket_type=".$ticketType) }}" onclick="ajaxViewDetail('#servicerequests',this.href); return false; " class="btn btn-xs btn-white tips" title="" data-original-title="View"><i class="fa fa-search"></i></a>

                                @if($canEditDetail)
                                <a href="{{ url('servicerequests/update/'.strip_tags($row->TicketID)."?ticket_type=".$ticketType) }}" onclick="ajaxViewDetail('#servicerequests',this.href); return false; " class="btn btn-xs btn-white tips" title="" data-original-title="Edit"><i class="fa  fa-edit"></i></a>
                                @endif
                            </div>


                        </td>
                    </tr>
                    @if($setting['view-method']=='expand')
                        <tr style="display:none" class="expanded" id="row-{{ strip_tags($row->TicketID) }}">
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
                        {!! AjaxHelpers::buttonActionInline(strip_tags($row->TicketID),'TicketID') !!}
                    @endforeach
                @endif
            @else

                <div style="margin:100px 0; text-align:center;">

                    <p> No Record Found </p>
                </div>

            @endif

        </div>
        @include('ajaxfooter')

    </div>
</div>
<script>
    $(document).ready(function () {
        $('.tips').tooltip();
        $('input[type="checkbox"],input[type="radio"]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
        });
        $('#{{ $pageModule }}Table .checkall').on('ifChecked', function () {
            $('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('check');
        });
        $('#{{ $pageModule }}Table .checkall').on('ifUnchecked', function () {
            $('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('uncheck');
        });

        $('#{{ $pageModule }}Paginate .pagination li a').click(function () {
            var url = $(this).attr('href')+'&ticket_type={{ $ticketType }}';
            reloadData('#{{ $pageModule }}', url);
            return false;
        });
        /*$('select[name="Status"]').change(function () {
            var showAll = $('input[name=showAll]');
            if($('select[name="Status"] :selected')[0].index == 0)
            {
                $('input[name=showAll]').removeAttr('disabled');
            }
            else
            {
                showAll.attr('disabled','disabled');
                showAll.parent('.icheckbox_square-blue').removeClass('checked');
            }
        });*/
        <?php if ($setting['view-method'] == 'expand') :
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
                    ticketType:'&ticket_type={{ $ticketType }}',
                    container: simpleSearch
                });
            });
        }

        initDataGrid('{{ $pageModule }}', '{{ $pageUrl }}');
      /*  setTimeout(function () {
            console.log($('select[name="Status"]').siblings('.select2-container').children('.select2-choice').children('span.select2-chosen').text(),$('select[name="Status"]').siblings('.select2-container').children('.select2-choice').children('span.select2-chosen')[0]);
            if($('select[name="Status"]').siblings('.select2-container').children('.select2-choice').children('span.select2-chosen').text() == ' -- Select  -- ')
            {
                $('input[name=showAll]').removeAttr('disabled');
            }
            else {
                $('input[name=showAll]').attr('disabled','disabled').parent('.icheckbox_square-blue').removeClass('checked').css('cursor','no-drop');
            }
        },400);*/
    });
    makeSimpleSearchFieldsToInitiateSearchOnEnter();

</script>
<style>
    .table th.right {
        text-align: right !important;
    }

    .table th.center {
        text-align: center !important;
    }

</style>
