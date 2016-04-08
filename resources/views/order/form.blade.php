
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif

			{!! Form::open(array('url'=>'order/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'orderFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> FEG order</legend>
									
				  <div class="form-group  " > 
					<label for="User Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('User Id', (isset($fields['user_id']['language'])? $fields['user_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='user_id' rows='5' id='user_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Date Ordered" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Date Ordered', (isset($fields['date_ordered']['language'])? $fields['date_ordered']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
                            {!! Form::text('date_ordered', $row['date_ordered'],array('class'=>'form-control date')) !!}
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
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
					<label for="Location Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Location Id', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='location_id' rows='5' id='location_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Vendor Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Vendor Id', (isset($fields['vendor_id']['language'])? $fields['vendor_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='vendor_id' rows='5' id='vendor_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Order Description" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Order Description', (isset($fields['order_description']['language'])? $fields['order_description']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='order_description' rows='5' id='order_description' class='form-control '  
				           >{{ $row['order_description'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Status Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Status Id', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='status_id' rows='5' id='status_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Order Type Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Order Type Id', (isset($fields['order_type_id']['language'])? $fields['order_type_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='order_type_id' rows='5' id='order_type_id' class='select2 ' required  ></select> 
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
					  <textarea name='notes' rows='5' id='notes' class='form-control '  
				         required  >{{ $row['notes'] }}</textarea> 
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

	


<script type="text/javascript">
$(document).ready(function() { 
	
        $("#user_id").jCombo("{{ URL::to('order/comboselect?filter=users:id:username') }}",
        {  selected_value : '{{ $row["user_id"] }}' });
        
        $("#location_id").jCombo("{{ URL::to('order/comboselect?filter=location:id:location_name') }}",
        {  selected_value : '{{ $row["location_id"] }}' });
        
        $("#vendor_id").jCombo("{{ URL::to('order/comboselect?filter=vendor:id:vendor_name') }}",
        {  selected_value : '{{ $row["vendor_id"] }}' });
        
        $("#status_id").jCombo("{{ URL::to('order/comboselect?filter=order_status:id:status') }}",
        {  selected_value : '{{ $row["status_id"] }}' });
        
        $("#order_type_id").jCombo("{{ URL::to('order/comboselect?filter=order_type:id:order_type') }}",
        {  selected_value : '{{ $row["order_type_id"] }}' });
         
	
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	$(".select2").select2({ width:"98%"});	
	$('.date').datepicker({format:'yyyy-mm-dd',autoClose:true})
	$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'}); 
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square_green'
	});			
	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});			
	var form = $('#orderFormAjax'); 
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