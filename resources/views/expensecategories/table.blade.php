<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
	<div class="sbox-title">
		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','expensecategories/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','expensecategories/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
			<?php echo "<script> var switch_filters = '$return';</script>" ?>
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
				<div class="sscol-submit col-md-3 col-sm-3" style="margin-top: 4px;"><br>
				<input type='checkbox' name="filter_trigger" data-size="mini" data-handle-width="40px" data-on-text="ON" data-off-text="OFF" id="filter_trigger" onSwitchChange="trigger()" />
				<span>&nbsp; Display Product Types Only</span>
				</div>
        </div>
        @endif
        @endif
        
        @include( $pageModule.'/toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])

	 <?php echo Form::open(array('url'=>'expensecategories/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
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
					<td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
                    @endif
					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('expensecategories/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
					@endif
					 <?php foreach ($tableGrid as $field) :
					 	if($field['view'] =='1') :
							$conn = (isset($field['conn']) ? $field['conn'] : array() );


							$value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn,$field['nodata']);
						 	?>
						 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
						 	@if(SiteHelpers::filterColumn($limited ))
								 <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{!!  ($field['field'] == 'mapped_expense_category')?$field['field']:'' !!}" data-format="{{ htmlentities($value) }}">
									{!! $value !!}
								 </td>
							@endif
                    <?php
						 endif;
						endforeach;
					  ?>
                  @if($setting['disablerowactions']=='false')     
				 <td data-values="action" data-key="<?php echo $row->id ;?>">
					{!! AjaxHelpers::buttonAction('expensecategories',$access,$id ,$setting) !!}

					{{--<a href="javascript:void(0)"--}}
						{{--data-id="{{$row->id}}"--}}
						{{--data-ordertype = "{{$row->order_type}}"--}}
						{{--data-producttype = "{{$row->product_type}}"--}}
						{{--data-expense_category = "{{$row->mapped_expense_category}}"--}}
						{{--data-action="removal"--}}
						{{--class="tips btn btn-xs btn-white expenseCategoryDeleteRequest"--}}
						{{--title="Delete Expense Category">--}}
						 {{--<i class="fa fa-trash-o " aria-hidden="true"></i>--}}
					 {{--</a>--}}
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
    
    // Configure data grid columns for sorting 
    initDataGrid('{{ $pageModule }}', '{{ $pageUrl }}');

	$('.expenseCategoryDeleteRequest').click(function () {

		console.log('debuge here');
		if (confirm("Are you sure you want to delete this!") == false) {
			return;
		}

		blockUI();

		var id = $(this).data('id');
		var order_type = $(this).data('ordertype');
		var product_type = $(this).data('producttype');
		var expense_category = $(this).data('expense_category');


		$.ajax({
			type: 'POST',
			url: "{{ url() }}/expensecategories/single-delete",
			data: {id : id, order_type : order_type, product_type : product_type, expense_category : expense_category},
			success: function(data) {
				console.log(data);
				reloadData('#{{ $pageModule }}','{{ $pageModule }}/data');
			},
			error: function(data) {
				console.log(data);
				unblockUI();
			}
		});

	});

	$('#filter_trigger').iCheck('destroy');
	$("#filter_trigger").bootstrapSwitch('state',{{\Session::get('filter_toggle')}});
	$("#filter_trigger").on('switchChange.bootstrapSwitch', function(event, state) {
		console.log(state+': {{ $return }}');
		if(state){
			reloadData('#{{ $pageModule }}','expensecategories/data?display_filter=yes&return='+switch_filters);
		}else{
			reloadData('#{{ $pageModule }}','expensecategories/data?display_filter=no&return='+switch_filters);
		}
	});
});
</script>
<style>
.table th.right { text-align:right !important;}
.table th.center { text-align:center !important;}

</style>
