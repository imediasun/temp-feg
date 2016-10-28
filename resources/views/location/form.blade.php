
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'location/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'locationFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> location</legend>
				
				  <div class="form-group  " >
					<label for="Active Location" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Active Location', (isset($fields['active']['language'])? $fields['active']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $active = explode(",",$row['active']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='active' value ='1'
					@if(in_array('',$active))checked @endif
					 />  </label> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Location Detail" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Location Detail', (isset($fields['ipaddress']['language'])? $fields['ipaddress']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('ipaddress', $row['ipaddress'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="FEG Owned" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('FEG Owned', (isset($fields['self_owned']['language'])? $fields['self_owned']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $self_owned = explode(",",$row['self_owned']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='self_owned' value ='1'
					@if(in_array('',$self_owned))checked @endif
					 />  </label> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="ID" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('ID', (isset($fields['id']['language'])? $fields['id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Location Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Location Name', (isset($fields['location_name']['language'])? $fields['location_name']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('location_name', $row['location_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Short Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Short Name', (isset($fields['location_name_short']['language'])? $fields['location_name_short']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('location_name_short', $row['location_name_short'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Region " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Region ', (isset($fields['region_id']['language'])? $fields['region_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='region_id' rows='5' id='region_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Company " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Company ', (isset($fields['company_id']['language'])? $fields['company_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='company_id' rows='5' id='company_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Contact " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Contact ', (isset($fields['contact_id']['language'])? $fields['contact_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='contact_id' rows='5' id='contact_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Merch Contact " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Merch Contact ', (isset($fields['merch_contact_id']['language'])? $fields['merch_contact_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='merch_contact_id' rows='5' id='merch_contact_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Field Manager Contact" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Field Manager Contact', (isset($fields['field_manager_id']['language'])? $fields['field_manager_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='field_manager_id' rows='5' id='field_manager_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="District Manager " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('District Manager ', (isset($fields['tech_manager_id']['language'])? $fields['tech_manager_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='tech_manager_id' rows='5' id='tech_manager_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div>


							<div class="form-group  " >
								<label for="General Contact " class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('General Contact ', (isset($fields['general_contact_id']['language'])? $fields['general_contact_id']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									<select name='general_contact_id' rows='5' id='general_contact_id' class='select2 '   ></select>
								</div>
								<div class="col-md-2">

								</div>
							</div>



							<div class="form-group  " >
								<label for="Merchandise Contact " class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Merchandise Contact ', (isset($fields['merchandise_contact_id']['language'])? $fields['merchandise_contact_id']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									<select name='merchandise_contact_id' rows='5' id='merchandise_contact_id' class='select2 '   ></select>
								</div>
								<div class="col-md-2">

								</div>
							</div>




							<div class="form-group  " >
								<label for="Technical Contact " class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Technical Contact ', (isset($fields['technical_contact_id']['language'])? $fields['technical_contact_id']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									<select name='technical_contact_id' rows='5' id='technical_contact_id' class='select2 '   ></select>
								</div>
								<div class="col-md-2">

								</div>
							</div>



							<div class="form-group  " >
								<label for="Regional Contact " class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Regional Contact ', (isset($fields['regional_contact_id']['language'])? $fields['regional_contact_id']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									<select name='regional_contact_id' rows='5' id='regional_contact_id' class='select2 '   ></select>
								</div>
								<div class="col-md-2">

								</div>
							</div>

							<div class="form-group  " >
								<label for="Senior VP " class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Senior VP ', (isset($fields['senior_vp_id']['language'])? $fields['senior_vp_id']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									<select name='senior_vp_id' rows='5' id='senior_vp_id' class='select2 '   ></select>
								</div>
								<div class="col-md-2">

								</div>
							</div>

							<div class="form-group  " >
					<label for="Street" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Street', (isset($fields['street1']['language'])? $fields['street1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('street1', $row['street1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="City" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('City', (isset($fields['city']['language'])? $fields['city']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('city', $row['city'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="State" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('State', (isset($fields['state']['language'])? $fields['state']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('state', $row['state'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Zip Code" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Zip Code', (isset($fields['zip']['language'])? $fields['zip']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('zip', $row['zip'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="ATTN" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('ATTN', (isset($fields['attn']['language'])? $fields['attn']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('attn', $row['attn'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Shipping Restrictions" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Shipping Restrictions', (isset($fields['loading_info']['language'])? $fields['loading_info']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loading_info', $row['loading_info'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Phone" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Phone', (isset($fields['phone']['language'])? $fields['phone']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('phone', $row['phone'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Bestbuy #" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Bestbuy #', (isset($fields['bestbuy_store_number']['language'])? $fields['bestbuy_store_number']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('bestbuy_store_number', $row['bestbuy_store_number'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Can Ship" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Can Ship', (isset($fields['can_ship']['language'])? $fields['can_ship']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $can_ship = explode(",",$row['can_ship']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='can_ship' value ='1'
					@if(in_array('',$can_ship))checked @endif
					 />  </label> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Requires Liftgate" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Requires Liftgate', (isset($fields['liftgate']['language'])? $fields['liftgate']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $liftgate = explode(",",$row['liftgate']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='liftgate' value ='1'
					@if(in_array('',$liftgate))checked @endif
					 />  </label> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Alt Ship" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Alt Ship', (isset($fields['loc_ship_to']['language'])? $fields['loc_ship_to']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='loc_ship_to' rows='5' id='loc_ship_to' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Ordering Group" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Ordering Group', (isset($fields['loc_group_id']['language'])? $fields['loc_group_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='loc_group_id' rows='5' id='loc_group_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="On Debit" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('On Debit', (isset($fields['debit_type_id']['language'])? $fields['debit_type_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='debit_type_id' rows='5' id='debit_type_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for=" Debit Type" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang(' Debit Type', (isset($fields['bill_debit_type']['language'])? $fields['bill_debit_type']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $bill_debit_type = explode(",",$row['bill_debit_type']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='bill_debit_type' value ='1'
					@if(in_array('',$bill_debit_type))checked @endif
					 />  </label> 
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
	
        $("#region_id").jCombo("{{ URL::to('location/comboselect?filter=region:id:region') }}",
        {  selected_value : '{{ $row["region_id"] }}' });
        
        $("#company_id").jCombo("{{ URL::to('location/comboselect?filter=company:id:company_name_long') }}",
        {  selected_value : '{{ $row["company_id"] }}' });
        
        $("#contact_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
        {  selected_value : '{{ $row["contact_id"] }}' });
        
        $("#merch_contact_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
        {  selected_value : '{{ $row["merch_contact_id"] }}' });
        
        $("#field_manager_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
        {  selected_value : '{{ $row["field_manager_id"] }}' });

	$("#tech_manager_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
			{  selected_value : '{{ $row["tech_manager_id"] }}' });
        
        $("#general_contact_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
        {  selected_value : '{{ $row["general_contact_id"] }}' });

	$("#merchandise_contact_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
			{  selected_value : '{{ $row["merchandise_contact_id"] }}' });
		$("#technical_contact_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
			{  selected_value : '{{ $row["technical_contact_id"] }}' });

		$("#regional_contact_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
			{  selected_value : '{{ $row["regional_contact_id"] }}' });

		$("#senior_vp_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
			{  selected_value : '{{ $row["senior_vp_id"] }}' });


        
        $("#loc_ship_to").jCombo("{{ URL::to('location/comboselect?filter=location:id:location_name') }}",
        {  selected_value : '{{ $row["loc_ship_to"] }}' });
        
        $("#loc_group_id").jCombo("{{ URL::to('location/comboselect?filter=loc_group:id:loc_group_name') }}",
        {  selected_value : '{{ $row["loc_group_id"] }}' });
        
        $("#debit_type_id").jCombo("{{ URL::to('location/comboselect?filter=debit_type:id:company') }}",
        {  selected_value : '{{ $row["debit_type_id"] }}' });
         
	
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	$(".select2").select2({ width:"98%"});	
	$('.date').datepicker({format:'mm/dd/yyyy',autoClose:true})
	$('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
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
	var form = $('#locationFormAjax'); 
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