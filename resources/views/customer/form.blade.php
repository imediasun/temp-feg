
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content">
@endif
			{!! Form::open(array('url'=>'customer/save/'.SiteHelpers::encryptID($row['customerNumber']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'customerFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Customer</legend>

				  <div class="form-group  " >
					<label for="CustomerNumber" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('CustomerNumber', (isset($fields['customerNumber']['language'])? $fields['customerNumber']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('customerNumber', $row['customerNumber'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="CustomerName" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('CustomerName', (isset($fields['customerName']['language'])? $fields['customerName']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('customerName', $row['customerName'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="ContactLastName" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('ContactLastName', (isset($fields['contactLastName']['language'])? $fields['contactLastName']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('contactLastName', $row['contactLastName'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="ContactFirstName" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('ContactFirstName', (isset($fields['contactFirstName']['language'])? $fields['contactFirstName']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('contactFirstName', $row['contactFirstName'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					<label for="AddressLine1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('AddressLine1', (isset($fields['addressLine1']['language'])? $fields['addressLine1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('addressLine1', $row['addressLine1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="AddressLine2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('AddressLine2', (isset($fields['addressLine2']['language'])? $fields['addressLine2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('addressLine2', $row['addressLine2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					<label for="PostalCode" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('PostalCode', (isset($fields['postalCode']['language'])? $fields['postalCode']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('postalCode', $row['postalCode'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="Country" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Country', (isset($fields['country']['language'])? $fields['country']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('country', $row['country'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="SalesRepEmployeeNumber" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('SalesRepEmployeeNumber', (isset($fields['salesRepEmployeeNumber']['language'])? $fields['salesRepEmployeeNumber']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='salesRepEmployeeNumber' rows='5' id='salesRepEmployeeNumber' class='select2 '   ></select>
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="CreditLimit" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('CreditLimit', (isset($fields['creditLimit']['language'])? $fields['creditLimit']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('creditLimit', $row['creditLimit'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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

        $("#salesRepEmployeeNumber").jCombo("{{ URL::to('customer/comboselect?filter=users:employeeNumber:firstName|lastName') }}",
        {  selected_value : '{{ $row["salesRepEmployeeNumber"] }}' });


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
	var form = $('#customerFormAjax');
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