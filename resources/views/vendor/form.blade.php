
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4>@if($id)
					<i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Vendor
				@else
					<i class="fa fa-plus"></i>&nbsp;&nbsp;Create New Vendor
					@endif
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'vendor/save/'.$row['id'], 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'vendorFormAjax')) !!}
			<div class="col-md-12">
						<fieldset>
									
				  <div class="form-group  " > 
					<label for="Vendor Name" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Vendor Name', (isset($fields['vendor_name']['language'])? $fields['vendor_name']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('vendor_name', $row['vendor_name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'required'  )) !!}
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
								<label for="Street2" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Street2', (isset($fields['street2']['language'])? $fields['street2']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('street2', $row['street2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					<label for="Fax" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Fax', (isset($fields['fax']['language'])? $fields['fax']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
                        {!! Form::text('fax', $row['fax'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Contact Person" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Contact Person', (isset($fields['contact']['language'])? $fields['contact']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('contact', $row['contact'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Email" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Email', (isset($fields['email']['language'])? $fields['email']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'',  'parsley-type'=>'email'   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Email 2" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Email 2', (isset($fields['email_2']['language'])? $fields['email_2']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('email_2', $row['email_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Website" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Website', (isset($fields['website']['language'])? $fields['website']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('website', $row['website'],array('class'=>'form-control', 'placeholder'=>'',  'parsley-type'=>'url'   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Games Contact" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Games Contact', (isset($fields['games_contact_name']['language'])? $fields['games_contact_name']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('games_contact_name', $row['games_contact_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Games  Email" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Games  Email', (isset($fields['games_contact_email']['language'])? $fields['games_contact_email']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('games_contact_email', $row['games_contact_email'],array('class'=>'form-control', 'placeholder'=>'',  'parsley-type'=>'email'   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Games  Phone" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Games  Phone', (isset($fields['games_contact_phone']['language'])? $fields['games_contact_phone']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('games_contact_phone', $row['games_contact_phone'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div>

				<div class="form-group  " >
					<label for="Billing Account Number" class=" control-label col-md-4 text-left">
						{!! SiteHelpers::activeLang('Billing Account Number', (isset($fields['bill_account_num']['language'])? $fields['bill_account_num']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
						{!! Form::text('bill_account_num', $row['bill_account_num'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					</div>
					<div class="col-md-2">

					</div>
				</div>
							<div class="form-group  " >
					<label for="Partner Hide" class=" control-label col-md-4 text-left">
                        <input type='hidden' value='0' name='partner_hide'>
					{!! SiteHelpers::activeLang('Partner Hide', (isset($fields['partner_hide']['language'])? $fields['partner_hide']['language'] : array())) !!}	
					</label>
					<div class="col-md-6 check-no">
					  <?php $partner_hide = explode(",",$row['partner_hide']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='partner_hide' value ='1'   class=''
					@if(in_array('1',$partner_hide))checked @endif 
					 />  </label>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " >
                      <input type='hidden' value='0' name='isgame'>
					<label for="Parts Vendor" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Vendor', (isset($fields['isgame']['language'])? $fields['isgame']['language'] : array())) !!}
					</label>
					<div class="col-md-6 check-no">
					  <?php $isgame = explode(",",$row['isgame']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='isgame' value ='1'   class=''
					@if(in_array('1',$isgame))checked @endif 
					 />  </label>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div>

				  <div class="form-group  " >
                      <input type='hidden' value='0' name='ismerch'>
					<label for="Merchandise Vendor" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Merchandise Vendor', (isset($fields['ismerch']['language'])? $fields['ismerch']['language'] : array())) !!}
					</label>
					<div class="col-md-6 check-no">
					  <?php $ismerch = explode(",",$row['ismerch']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='ismerch' value ='1'   class=''
					@if(in_array('1',$ismerch))checked @endif 
					 />  </label>
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
								<input type='hidden' value='0' name='hide'>
								<label for="Hide Vendor" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Hide Vendor', (isset($fields['hide']['language'])? $fields['hide']['language'] : array())) !!}
								</label>
								<div class="col-md-6 check-no">
                                    <?php $hide = explode(",",$row['hide']); ?>
									<label class='checked checkbox-inline'>
										<input type='checkbox' name='hide' value ='1'   class=''
											   @if(in_array('1',$hide))checked @endif
										/>  </label>
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<input type='hidden' value='0' name='status'>
								<label for="Active" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Active', (isset($fields['status']['language'])? $fields['status']['language'] : array())) !!}
								</label>
								<div class="col-md-6 check-no">
                                    <?php $status = explode(",",$row['status']); ?>
									<label class='checked checkbox-inline'>
										<input type='checkbox' name='status' value ='1'   class=''
											   @if(in_array('1',$status))checked @endif
										/>  </label>
								</div>
								<div class="col-md-2">

								</div>
							</div>
						</fieldset>
			</div>
			
												
								
						
			<div style="clear:both"></div>	
							
			<div class="form-group">
				<div class="col-sm-12 text-center">	
					<button type="submit" class="btn btn-primary btn-sm "><i class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
					<button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm"><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
				</div>			
			</div>

			{!! Form::hidden('id',$row['id']) !!}
			{!! Form::hidden('created_at',$row['created_at']) !!}
			{!! Form::hidden('updated_at',$row['updated_at']) !!}
			{!! Form::close() !!}


@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif
			 
<script type="text/javascript">
$(document).ready(function() { 
	 
	
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	renderDropdown($(".select2 "), { width:"100%"});
	$('.date').datepicker({format:'mm/dd/yyyy',autoclose:true})
	$('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square_green'
	});			
	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});			
	var form = $('#vendorFormAjax');
    
	form.parsley();
	form.submit(function(){
		cleanupForm(form);
		if(form.parsley('isValid') == true){			
			var options = { 
				dataType:      'json', 
				beforeSubmit :  cleanupFormData,
				success:       showResponse  
			}
            blockUI();
			$(this).ajaxSubmit(options); 
			return false;
						
		} else {
			return false;
		}		
	
	});

});

function cleanupForm(form) {

    var inputs = form.find(":input"),
        actionList = {'email': ['trim'], 'email_2': ['trim']};

    if (inputs.length) {
        inputs.each(function (){
            var elm = $(this),
                elmName = elm.attr('name'),
                val = elm.val(),
                actions = actionList[elmName];

            if (actions && actions.length) {
                if (val !== UNDEFINED) {
                    val = App.applyFormats(val, actions, {'form': form});
                    elm.val(val);
                }
            }
        });
    }
}
function cleanupFormData(data, $form, options) {
    var i, item, key, val, actions, action,
        actionList = {};

    for(i in data) {
        item = data[i];
        key = item['name'];
        actions = actionList[key];
        if (actionList[key]) {
            val = item['value'];
            val = App.applyFormats(val, actions, {'data': data, 'form': $form, 'ajaxOptions': options});
            data[i]['value'] = val;
        }
    }

    return data;
}
function showRequest()
{
	unblockUI();
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

<style>
	.form-horizontal .control-label, .form-horizontal .radio, .form-horizontal .checkbox, .form-horizontal .radio-inline, .form-horizontal .checkbox-inline {
		padding-top: 0px;
		margin-top: 0;
		margin-bottom: 0;
	}
</style>
