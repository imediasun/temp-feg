
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif
			{!! Form::open(array('url'=>'shopfegrequeststore/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'shopfegrequeststoreFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Shop FEG Request Store</legend>
									
				  <div class="form-group  " > 
					<label for="Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " >
					<label for="Sku" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Sku', (isset($fields['sku']['language'])? $fields['sku']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('sku', $row['sku'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " > 
					<label for="Vendor Description" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Vendor Description', (isset($fields['vendor_description']['language'])? $fields['vendor_description']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('vendor_description', $row['vendor_description'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Item Description" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Item Description', (isset($fields['item_description']['language'])? $fields['item_description']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('item_description', $row['item_description'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Size" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Size', (isset($fields['size']['language'])? $fields['size']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('size', $row['size'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Details" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Details', (isset($fields['details']['language'])? $fields['details']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='details' rows='5' id='details' class='form-control '  
				           >{{ $row['details'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Num Items" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Num Items', (isset($fields['num_items']['language'])? $fields['num_items']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('num_items', $row['num_items'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Vendor Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Vendor Id', (isset($fields['vendor_id']['language'])? $fields['vendor_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('vendor_id', $row['vendor_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Unit Price" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Unit Price', (isset($fields['unit_price']['language'])? $fields['unit_price']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
						{!! Form::text('unit_price', number_format((double)$row['unit_price'],2),array('class'=>'form-control', 'placeholder'=>'0.00','required'=>'required','input type'=>'number', 'value'=>'0.00', 'min' => '0','step'=>'1' )) !!}
					 </div> 
					 <div class="col-md-2">



					 </div>
				  </div> 					
				  <div class="form-group  " >
					<label for="Case Price" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Case Price', (isset($fields['case_price']['language'])? $fields['case_price']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('case_price', $row['case_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " > 
					<label for="Retail Price" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Retail Price', (isset($fields['retail_price']['language'])? $fields['retail_price']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('retail_price', $row['retail_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Ticket Value" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])? $fields['ticket_value']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('ticket_value', $row['ticket_value'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Prod Type Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Prod Type Id', (isset($fields['prod_type_id']['language'])? $fields['prod_type_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
						{!! Form::select('prod_type_id', DB::table('product_type')->lists('product_type','id'), $row['prod_type_id'],array('class'=>'form-control') ) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Prod Sub Type Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Prod Sub Type Id', (isset($fields['prod_sub_type_id']['language'])? $fields['prod_sub_type_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
						{!! Form::select('prod_sub_type_id', DB::table('product_type')->lists('product_type','id'), $row['prod_sub_type_id'],array('class'=>'form-control') ) !!}
					</div>
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Is Reserved" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Is Reserved', (isset($fields['is_reserved']['language'])? $fields['is_reserved']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::checkbox('is_reserved', 1,$row['is_reserved']) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Reserved Qty" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Reserved Qty', (isset($fields['reserved_qty']['language'])? $fields['reserved_qty']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('reserved_qty', $row['reserved_qty'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Min Order Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Min Order Amt', (isset($fields['min_order_amt']['language'])? $fields['min_order_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('min_order_amt', $row['min_order_amt'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Img" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Img', (isset($fields['img']['language'])? $fields['img']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('img', $row['img'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Inactive" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Inactive', (isset($fields['inactive']['language'])? $fields['inactive']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('inactive', $row['inactive'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Eta" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Eta', (isset($fields['eta']['language'])? $fields['eta']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('eta', $row['eta'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="In Development" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('In Development', (isset($fields['in_development']['language'])? $fields['in_development']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::checkbox('in_development', 1,$row['in_development']) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Limit To Loc Group Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Limit To Loc Group Id', (isset($fields['limit_to_loc_group_id']['language'])? $fields['limit_to_loc_group_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('limit_to_loc_group_id', $row['limit_to_loc_group_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Date Added" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Date Added', (isset($fields['date_added']['language'])? $fields['date_added']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('date_added', date("m/d/Y", strtotime($row['date_added'])),array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Hot Item" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Hot Item', (isset($fields['hot_item']['language'])? $fields['hot_item']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::checkbox('hot_item', 1,$row['hot_item']) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> </fieldset>
			</div>
			
												
								
						
			<div style="clear:both"></div>	
							
			<div class="form-group">
				<label class="col-sm-4 text-right">&nbsp;</label>
				<div class="col-sm-8">	
					<button type="submit" class="btn btn-primary btn-sm "><i class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
					<button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm"><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
				</div>			
			</div> 		 
			{!! Form::close() !!}


@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

	
</div>	
			 
<script type="text/javascript">
$(document).ready(function() { 
	 
	
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	renderDropdown($(".select2 "), { width:"98%"});
	$('.date').datepicker({format:'mm/dd/yyyy',autoclose:true})
	$('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
	});			
	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});			
	var form = $('#shopfegrequeststoreFormAjax'); 
	form.parsley();
	form.submit(function(){
		
		if(form.parsley('isValid') == true){			
			var options = { 
				dataType:      'json', 
				beforeSubmit :  showRequest,
				success:       showResponse  
			}  
			$(this).ajaxSubmit(options); 
			return false;
						
		} else {
			return false;
		}		
	
	});

});

function showRequest()
{
	$('.ajaxLoading').show();		
}  
function showResponse(data)  {		
	
	if(data.status == 'success')
	{
		ajaxViewClose('#{{ $pageModule }}');
		ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
		notyMessage(data.message);
        $('.ajaxLoading').hide();
		$('#sximo-modal').modal('hide');	
	} else {
		notyMessageError(data.message);	
		$('.ajaxLoading').hide();
		return false;
	}	
}			 

</script>		 
