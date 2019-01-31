<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
	<div class="sbox-title">
		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','googledriveearningreport/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','googledriveearningreport/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
			@if(Session::get('gid') ==  \App\Models\Core\Groups::SUPPER_ADMIN)
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
        
        @include( $pageModule.'/toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])

	 <?php echo Form::open(array('url'=>'googledriveearningreport/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
<div class="table-responsive">
    @if(!empty($topMessage))
    <h5 class="topMessage">{{ $topMessage }}</h5>
    @endif
	@if(count($rowData)>=1)
    <table class="table table-striped datagrid " id="{{ $pageModule }}Table" data-module="{{ $pageModule }}" data-url="{{ $pageUrl }}">
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
				<th width="70"><?php echo Lang::get('core.btn_action') ;?></th>
                @endif
			  </tr>
        </thead>

        <tbody>
		@if(($access['is_add'] =='1' || $access['is_edit']=='1' ) && $setting['inline']=='true' )
			<tr id="form-0" >
				<td> # </td>
                @if($setting['disableactioncheckbox']=='false')
				<td> </td>
                @endif
				@if($setting['view-method']=='expand') <td> </td> @endif
				@foreach ($tableGrid as $t)
					@if($t['view'] =='1')
					<?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
						@if(SiteHelpers::filterColumn($limited ))
						<td data-form="{{ $t['field'] }}" data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
							{!! SiteHelpers::transForm($t['field'] , $tableForm) !!}
						</td>
						@endif
					@endif
				@endforeach
				<td >
					<button onclick="saved('form-0')" class="btn btn-primary btn-xs" type="button"><i class="fa  fa-save"></i></button>
				</td>
			  </tr>
			  @endif

           		<?php foreach ($rowData as $row) :
           			  $id = $row->id;
           		?>
                <tr class="editable" id="form-{{ $row->id }}" data-id="{{ $row->id }}" id="form-{{ $row->id}}" @if($setting['inline']!='false' && $setting['disablerowactions']=='false') ondblclick="showFloatingCancelSave(this)" @endif>
                    @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
					<td class="number"> <?php echo ++$i;?>  </td>
                    @endif
                    @if($setting['disableactioncheckbox']=='false')
					<td ><input type="checkbox" FileId="{{$row->google_file_id}}" webLink="{{$row->web_view_link}}" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
                    @endif
					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('googledriveearningreport/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
					@endif
					 <?php foreach ($tableGrid as $field) :
					 	if($field['view'] =='1') :
							$conn = (isset($field['conn']) ? $field['conn'] : array() );


							$value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn,$field['nodata']);
						 	?>
						 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
						 	@if(SiteHelpers::filterColumn($limited ))
								 <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
									@if($field['field'] == 'mime_type')
										{{ SiteHelpers::getExtensionName($value) }}
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
					{!! AjaxHelpers::buttonAction('googledriveearningreport',$access,$id ,$setting) !!}
					 <a href="#" class="btn btn-xs btn-white tips view-file" webLink="{{$row->web_view_link}}" title="View File"><i class="fa fa-search"></i></a>
					 <a href="#" class="btn btn-xs btn-white tips rename-file" fileId="{{$row->google_file_id}}"  title="Rename File"><i class="fa fa-pencil"></i></a>
					 <a href="javascript:void(0)" class="btn btn-xs btn-white tips" link-field="{{$row->web_view_link}}" id="copyBoard"  title="Copy to Clipboard"><i class="fa fa-copy"></i></a>

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
	<?php echo Form::close() ;?>
	@include('ajaxfooter')

	</div>
</div>
@if($setting['inline']!='false' && $setting['disablerowactions']=='false')
@foreach ($rowData as $row)
{!! AjaxHelpers::buttonActionInline($row->id,'key') !!}
@endforeach
@endif
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
	var arr = [];
	$('.ads_Checkbox:checked').each(function () {
		arr[i++] = $(this).val();
	});

	$(".download-drfile").click(function(e){
		e.preventDefault();
		var val = $('input[name="ids[]"]:checked').length;
		if(val>0) {
			var downloadFileIdsArray = '';
			$.each($(".ids:checkbox:checked"), function () {
				downloadFileIdsArray += $(this).val()+','  ;
			});
			downloadFileIdsArray = downloadFileIdsArray.replace(/,\s*$/, "");
			$('.ids').prop('checked', false);
			$('.ids').change();
			$('.icheckbox_square-blue').removeClass('checked');
			window.open('/googledriveearningreport/download-drive-file/' + downloadFileIdsArray+'/'+val, '_self')
		}
		else{
			notyMessageError("Please select any file to download");
		}
	});


	$('.view-file').click(function (e) {
		e.preventDefault();
		var current = $(this);
		var linkValue = current.attr('webLink');
		if (linkValue) {
			  window.open(linkValue);
			}

		});
	var FilerowValue ='';
	var FileExt='';
	var fileId ='';

  $('.rename-file').click(function () {
	  var current = $(this);
	  	  var fileName = $(this).closest('tr').children('td[data-field="file_name"]').text();
	   fileId  = current.attr('fileid');
	  FileExt = $.trim(fileName).split('.').pop();
		  $('#refile').val($.trim(fileName).substr(0, $.trim(fileName).indexOf('.')));
		  $('#exampleModal').modal('show');

  });
	$('#rename-btn').click(function () {
		var renameFileValue = $('#refile').val();
		renameFileValue= renameFileValue+'.'+FileExt;
		if(renameFileValue!=''){
			$('.ajaxLoading').show();
			$.ajax({
				type: "POST",
				url:"/googledriveearningreport/change-filename",
				data: {
					'file':renameFileValue,
					'id':fileId
				},
				success: function(data)
				{
					if(data.status=='200') {
						$('.btn-search[data-original-title="Reload Data"]').trigger('click');
//						$('.icheckbox_square-blue').removeClass('checked');
//						$('.ids').prop('checked', false);
//						$('.ids').change();
//						FilerowValue.children('td[data-field="file_name"]').text(data.name);
						$('#exampleModal').modal('toggle');
					}
					else{
						$('.icheckbox_square-blue').removeClass('checked');
						$('.ids').prop('checked', false);
						$('.ids').change();
						notyMessageError("File Name not changed");
						$('#exampleModal').modal('toggle');
						$('.ajaxLoading').hide();
					}
					}
			});
		}
		else{
			$('#refile').focus();
		}
	});
	$('#close-mdl').click(function () {
		$('#refile').val('');
		$('.icheckbox_square-blue').removeClass('checked');
		$('.ids').prop('checked', false);
		$('.ids').change();
		FileExt='';
		$('#exampleModal').modal('toggle');
	});

	$(document).off('click','#copyBoard').on('click','#copyBoard',function (e) {
		e.preventDefault();
		var temp = '<textarea type="hidden" style="opacity: 0;" id="copyTxt"></textarea>';
		$("body").append(temp);
		var copyTxtField = document.getElementById('copyTxt');
		$(copyTxtField).text($(this).attr('link-field'));
		$(this).focus();
		$(copyTxtField).select();
		document.execCommand("copy");
		$(copyTxtField).remove();
		notyMessage("link coppied to clipboard");
		return false;

	});
    // Configure data grid columns for sorting
    initDataGrid('{{ $pageModule }}', '{{ $pageUrl }}');
});
/*
App.autoCallbacks.registerCallback('reloaddata', function () {
	var excludedLocations = {!! json_encode($excludedUserLocations) !!}
	$("select[name='loc_id']").jCombo("{{ URL::to('googledriveearningreport/comboselect?filter=location:id:id|location_name') }}", {
		excludeItems: excludedLocations
	});
});
*/
</script>
<style>
.table th.right { text-align:right !important;}
.table th.center { text-align:center !important;}

</style>