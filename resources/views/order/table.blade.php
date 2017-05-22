<?php usort($tableGrid, "SiteHelpers::_sort");
?>
<div class="sbox">
	<div class="sbox-title">
		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','order/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','order/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
			@if(Session::get('gid') ==10)
			<a href="{{ url('feg/module/config/'.$pageModule) }}" 
               class="btn btn-xs btn-white tips openModuleConfig"
               title=" {{ Lang::get('core.btn_config') }}"
               ><i class="fa fa-cog"></i></a>
            <a href="{{ url('feg/module/special-permissions/'.$pageModule.'/solo') }}"
                   class="btn btn-xs btn-white tips openSpecialPermissions" title="Special Permissions"
                ><i class="fa fa-sliders"></i></a>
			@endif
		</div>
	</div>
	<div class="sbox-content" style="padding-bottom:0">
        <?php
         $searched=\Request::get('search');
         $searched=explode("|",$searched);
        $searchedParams=[];
        foreach($searched as $t)
            {
                $searchedParams[]=explode(':',$t);
            }
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
                            }
                                if($t['field'] == "order_type_id")
                                {
                                    $fv=\Session::get('order_selected');
                                }
                            ?>
                            {!! SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) !!}
                            {!! SiteHelpers::transForm($t['field'] , $simpleSearchForm,false,$fv) !!}
                        </div>
                    @endforeach
                    {!! SiteHelpers::generateSimpleSearchButton($setting) !!}
                </div>
            @endif
        @endif
        @include( $pageModule.'/toolbar',['colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])
			<div class="sbox-content" style="border: medium none; padding-top: 15px;">
            @if ($access['is_remove'] ==1 || !empty($pass['Can remove order']))
                <?php echo Form::open(array('url'=>'order/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
            @endif
<div class="table-responsive">
	@if(count($rowData)>=1)
    <table class="table table-striped datagrid " id="{{ $pageModule }}Table" style="position:relative">
        <thead>
        <tr class="row-">
            @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                <th width="35"> No </th>
            @endif
				@if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1' || !empty($pass['Can remove order'])))
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
                <th width="190"><?php echo Lang::get('core.btn_action') ;?></th>
            @endif
        </tr>
        </thead>
        <tbody>
        	@if($access['is_add'] =='1' && $setting['inline']=='true')
			<tr id="form-0">
				<td class="cell"> # </td>
				@if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1' || !empty($pass['Can remove order'])))
					<td class="cell"> </td>
				@endif
				@if($setting['view-method']=='expand') <td> </td> @endif
				@foreach ($tableGrid as $t)
					@if(isset($t['inline']) && $t['inline'] =='1')
					<?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
						@if(SiteHelpers::filterColumn($limited ))
						<td class="cell" data-form="{{ $t['field'] }}" data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
							{!! SiteHelpers::transInlineForm($t['field'] , $tableForm) !!}
						</td>
						@endif
					@endif
				@endforeach
				<td class="cell">
					<button onclick="saved('form-0')" class="btn btn-primary btn-xs" type="button"><i class="fa  fa-save"></i></button>
				</td>
			  </tr>
			  @endif

           		<?php foreach ($rowData as $row) :
           			  $id = $row->id;
           		?>

                <tr  class="editable" data-id="{{ $row->id }}" id="form-{{ $row->id }}" @if($setting['inline']!='false' && $setting['disablerowactions']=='false') ondblclick="showFloatingCancelSave(this)" @endif>

					@if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
						<td class="number"> <?php echo ++$i;?>  </td>
					@endif
						@if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1' || !empty($pass['Can remove order'])))
						<td><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
					@endif


					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('order/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
					@endif
					 <?php foreach ($tableGrid as $field) :
					 	if($field['view'] =='1') :
							$conn = (isset($field['conn']) ? $field['conn'] : array() );
							$value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn,isset($field['nodata'])?$field['nodata']:0);
						 	?>
						 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
						 	@if(SiteHelpers::filterColumn($limited ))
								 <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">

							{!! $value !!}
							</td>
							@endif
						 <?php endif;
						endforeach;
					  ?>


				 <td data-values="action" data-key="<?php echo $row->id ;?>">
					{!! AjaxHelpers::GamestitleButtonAction('order',$access,$id ,$setting) !!}



                        <a href="{{ URL::to('order/po/'.$row->id)}}"  class="tips btn btn-xs btn-white" title="Generate PO"><i class="fa fa-cogs" aria-hidden="true"></i></a>

                     <a href="{{ $pageModule }}/update/{{$row->id}}/clone"  onclick="ajaxViewDetail('#order',this.href); return false; "  class="tips btn btn-xs btn-white" title="Clone Order"><i class=" fa fa-random" aria-hidden="true"></i></a>


                    @if($row->status_id=='Open' || $row->status_id=='Open (Partial)')
                        <a href="{{ URL::to('order/orderreceipt/'.$row->id)}}" class="tips btn btn-xs btn-white" title="Receive Order"><i class="fa fa fa-truck" aria-hidden="true"></i></a>
                   @endif
					@if($row->status_id=='Open' || $row->status_id=='Open (Partial)')
						<a href="{{ URL::to('order/removalrequest/'.$row->po_number)}}" class="tips btn btn-xs btn-white" title="Request Removal"><i class="fa fa-trash-o " aria-hidden="true"></i></a>
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

		<p> No Record Found </p>
	</div>

	@endif

	</div>
            @if ($access['is_remove'] == 1 || !empty($pass['Can remove order']))
                <?php echo Form::close() ;?>
            @endif
	@include('ajaxfooter')

	</div>
            @if($setting['inline']!='false' && $setting['disablerowactions']=='false')
                @foreach ($rowData as $row)
                    {!! AjaxHelpers::buttonActionInline($row->id,'id') !!}
                @endforeach
            @endif
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

