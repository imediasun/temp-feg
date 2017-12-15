<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
	<div class="sbox-title">

		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','product/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','product/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
			@if(Session::get('gid') == \App\Models\Core\Groups::SUPPER_ADMIN)
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
                    <div class="sscol-submit"><br/>
                        <button type="button" name="search" class="doSimpleSearch btn btn-sm btn-primary"> Search </button>
                    </div>
                </div>
            @endif
        @endif
        @include( $pageModule.'/toolbar',['colconfigs' => SiteHelpers::getRequiredConfigs($module_id),'prod_list_type'=>$product_list_type,'active'=>$active_prod])

	 <?php echo Form::open(array('url'=>'product/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
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
            @if($setting['disablerowactions']=='false')
                <th width="105"><?php echo Lang::get('core.btn_action') ;?></th>
            @endif
        </tr>
        </thead>


        <tbody>
        	@if($access['is_add'] =='1' && $setting['inline']=='true')
			<tr id="form-0" style="display: none">
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
				{{--commented calculateUnitPrice() function call to allow user to edit unit price--}}
                <tr class="editable" onkeyup="//calculateUnitPrice({{ $row->id }})" id="form-{{ $row->id }}" data-id="{{ $row->id }}" @if($setting['inline']!='false' && $setting['disablerowactions']=='false') ondblclick="showFloatingCancelSave(this)" @endif>
					<input type="hidden" name="numberOfItems" value="{{$row->num_items}}" />
					@if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
						<td class="number"> <?php echo ++$i;?>  </td>
					@endif
						@if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
						<td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
					@endif
					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('product/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>								
					@endif			
					 <?php foreach ($tableGrid as $field) :
					 	if($field['view'] =='1') : 
							$conn = (isset($field['conn']) ? $field['conn'] : array() );
							$value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn,isset($field['nodata'])?$field['nodata']:0);
						 	?>
						 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
						 	@if(SiteHelpers::filterColumn($limited ))
								 <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">					 

                                     @if($field['field']=='img')
										<?php
										 echo SiteHelpers::showUploadedFile($value,'/uploads/products/', 50,false,$row->id)
										 ?>
									@elseif($field['field']=='details')

										<?php

									$trimValue = preg_replace('/\s+/', ' ',$value);


										 if (strlen($trimValue)>20) {

										  echo substr($trimValue,0,20);
										 echo '<br><a href="javascript:void(0)" onclick="showModal(10,this)">Read more</a>';
										 }
										 else{

										 	echo $value;
										 }
?>
                                     @elseif($field['field']=='inactive')
                                         <input type='checkbox' name="mycheckbox" @if($value == "Yes") checked  @endif 	data-size="mini" data-animate="true"
                                                data-on-text="Inactive" data-name="{{$row->vendor_description}}" data-off-text="Active" data-handle-width="50px" class="toggle" data-id="{{$row->id}}"
                                                id="toggle_trigger_{{$row->id}}" onSwitchChange="trigger()" />

                                     @else
									 {!! $value !!}
									@endif

								 </td>
							@endif	
						 <?php endif;					 
						endforeach; 
					  ?>
				 <td data-values="action" data-key="<?php echo $row->id ;?>">
                     {!! AjaxHelpers::GamestitleButtonAction('product',$access,$id ,$setting) !!}
					 <a href="{{ URL::to('product/upload/'.$row->id)}}"class="tips btn btn-xs btn-white"  title="Upload Image"><i class="fa fa-picture-o" aria-hidden="true"></i></a>




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
	@include('ajaxfooter',array('product_list_type'=>$product_list_type,'sub_type'=>$sub_type,'active'=>$active_prod))
	
	</div>
</div>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Product Details</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Dismiss</button>
       
      </div>
    </div>
  </div>
</div>
	@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>

function showModal(id,obj){
	$('#myModal').modal('show');

		var content = $(obj).parent().attr("data-values");
		$('#myModal .modal-body').text(content);

}

$(document).ready(function() {
	//$(".sel-search").select2({ width:"100%"});
    $("[id^='toggle_trigger_']").on('switchChange.bootstrapSwitch', function(event, state) {
        productId=$(this).data('id');
        $.ajax(
            {
                type:'POST',
                url:'product/trigger',
                data:{isActive:state,productId:productId},
                success:function(data){
                    if($('select[name="product_list_type"] :selected').val() == 'productsindevelopment' && state == false)
                    {
                        //window.location.reload();
                        $('#form-'+productId).hide(800);
                    }
                    if(data.status == "error"){
                        //notyMessageError(data.message);
                    }
                }
            }
        );
    });

    $("[id^='toggle_trigger_']").bootstrapSwitch( {onColor: 'default', offColor:'primary'});
	$('.tips').tooltip();
	$('input[type="checkbox"],input[type="radio"]').not('.toggle').iCheck({
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


	function calculateUnitPrice(id){
		var case_price = $('#form-'+id+' input[name = "case_price"]').val();
		var quantity = $('#form-'+id+' input[name = "numberOfItems"]').val();
		var unit_price = case_price/quantity;
		if(quantity != 0 && unit_price != 0) {
			$('#form-'+id+' input[name = "unit_price"]').val(unit_price.toFixed(3));
		}
		else
		{
			$('#form-'+id+' input[name = "unit_price"]').val(0.000);
		}

	}

$('a[data-original-title=Edit]').click(function () {
	$("[id^='toggle_trigger_']").bootstrapSwitch('destroy');
	showAction();
});

$('a[data-original-title=View]').click(function () {
	showAction();
});

$('a[data-original-title="Upload Image"]').click(function () {
	showAction();
});

$( document ).ajaxComplete(function( event, xhr, settings ) {
	console.log(settings);
	var $urlArray = settings.url.split('/');
	console.log($urlArray);
    $('tr td[data-field="expense_category"]').each(function(){
        var ids = $.trim($(this).text());
        ids = ids.trim();
        if(ids !=="No Data") {
           ids = ids.split("|");
           $(this).text(Number(ids[0]));
        }
    });
	if(typeof($urlArray[2]) != "undefined" && $urlArray[2] !== null)
	{
		if ( settings.url === "product/save/"+$urlArray[2] ) {
			var detailText =$('#form-'+$urlArray[2]).children('td[data-field="details"]').text();
			if(detailText.length >= 20){
				var new_details = detailText.substr(0, 20)+'<br><a href="javascript:void(0)" onclick="showModal(10,this)">Read more</a>';
				$('#form-'+$urlArray[2]).children('td[data-field="details"]').empty();
				$('#form-'+$urlArray[2]).children('td[data-field="details"]').html(new_details);
			}
		}
	}

});
	$(function(){

        $.ajax({
            type:"GET",
            data:{DATATEST:1},
            dataType:"HTML",
            url:'product/expense-category-ajax',
            success:function(response){
                console.log(response);
                $(".expense_category").html(response);
                $(".expense_category").change();
            },
            error:function(res){
                console.log(res);
            }
        });
	});
</script>

<style>
    .table th.right {
        text-align: right !important;
    }

    .table th.center {
        text-align: center !important;
    }


	.btn-imagee{

		font-size: 10px; padding: 7px 11px;border: 1px solid transparent;  border-radius: 0px;
		background-color: #428bca;
		border-color: #2a6496;		white-space: nowrap;
		font-family: 'Lato', sans-serif;}

</style>
