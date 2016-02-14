
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'product/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'productFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> FEG Store Products</legend>
									
				  <div class="form-group  " > 
					<label for="Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='id' rows='5' id='id' class='form-control '  
				           >{{ $row['id'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Sku" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Sku', (isset($fields['sku']['language'])? $fields['sku']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='sku' rows='5' id='sku' class='form-control '  
				           >{{ $row['sku'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Vendor Description" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Vendor Description', (isset($fields['vendor_description']['language'])? $fields['vendor_description']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='vendor_description' rows='5' id='vendor_description' class='form-control '  
				           >{{ $row['vendor_description'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Item Description" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Item Description', (isset($fields['item_description']['language'])? $fields['item_description']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='item_description' rows='5' id='item_description' class='form-control '  
				           >{{ $row['item_description'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Size" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Size', (isset($fields['size']['language'])? $fields['size']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='size' rows='5' id='size' class='form-control '  
				           >{{ $row['size'] }}</textarea> 
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
					  <textarea name='num_items' rows='5' id='num_items' class='form-control '  
				           >{{ $row['num_items'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Vendor Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Vendor Id', (isset($fields['vendor_id']['language'])? $fields['vendor_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='vendor_id' rows='5' id='vendor_id' class='form-control '  
				           >{{ $row['vendor_id'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Unit Price" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Unit Price', (isset($fields['unit_price']['language'])? $fields['unit_price']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='unit_price' rows='5' id='unit_price' class='form-control '  
				           >{{ $row['unit_price'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Case Price" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Case Price', (isset($fields['case_price']['language'])? $fields['case_price']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='case_price' rows='5' id='case_price' class='form-control '  
				           >{{ $row['case_price'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Retail Price" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Retail Price', (isset($fields['retail_price']['language'])? $fields['retail_price']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='retail_price' rows='5' id='retail_price' class='form-control '  
				           >{{ $row['retail_price'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Ticket Value" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])? $fields['ticket_value']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='ticket_value' rows='5' id='ticket_value' class='form-control '  
				           >{{ $row['ticket_value'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Prod Type Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Prod Type Id', (isset($fields['prod_type_id']['language'])? $fields['prod_type_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='prod_type_id' rows='5' id='prod_type_id' class='form-control '  
				           >{{ $row['prod_type_id'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Prod Sub Type Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Prod Sub Type Id', (isset($fields['prod_sub_type_id']['language'])? $fields['prod_sub_type_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='prod_sub_type_id' rows='5' id='prod_sub_type_id' class='form-control '  
				           >{{ $row['prod_sub_type_id'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Is Reserved" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Is Reserved', (isset($fields['is_reserved']['language'])? $fields['is_reserved']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='is_reserved' rows='5' id='is_reserved' class='form-control '  
				           >{{ $row['is_reserved'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Reserved Qty" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Reserved Qty', (isset($fields['reserved_qty']['language'])? $fields['reserved_qty']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='reserved_qty' rows='5' id='reserved_qty' class='form-control '  
				           >{{ $row['reserved_qty'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Min Order Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Min Order Amt', (isset($fields['min_order_amt']['language'])? $fields['min_order_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='min_order_amt' rows='5' id='min_order_amt' class='form-control '  
				           >{{ $row['min_order_amt'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Img" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Img', (isset($fields['img']['language'])? $fields['img']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='img' rows='5' id='img' class='form-control '  
				           >{{ $row['img'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Inactive" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Inactive', (isset($fields['inactive']['language'])? $fields['inactive']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='inactive' rows='5' id='inactive' class='form-control '  
				           >{{ $row['inactive'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Eta" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Eta', (isset($fields['eta']['language'])? $fields['eta']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='eta' rows='5' id='eta' class='form-control '  
				           >{{ $row['eta'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="In Development" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('In Development', (isset($fields['in_development']['language'])? $fields['in_development']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='in_development' rows='5' id='in_development' class='form-control '  
				           >{{ $row['in_development'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Limit To Loc Group Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Limit To Loc Group Id', (isset($fields['limit_to_loc_group_id']['language'])? $fields['limit_to_loc_group_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='limit_to_loc_group_id' rows='5' id='limit_to_loc_group_id' class='form-control '  
				           >{{ $row['limit_to_loc_group_id'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Date Added" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Date Added', (isset($fields['date_added']['language'])? $fields['date_added']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='date_added' rows='5' id='date_added' class='form-control '  
				           >{{ $row['date_added'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Hot Item" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Hot Item', (isset($fields['hot_item']['language'])? $fields['hot_item']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='hot_item' rows='5' id='hot_item' class='form-control '  
				           >{{ $row['hot_item'] }}</textarea> 
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
	$(".select2").select2({ width:"98%"});	
	$('.date').datepicker({format:'yyyy-mm-dd',autoClose:true})
	$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'}); 
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
	});			
	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});			
	var form = $('#productFormAjax'); 
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
		$('#sximo-modal').modal('hide');	
	} else {
		notyMessageError(data.message);	
		$('.ajaxLoading').hide();
		return false;
	}	
}			 

</script>		 