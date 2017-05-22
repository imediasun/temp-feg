<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
	<div class="sbox-title">
		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','systememailreportmanager/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','systememailreportmanager/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
			@if(Session::get('gid') ==10)
			<a href="{{ url('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
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
        
        @include( 'feg/system/systememailmanager/toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])

	 <?php echo Form::open(array('url'=>'feg/system/systememailreportmanager/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
<div class="table-responsive">
    @if(!empty($topMessage))
    <h5 class="topMessage">{{ $topMessage }}</h5>
    @endif
	@if(count($rowData)>=1)
    <table class="table table-striped datagrid " id="{{ $pageModule }}Table" data-module="{{ $pageModule }}" data-url="{{ $pageUrl }}">
        <thead>
			<tr>
                @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
				<th width="35"> # </th>
                @endif
                @if($setting['disablerowactions']=='false')
				<th width="70"><?php echo Lang::get('core.btn_action') ;?></th>
                @endif
                    @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
				<th width="30"> <input type="checkbox" class="checkall" /></th>
                @endif
				@if($setting['view-method']=='expand') <th>  </th> @endif
                <th width="200">Info</th>
                <th>TO</th>
                <th>CC</th>
                <th>BCC</th>
			  </tr>
        </thead>

        <tbody>
            <?php foreach ($rowData as $row) :
                  $id = $row->id;
            ?>
            <tr class="editable" id="form-{{ $row->id }}">
                @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                <td class="number"> <?php echo ++$i;?>  </td>
                @endif
                @if($setting['disablerowactions']=='false')     
				 <td data-values="action" data-key="<?php echo $row->id ;?>">
					{!! AjaxHelpers::buttonAction('feg/system/systememailreportmanager',$access,$id ,$setting) !!}
					{!! AjaxHelpers::buttonActionInline($row->id,'id') !!}
				</td>
                @endif
                    @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                <td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
                @endif
                @if($setting['view-method']=='expand')
                <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('feg/system/systememailreportmanager/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
                @endif
                <td>
                 <?php 
                    $vrow = new \stdClass;
                    foreach ($tableGrid as $field) :
                        $conn = (isset($field['conn']) ? $field['conn'] : array() );


                        $value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn,isset($field['nodata'])?$field['nodata']:0);
                        $fieldName = $field['field'];
                        if ($fieldName == "is_active") {
                            $value = $value == "1" ? "Yes" : "No";
                        }
                        if ($fieldName == "has_locationwise_filter") {
                            $value = $value == "1" ? "Yes" : "No";
                        }
                        $tempValue = array();
                        if (strpos($fieldName, "email_groups") > 0) {
                            $values = explode(",", $value);
                            foreach($values as $val) {
                                if (!empty($userGroups[$val])) {
                                    $tempValue[] = "<span>".
                                            $userGroups[$val]->group_name.
                                        "";
                                }                                    
                            }
                            $value = implode(", </span>", $tempValue);
                            if (!empty($value)) {
                                $value .= "</span>";
                            }                
                        }
                        if (strpos($fieldName, "email_individuals") > 0) {
                            $values = explode(",", $value);
                            foreach($values as $val) {
                                if (!empty($users[$val])) {
                                    $tempValue[] = "<span class='tips' title='".$users[$val]->email."'>".
                                            $users[$val]->first_name . 
                                            ' ' . $users[$val]->last_name . "";

                                }                                    
                            }
                            $value = implode(", </span>", $tempValue);
                            if (!empty($value)) {
                                $value .= "</span>";
                            }                                                
                        }
                        
                        if (strpos($fieldName, "email_location_contacts") > 0) {
                            $values = explode(",", $value);
                            foreach($values as $val) {
                                if (!empty($locationContactNames[$val])) {
                                    $tempValue[] = "<span class='tips'>".
                                            $locationContactNames[$val];
                                }                                    
                            }
                            $value = implode(", </span>", $tempValue);
                            if (!empty($value)) {
                                $value .= "</span>";
                            }                                                
                        }
                        
                        if (strpos($fieldName, "updated_at") === 0) {  
                            $time = strtotime($value);
                            if ($time === false || $time <= 0) {
                                $value = $row->created_at;
                            }                               
                        }
                        
                        $vrow->$fieldName = $value;
                        
                    endforeach;
                ?>
                
                    <h4><strong>{{ $vrow->report_name }}</strong></h4>
                    <strong>ID:</strong> {{ $vrow->id }}<br/><br/>
                    <strong>Is Active?:</strong> {{ $vrow->is_active }}<br/><br/>
                    <strong>Locationwise filter?:</strong> {{ $vrow->has_locationwise_filter }}<br/><br/>
                    <strong>Updated On:</strong> {{ $vrow->updated_at }}<br/><br/>
                    
                </td>
                <td>
                    <h6>Groups</h6>
                    {!! $vrow->to_email_groups !!}
                    <hr/>
                    <h6>Location based roles</h6>
                    {!! $vrow->to_email_location_contacts !!}
                    <hr/>
                    <h6>Users</h6>
                    {!! $vrow->to_email_individuals !!}
                    <hr/>
                    <h6>Include</h6>
                    {!! $vrow->to_include_emails !!} 
                    <hr/>
                    <h6>Exlude</h6>
                    {!! $vrow->to_exclude_emails !!}
                    <hr/>
                    <h6>Test Mode</h6>
                    {!! $vrow->test_to_emails !!}
                    <hr/>
                </td>
                <td>
                    <h6>Groups</h6>
                    {!! $vrow->cc_email_groups !!}
                    <hr/>
                    <h6>Location Contacts/Managers</h6>
                    {!! $vrow->cc_email_location_contacts !!}
                    <hr/>                    
                    <h6>Users</h6>                    
                    {!! $vrow->cc_email_individuals !!}
                    <hr/>
                    <h6>Include</h6>
                    {!! $vrow->cc_include_emails !!}
                    <hr/>
                    <h6>Exlude</h6>
                    {!! $vrow->cc_exclude_emails !!}
                    <hr/>
                    <h6>Test Mode</h6>
                    {!! $vrow->test_cc_emails !!}
                    <hr/>
                </td>
                <td>
                    <h6>Groups</h6>
                    {!! $vrow->bcc_email_groups !!}
                    <hr/>
                    <h6>Location Contacts/Managers</h6>
                    {!! $vrow->bcc_email_location_contacts !!}
                    <hr/>
                    <h6>Users</h6> 
                    {!! $vrow->bcc_email_individuals !!}
                    <hr/>
                    <h6>Include</h6>
                    {!! $vrow->bcc_include_emails !!} 
                    <hr/>
                    <h6>Exlude</h6>
                    {!! $vrow->bcc_exclude_emails !!}
                    <hr/>
                    <h6>Test Mode</h6>
                    {!! $vrow->test_bcc_emails !!}
                    <hr/>
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
	<?php echo Form::close() ;?>
	@include('ajaxfooter')

	</div>
</div>

	@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
$(document).ready(function() {
	$('.tips').tooltip();
	$('input[type="checkbox"],input[type="radio"]').iCheck({
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

	<?php if($setting['view-method'] =='expand') :
			echo AjaxHelpers::htmlExpandGrid();
		endif;
	 ?>

    // configure simple search is available
    var simpleSearch = $('.simpleSearchContainer');
    if (simpleSearch.length) {
        initiateSearchFormFields(simpleSearch);
        simpleSearch.find('.doSimpleSearch').click(function(event){
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
.table th.right { text-align:right !important;}
.table th.center { text-align:center !important;}

</style>
