
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif
        {!! Form::open(array('url'=>'location/save/'.$row['id'], 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'locationFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> location</legend>
                            <div class="form-group  " >
                                <label for="Short Name" class=" control-label col-md-4 text-left">
                                    {!! SiteHelpers::activeLang('Location Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) !!}
                                </label>
                                <div class="col-md-6">
                                    {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'','id'=>'location_id','required'=>'required'   )) !!}
									<div id="location_available">

									</div>
                                </div>
                            </div>
				  <div class="form-group  " >
					<label for="Active Location" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Active Location', (isset($fields['active']['language'])? $fields['active']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
                        <input type ="text"  value="0" name="active" style="display:none"/>
					  <?php $active = $row['active']; ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='active' value ='1'
					@if($active == 1)checked @endif
					 />  </label> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				{{--  <div class="form-group  " >
					<label for="Location Detail" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Location Detail', (isset($fields['ipaddress']['language'])? $fields['ipaddress']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('ipaddress', $row['ipaddress'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> --}}
				  <div class="form-group  " >
					<label for="FEG Owned" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('FEG Owned', (isset($fields['self_owned']['language'])? $fields['self_owned']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
                        <input type ="text"  value="0" name="self_owned" style="display:none"/>
					  <?php $self_owned = $row['self_owned']; ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='self_owned' value ='1'
					@if($self_owned == 1)checked @endif
					 />  </label> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				{{--  <div class="form-group  " >
					<label for="ID" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('ID', (isset($fields['id']['language'])? $fields['id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> --}}
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
                        {!! SiteHelpers::activeLang('Inernal Contact ', (isset($fields['contact_id']['language'])? $fields['contact_id']['language'] : array())) !!}
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
                        <label for="general_manager_id" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('General Manager', (isset($fields['general_manager_id']['language'])? $fields['general_manager_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                          <select name='general_manager_id' rows='5' id='general_manager_id' class='select2 '   ></select>
                         </div> 
                         <div class="col-md-2">

                         </div>
                    </div> 
                    <div class="form-group  " >
                        <label for="technical_user_id" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('Technical', (isset($fields['technical_user_id']['language'])? $fields['technical_user_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                          <select name='technical_user_id' rows='5' id='technical_user_id' class='select2 '   ></select>
                         </div> 
                         <div class="col-md-2">

                         </div>
                    </div>
                    <div class="form-group  " >
                        <label for="regional_manager_id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Regional Manager', (isset($fields['regional_manager_id']['language'])? $fields['regional_manager_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <select name='regional_manager_id' rows='5' id='regional_manager_id' class='select2 '   ></select>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  " >
                        <label for="vp_id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('VP', (isset($fields['vp_id']['language'])? $fields['vp_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <select name='vp_id' rows='5' id='vp_id' class='select2 '   ></select>
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
                        <input type ="text"  value="0" name="can_ship" style="display:none"/>
					  <?php $can_ship = $row['can_ship']; ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='can_ship' value ='1'
					@if($can_ship == 1)checked @endif
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
                        <input type ="text"  value="0" name="liftgate" style="display:none!important"/>
					  <?php $liftgate = $row['liftgate']; ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='liftgate' value ='1'
					@if($liftgate == 1)checked @endif
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
                                <label for="reporting" class=" control-label col-md-4 text-left">
                                    {!! SiteHelpers::activeLang('Reporting', (isset($fields['reporting']['language'])? $fields['reporting']['language'] : array())) !!}
                                </label>
                                <div class="col-md-6">
                                    <input type ="text"  value="0" name="reporting" style="display:none"/>
                                    <?php $reporting = $row['reporting']; ?>
                                    <label class='checked checkbox-inline'>
                                        <input type='checkbox' name='reporting' value ='1'
                                               @if($reporting == 1)checked @endif />  </label>
                                </div>
                                <div class="col-md-2">

                                </div>
                            </div>
				  <div class="form-group  " >
					<label for="On Debit" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Debit Type', (isset($fields['debit_type_id']['language'])? $fields['debit_type_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='debit_type_id' rows='5' id='debit_type_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for=" Debit Type" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('On Debit', (isset($fields['bill_debit_type']['language'])? $fields['bill_debit_type']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
                        <input type ="text"  value="0" name="bill_debit_type" style="display:none"/>
					  <?php $bill_debit_type = $row['bill_debit_type']; ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='bill_debit_type' value ='1'
					@if($bill_debit_type == 1)checked @endif
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


			 
<script type="text/javascript">
$(document).ready(function() {
        $('#location_available').hide();
        $("#region_id").jCombo("{{ URL::to('location/comboselect?filter=region:id:region') }}",
        {  selected_value : '{{ $row["region_id"] }}' });
        $("#company_id").jCombo("{{ URL::to('location/comboselect?filter=company:id:company_name_long') }}",
        {  selected_value : '{{ $row["company_id"] }}' });
        
        
        $("#contact_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
            {  selected_value : '{{ $row["contact_id"] }}' });
        $("#merch_contact_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
            {  selected_value : '{{ $row["merch_contact_id"] }}' });    
        $("#general_manager_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name:group_id:1') }}",
            {  selected_value : '{{ $row["general_manager_id"] }}' });
        $("#technical_user_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name:group_id:2') }}",
			{  selected_value : '{{ $row["technical_user_id"] }}' });
        $("#regional_manager_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name:group_id:6') }}",
			{  selected_value : '{{ $row["regional_manager_id"] }}' });
		$("#vp_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name:group_id:7') }}",
			{  selected_value : '{{ $row["vp_id"] }}' });


        
        $("#loc_ship_to").jCombo("{{ URL::to('location/comboselect?filter=location:id:location_name') }}",
        {  selected_value : '{{ $row["loc_ship_to"] }}' });
        
        $("#loc_group_id").jCombo("{{ URL::to('location/comboselect?filter=loc_group:id:loc_group_name') }}",
        {  selected_value : '{{ $row["loc_group_id"] }}' });
        
        $("#debit_type_id").jCombo("{{ URL::to('location/comboselect?filter=debit_type:id:company') }}",
        {  selected_value : '{{ $row["debit_type_id"] }}' });
         
	
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	renderDropdown($(".select2 "), { width:"100%"});
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
$('#location_id').on('blur',function(){
    var location_id=$(this).val();
    $.ajax({
        url:'{{url()}}/location/is-location-available/'+location_id,
        method:'get',
        dataType:'json',
        success:function(result){
            if(result.status=="error")
            {
                $('#location_available').css('color','red');
            }
            else{
                $('#location_available').css('color','green');
            }
            $('#location_available').show('500');
            $("#location_available").text(result.message);
        }
    });
});
$('#location_id').on('focus',function(){
    $('#location_available').hide();
});
</script>		 