
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content">
@endif
			{!! Form::open(array('url'=>'location/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'locationFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Locations</legend>

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
					<label for="Location Name Short" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Location Name Short', (isset($fields['location_name_short']['language'])? $fields['location_name_short']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('location_name_short', $row['location_name_short'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="Street1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Street1', (isset($fields['street1']['language'])? $fields['street1']['language'] : array())) !!}
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
					<label for="Zip" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Zip', (isset($fields['zip']['language'])? $fields['zip']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('zip', $row['zip'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="Attn" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Attn', (isset($fields['attn']['language'])? $fields['attn']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('attn', $row['attn'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					<label for="FEG Owned" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('FEG Owned', (isset($fields['self_owned']['language'])? $fields['self_owned']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $self_owned = explode(",",$row['self_owned']); ?>
					 <label class='checked checkbox-inline'>
					<input type='checkbox' name='self_owned' value ='1'   class=''
					@if(in_array('1',$self_owned))checked @endif
					 /> Yes </label>
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="Loading Info" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loading Info', (isset($fields['loading_info']['language'])? $fields['loading_info']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loading_info', $row['loading_info'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					<label for="Debit Type " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Debit Type ', (isset($fields['debit_type_id']['language'])? $fields['debit_type_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='debit_type_id' rows='5' id='debit_type_id' class='select2 '   ></select>
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
					<input type='checkbox' name='can_ship' value ='1'   class=''
					@if(in_array('1',$can_ship))checked @endif
					 /> Yes </label>
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="Location Ship To" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Location Ship To', (isset($fields['loc_ship_to']['language'])? $fields['loc_ship_to']['language'] : array())) !!}
					</label>
					<div class="col-md-6">

					<?php $loc_ship_to = explode(',',$row['loc_ship_to']);
					$loc_ship_to_opt = array( '0' => 'No' , ); ?>
					<select name='loc_ship_to' rows='5'   class='select2 '  >
						<?php
						foreach($loc_ship_to_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['loc_ship_to'] == $key ? " selected='selected' " : '' ).">$val</option>";
						}
						?></select>
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
					<label for="Bestbuy Store Number" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Bestbuy Store Number', (isset($fields['bestbuy_store_number']['language'])? $fields['bestbuy_store_number']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('bestbuy_store_number', $row['bestbuy_store_number'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					<label for="Tech Manager " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Tech Manager ', (isset($fields['tech_manager_id']['language'])? $fields['tech_manager_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='tech_manager_id' rows='5' id='tech_manager_id' class='select2 ' required  ></select>
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
					<input type='checkbox' name='liftgate' value ='1'   class=''
					@if(in_array('1',$liftgate))checked @endif
					 /> Yes </label>
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="Active" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Active', (isset($fields['active']['language'])? $fields['active']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $active = explode(",",$row['active']); ?>
					 <label class='checked checkbox-inline'>
					<input type='checkbox' name='active' value ='1'   class=''
					@if(in_array('1',$active))checked @endif
					 /> Yes </label>
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

        $("#company_id").jCombo("{{ URL::to('location/comboselect?filter=company:id:company_name_long') }}",
        {  selected_value : '{{ $row["company_id"] }}' });

        $("#region_id").jCombo("{{ URL::to('location/comboselect?filter=region:id:region') }}",
        {  selected_value : '{{ $row["region_id"] }}' });

        $("#loc_group_id").jCombo("{{ URL::to('location/comboselect?filter=loc_group:id:loc_group_name') }}",
        {  selected_value : '{{ $row["loc_group_id"] }}' });

        $("#debit_type_id").jCombo("{{ URL::to('location/comboselect?filter=debit_type:id:company') }}",
        {  selected_value : '{{ $row["debit_type_id"] }}' });

        $("#contact_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
        {  selected_value : '{{ $row["contact_id"] }}' });

        $("#merch_contact_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
        {  selected_value : '{{ $row["merch_contact_id"] }}' });

        $("#field_manager_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
        {  selected_value : '{{ $row["field_manager_id"] }}' });

        $("#tech_manager_id").jCombo("{{ URL::to('location/comboselect?filter=users:id:first_name|last_name') }}",
        {  selected_value : '{{ $row["tech_manager_id"] }}' });


	$('.editor').summernote();
	$('.previewImage').fancybox();
	$('.tips').tooltip();
	$(".select2").select2({ width:"98%"});
	$('.date').datepicker({format:'yyyy-mm-dd',autoClose:true})
	$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square_green',
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