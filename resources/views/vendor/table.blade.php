<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
	<div class="sbox-title"> 
		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','vendor/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','vendor/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
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

        @include( $pageModule.'/toolbar',['colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])

	 <?php echo Form::open(array('url'=>'vendor/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
<div class="table-responsive">	
	@if(count($rowData)>=1)
    <table class="table table-striped datagrid " id="{{ $pageModule }}Table" style="table-layout: fixed">
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
                <th width="130"><?php echo Lang::get('core.btn_action') ;?></th>
            @endif
        </tr>
        </thead>

        <tbody>
        @if(($access['is_add'] =='1' || $access['is_edit']=='1' ) && $setting['inline']=='true' )
			<tr id="form-0" >
				<td> # </td>
				@if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
					<td> </td>
				@endif
				@if($setting['view-method']=='expand') <td> </td> @endif
				@foreach ($tableGrid as $t)
					@if(isset($t['inline']) && $t['inline'] =='1')
					<?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
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
			
           		<?php foreach ($rowData as $row) : 
           			  $id = $row->id;
           		?>
            <tr @if($access['is_edit']=='1' && $setting['inline']=='true' )class="editable"
                @endif id="form-{{ $row->id }}"
                @if($setting['inline']!='false' && $setting['disablerowactions']=='false') data-id="{{ $row->id }}"
                @if($access['is_edit']=='1' && $setting['inline']=='true' )ondblclick="showFloatingCancelSave(this)" @endif @endif>
					@if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
						<td class="number"> <?php echo ++$i;?>  </td>
					@endif
						@if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
						<td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
					@endif
					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('vendor/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>								
					@endif			
					 <?php foreach ($tableGrid as $field) :
					 	if($field['view'] =='1') : 
							$conn = (isset($field['conn']) ? $field['conn'] : array() );
							$value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn,isset($field['nodata'])?$field['nodata']:0);
						 	?>
						 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
						 	@if(SiteHelpers::filterColumn($limited ))
								@if($field['field']=='status')
									<td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
										<input type='checkbox' name="mycheckbox" @if($value == 1) checked  @endif 	data-size="mini" data-animate="true"
									   		data-on-text="Active" data-field="status" data-off-text="Inactive" data-handle-width="50px" class="toggle" data-id="{{$row->id}}"
									   		id="toggle_trigger_{{$row->id}}" onSwitchChange="trigger()" />
									</td>

								@elseif($field['field']=='hide')
									<td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
										<input type='checkbox' name="mycheckbox" @if($value == 1) checked  @endif 	data-size="mini" data-animate="true"
											   data-on-text="Yes" data-field="hide" data-off-text="No" data-handle-width="50px" class="toggle" data-id="{{$row->id}}"
											   id="toggle_trigger_{{$row->id}}" onSwitchChange="trigger()" />
									</td>
								@else
									<td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
										{!! $value !!}
								 	</td>
								@endif
							@endif	
						 <?php endif;					 
						endforeach; 
					  ?>

				 <td data-values="action" data-key="<?php echo $row->id ;?>">
					{!! AjaxHelpers::buttonAction('vendor',$access,$id ,$setting) !!}
					{!! AjaxHelpers::buttonActionInline($row->id,'id') !!}
                            <!--Send vendor list button-->
                      @if(($row->email != '' || $row->email_2 != '') && ($viewProductListExportOption || $row->ismerch == 1))
                         <a href="javascript://ajax" onclick="ajaxSendProductList('{{ URL::to('vendor/send-list/'.$row->id)}}');" class="tips btn btn-xs btn-white" title="Send List">
                             <i class="fa fa-envelope" aria-hidden="true"></i>
                         </a>
                         <!--Schedule vendor list button-->
                         <!--<a href="{{ URL::to('vendor/schedule-list/'.$row->id)}}" onclick="ajaxViewDetail('#vendor',this.href); return false; " class="tips btn btn-xs btn-white" title="Schedule List">
                             <i class="fa fa-calendar" aria-hidden="true"></i>
                         </a>--->
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
	<?php echo Form::close() ;?>
	@include('ajaxfooter')
	
	</div>
</div>	
	
	@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
$(document).ready(function() {
    $("[id^='toggle_trigger_']").on('switchChange.bootstrapSwitch', function(event, state) {


        var vendorId=$(this).data('id');
        var field=$(this).data('field');
        var check =  (field == 'hide');
        var message = '';
        var check2 = check;
        if(state)
        {
            if(check)
            {
                message = "<div class='confirm_inactive'><br>Are you sure you want to Hide this Vendor <br> <b>***WARNING***</b><br> if you Hide this Vendor then this vendor will not be available and all products of this vendor will not be able to add to cart.</div>";
            }
            else {
                check2 = !check;
                message = "<div class='confirm_inactive'><br>Are you sure you want to Active this Vendor <br> <b>***WARNING***</b><br> if you active this Vendor then this vendor will be available and all products of this vendor will be able to add to cart.</div>";
            }
        }
        else
        {
            if(check)
            {
                check2 = !check;
                message = "<div class='confirm_inactive'><br>Are you sure you want to make visible this Vendor <br> <b>***WARNING***</b><br> if you make visible this Vendor then this vendor will be available and all products of this vendor will be able to add to cart.</div>";
            }
            else
            {
                message = "<div class='confirm_inactive'><br>Are you sure you want to Inactive this Vendor <br> <b>***WARNING***</b><br> if you Inactive this Vendor then this vendor will not be available and all products of this vendor will not be able to add to cart.</div>";
            }
        }

        currentElm = $(this);
        currentElm.bootstrapSwitch('state', !check2,true);
        $('.custom_overlay').show();
        App.notyConfirm({
            message: message,
            confirmButtonText: 'Yes',
            confirm: function (){
                $('.custom_overlay').slideUp(500);
                $.ajax(
                    {
                        type:'POST',
                        url:'vendor/trigger',
                        data:{isActive:state,field:field,vendorId:vendorId},
                        success:function(data){
                            currentElm.bootstrapSwitch('state', check2,true);
                            if($('select[name="status"] :selected').val() == 1 && state == false && field == 'status')
                            {
                                $('#form-'+vendorId).hide(500);
                                $('#divOverlay_'+vendorId).hide(500);
                            }
                            else if($('select[name="status"] :selected').val() == 0 && state == true && field == 'status')
                            {
                                $('#form-'+vendorId).hide(500);
                                $('#divOverlay_'+vendorId).hide(500);
                            }
                            if($('select[name="hide"] :selected').val() == 1 && state == false && field == 'hide')
                            {
                                $('#form-'+vendorId).hide(500);
                                $('#divOverlay_'+vendorId).hide(500);
                            }
                            else if($('select[name="hide"] :selected').val() == 0 && state == true && field == 'hide')
                            {
                                $('#form-'+vendorId).hide(500);
                                $('#divOverlay_'+vendorId).hide(500);
                            }
                            if(data.status == "error"){
                                notyMessageError(data.message);
                            }
                        }
                    }
                );
            },
            cancel: function () {
                $('.custom_overlay').slideUp(500);
            }
        });

    });

    $("[id^='toggle_trigger_']").bootstrapSwitch();
	$('.tips').tooltip();	
	$('input[type="checkbox"],input[type="radio"]').not('.toggle').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
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
    .table th.right { text-align:right !important;}
    .table th.center { text-align:center !important;}

</style>
