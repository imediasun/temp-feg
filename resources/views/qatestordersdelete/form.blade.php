
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'qatestordersdelete/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'qatestordersdeleteFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> qatestordersdelete</legend>
				
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
					<label for="User Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('User Id', (isset($fields['user_id']['language'])? $fields['user_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('user_id', $row['user_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Company Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Company Id', (isset($fields['company_id']['language'])? $fields['company_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('company_id', $row['company_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Ordered" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Ordered', (isset($fields['date_ordered']['language'])? $fields['date_ordered']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('date_ordered', $row['date_ordered'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Order Total" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Order Total', (isset($fields['order_total']['language'])? $fields['order_total']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('order_total', $row['order_total'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Warranty" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Warranty', (isset($fields['warranty']['language'])? $fields['warranty']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('warranty', $row['warranty'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Location Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Location Id', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('location_id', $row['location_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					<label for="Order Description" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Order Description', (isset($fields['order_description']['language'])? $fields['order_description']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('order_description', $row['order_description'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Status Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Status Id', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('status_id', $row['status_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Order Type Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Order Type Id', (isset($fields['order_type_id']['language'])? $fields['order_type_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('order_type_id', $row['order_type_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Game Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Id', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_id', $row['game_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Id', (isset($fields['freight_id']['language'])? $fields['freight_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('freight_id', $row['freight_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Po Number" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Po Number', (isset($fields['po_number']['language'])? $fields['po_number']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('po_number', $row['po_number'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Po Notes" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Po Notes', (isset($fields['po_notes']['language'])? $fields['po_notes']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('po_notes', $row['po_notes'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Notes" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('notes', $row['notes'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Received" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Received', (isset($fields['date_received']['language'])? $fields['date_received']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('date_received', $row['date_received'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Received By" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Received By', (isset($fields['received_by']['language'])? $fields['received_by']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('received_by', $row['received_by'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Quantity" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Quantity', (isset($fields['quantity']['language'])? $fields['quantity']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('quantity', $row['quantity'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Alt Address" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Alt Address', (isset($fields['alt_address']['language'])? $fields['alt_address']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('alt_address', $row['alt_address'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Request Ids" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Request Ids', (isset($fields['request_ids']['language'])? $fields['request_ids']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('request_ids', $row['request_ids'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Game Ids" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Ids', (isset($fields['game_ids']['language'])? $fields['game_ids']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_ids', $row['game_ids'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Tracking Number" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Tracking Number', (isset($fields['tracking_number']['language'])? $fields['tracking_number']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('tracking_number', $row['tracking_number'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Added To Inventory" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Added To Inventory', (isset($fields['added_to_inventory']['language'])? $fields['added_to_inventory']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('added_to_inventory', $row['added_to_inventory'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Order Content" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Order Content', (isset($fields['order_content']['language'])? $fields['order_content']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('order_content', $row['order_content'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="New Format" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('New Format', (isset($fields['new_format']['language'])? $fields['new_format']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('new_format', $row['new_format'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Is Partial" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Is Partial', (isset($fields['is_partial']['language'])? $fields['is_partial']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('is_partial', $row['is_partial'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Is Freehand" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Is Freehand', (isset($fields['is_freehand']['language'])? $fields['is_freehand']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('is_freehand', $row['is_freehand'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Invoice Verified" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Invoice Verified', (isset($fields['invoice_verified']['language'])? $fields['invoice_verified']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('invoice_verified', $row['invoice_verified'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Invoice Verified Date" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Invoice Verified Date', (isset($fields['invoice_verified_date']['language'])? $fields['invoice_verified_date']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('invoice_verified_date', $row['invoice_verified_date'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Is Api Visible" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Is Api Visible', (isset($fields['is_api_visible']['language'])? $fields['is_api_visible']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('is_api_visible', $row['is_api_visible'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Api Created At" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Api Created At', (isset($fields['api_created_at']['language'])? $fields['api_created_at']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('api_created_at', $row['api_created_at'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Api Updated At" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Api Updated At', (isset($fields['api_updated_at']['language'])? $fields['api_updated_at']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('api_updated_at', $row['api_updated_at'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Deleted By" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Deleted By', (isset($fields['deleted_by']['language'])? $fields['deleted_by']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('deleted_by', $row['deleted_by'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Deleted At" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Deleted At', (isset($fields['deleted_at']['language'])? $fields['deleted_at']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('deleted_at', $row['deleted_at'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Created At" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Created At', (isset($fields['created_at']['language'])? $fields['created_at']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('created_at', $row['created_at'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Updated At" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Updated At', (isset($fields['updated_at']['language'])? $fields['updated_at']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('updated_at', $row['updated_at'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Po Notes Additionaltext" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Po Notes Additionaltext', (isset($fields['po_notes_additionaltext']['language'])? $fields['po_notes_additionaltext']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('po_notes_additionaltext', $row['po_notes_additionaltext'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
	renderDropdown($(".select2, .select3, .select4, .select5"), { width:"100%"});
	$('.date').datepicker({format:'mm/dd/yyyy',autoclose:true})
	$('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue'
	});			
	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});			
	var form = $('#qatestordersdeleteFormAjax'); 
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