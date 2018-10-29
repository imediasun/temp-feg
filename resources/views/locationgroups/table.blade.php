<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
	<div class="sbox-title">
		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','locationgroups/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','locationgroups/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
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

	 <?php echo Form::open(array('url'=>'locationgroups/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
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
							@if($t['field'] == 'location_ids' || $t['field'] == 'excluded_product_ids' || $t['field'] == 'excluded_product_type_ids')
								<td data-form="{{ $t['field'] }}" data-form-type="select">
									<select customOption="1" name="{{$t['field']}}[]" class="custom-select2 sel-inline {{ $t['field'] }}" multiple="multiple">

									</select>
								</td>
							@else
						<td data-form="{{ $t['field'] }}" data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
							{!! SiteHelpers::transForm($t['field'] , $tableForm) !!}
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
           			  $id = $row->id;
           		?>
                <tr class="editable" id="form-{{ $row->id }}" data-id="{{ $row->id }}" id="form-{{ $row->id}}" @if($setting['inline']!='false' && $setting['disablerowactions']=='false') ondblclick="showFloatingCancelSave(this); loadExcludedDropDownData(this);" @endif>
                    @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
					<td class="number"> <?php echo ++$i;?>  </td>
                    @endif
                    @if($setting['disableactioncheckbox']=='false')
					<td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
                    @endif
					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('locationgroups/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
					@endif
					 <?php foreach ($tableGrid as $field) :
					 	if($field['view'] =='1') :
							$conn = (isset($field['conn']) ? $field['conn'] : array() );


							$value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn,$field['nodata']);
						 	?>
						 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
						 	@if(SiteHelpers::filterColumn($limited ))
								 <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
									{!! $value !!}
								 </td>
							@endif
                    <?php
						 endif;
						endforeach;
					  ?>
                  @if($setting['disablerowactions']=='false')     
				 <td data-values="action" data-key="<?php echo $row->id ;?>">
					 {!! AjaxHelpers::buttonAction('locationgroups',$access,$id ,$setting) !!}
{{--					 @if($row->status_id=='Open' || $row->status_id=='Open (Partial)')--}}

						 {{--<a onclick='deleteLocationGroups("{{$row->id}}")'--}}
							{{--href="{{ URL::to('locationgroups/delete/'.$row->id)}}"--}}
							{{--data-id="{{$row->id}}"--}}
							{{--data-action="removal"--}}
							{{--class="tips btn btn-xs btn-white locationGroupDeleteAction"--}}
							{{--title="{{Lang::get('core.locationGroup.deleteButtonTitle')}}">--}}
							 {{--<i class="fa fa-trash-o " aria-hidden="true"></i>--}}
						 {{--</a>--}}

					 {{--@endif--}}
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
{{--@section('select2Custom')

@endsection--}}
@if($setting['inline']!='false' && $setting['disablerowactions']=='false')
@foreach ($rowData as $row)
{!! AjaxHelpers::buttonActionInline($row->id,'key') !!}
@endforeach
@endif
	@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
	/**
	 *
	 * @param rowId
	 * @param field
	 * @param responseData
	 * @param selected
	 */
	function perseReponse(rowId,field,responseData,selected){
		$('tr#'+rowId+' td[data-field="'+field+'"] select').select2({
			width: '100%',
            closeOnSelect: false
		});
		$('tr#'+rowId+' td[data-field="'+field+'"] select').html(responseData);
		$('tr#'+rowId+' td[data-field="'+field+'"] select').change();
		if($.isArray(selected) && selected.length >0 ) {
			$('tr#' + rowId + ' td[data-field="' + field + '"] select').val(selected);
			$('tr#' + rowId + ' td[data-field="' + field + '"] select').change();
		}

	}
$(document).ready(function() {
	$(document).ajaxComplete(function (event, xhr, settings) {
		var $urlArray = settings.url.split('/');
		if (typeof($urlArray[2]) !== undefined && $urlArray[2] != null) {
			if (settings.url === "locationgroups/save/" + $urlArray[2]) {
				$('.sbox-tools a[data-original-title="Reload Data"]').trigger('click');
			}
		}
	});

	$('.tips').tooltip();



    updateDropdowns('location_ids[]');
    updateDropdowns('excluded_product_ids[]');
    updateDropdowns('excluded_product_type_ids[]');

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
	var singleAjaxCall = true;

	function loadExcludedDropDownData(object) {
		$('.ajaxLoading').show();
		var row = $(object);
		if (singleAjaxCall) {
			singleAjaxCall = false;
			$.ajax({
				url: "locationgroups/excluded-data-inline/" + row.attr('data-id'),
				type: "GET",
				success: function (response) {
					perseReponse(row.attr('id'), 'location_ids', response.locations, response.selectedData.locations);
					perseReponse(row.attr('id'), 'excluded_product_ids', response.products, response.selectedData.products);
					perseReponse(row.attr('id'), 'excluded_product_type_ids', response.productTypes, response.selectedData.productTypes);
					$('.ajaxLoading').hide();
					singleAjaxCall = true;
				}
			});
		}
	}
function deleteLocationGroups(locationGroupId){
    $.ajax({
        headers: {
            'X-CSRF-Token': '{{csrf_token()}}'
        },
        'type' : 'post',
        'url': '/locationgroups/delete/'+locationGroupId,
		'data': {
            'ids': [locationGroupId]
		},
		'error': function (error) {
			console.log(error);
        },
        'success': function(response){
            notyMessage(response.message);
            if(response.status == 'success'){
                $('#form-'+locationGroupId).remove();
				if($(".editable").length == 0){
					$('.table-responsive').html('\n' +
						'            \t<div style="margin:100px 0; text-align:center;">\n' +
						'                    <p class="centralMessage"> No Record Found </p>\n' +
						'                </div>');
				}
			}
		}

    });
}

function notyMessage(message)
{
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-bottom-right",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"

    }
    toastr.success("", message);
}
</script>
<style>
.table th.right { text-align:right !important;}
.table th.center { text-align:center !important;}

</style>
