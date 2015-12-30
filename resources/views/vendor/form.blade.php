
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'vendor/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'vendorFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> vendor</legend>
									
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
					<label for="Vendor Name" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Vendor Name', (isset($fields['vendor_name']['language'])? $fields['vendor_name']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='vendor_name' rows='5' id='vendor_name' class='form-control '  
				           >{{ $row['vendor_name'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Street1" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Street1', (isset($fields['street1']['language'])? $fields['street1']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='street1' rows='5' id='street1' class='form-control '  
				           >{{ $row['street1'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Street2" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Street2', (isset($fields['street2']['language'])? $fields['street2']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='street2' rows='5' id='street2' class='form-control '  
				           >{{ $row['street2'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="City" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('City', (isset($fields['city']['language'])? $fields['city']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='city' rows='5' id='city' class='form-control '  
				           >{{ $row['city'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="State" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('State', (isset($fields['state']['language'])? $fields['state']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='state' rows='5' id='state' class='form-control '  
				           >{{ $row['state'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Zip" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Zip', (isset($fields['zip']['language'])? $fields['zip']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='zip' rows='5' id='zip' class='form-control '  
				           >{{ $row['zip'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Phone" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Phone', (isset($fields['phone']['language'])? $fields['phone']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='phone' rows='5' id='phone' class='form-control '  
				           >{{ $row['phone'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Fax" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Fax', (isset($fields['fax']['language'])? $fields['fax']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='fax' rows='5' id='fax' class='form-control '  
				           >{{ $row['fax'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Contact" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Contact', (isset($fields['contact']['language'])? $fields['contact']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='contact' rows='5' id='contact' class='form-control '  
				           >{{ $row['contact'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Email" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Email', (isset($fields['email']['language'])? $fields['email']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='email' rows='5' id='email' class='form-control '  
				           >{{ $row['email'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Email 2" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Email 2', (isset($fields['email_2']['language'])? $fields['email_2']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='email_2' rows='5' id='email_2' class='form-control '  
				           >{{ $row['email_2'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Website" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Website', (isset($fields['website']['language'])? $fields['website']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='website' rows='5' id='website' class='form-control '  
				           >{{ $row['website'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Games Contact Name" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Games Contact Name', (isset($fields['games_contact_name']['language'])? $fields['games_contact_name']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='games_contact_name' rows='5' id='games_contact_name' class='form-control '  
				           >{{ $row['games_contact_name'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Games Contact Email" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Games Contact Email', (isset($fields['games_contact_email']['language'])? $fields['games_contact_email']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='games_contact_email' rows='5' id='games_contact_email' class='form-control '  
				           >{{ $row['games_contact_email'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Games Contact Phone" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Games Contact Phone', (isset($fields['games_contact_phone']['language'])? $fields['games_contact_phone']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='games_contact_phone' rows='5' id='games_contact_phone' class='form-control '  
				           >{{ $row['games_contact_phone'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Partner Hide" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Partner Hide', (isset($fields['partner_hide']['language'])? $fields['partner_hide']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='partner_hide' rows='5' id='partner_hide' class='form-control '  
				           >{{ $row['partner_hide'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Isgame" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Isgame', (isset($fields['isgame']['language'])? $fields['isgame']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='isgame' rows='5' id='isgame' class='form-control '  
				           >{{ $row['isgame'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Ismerch" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Ismerch', (isset($fields['ismerch']['language'])? $fields['ismerch']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='ismerch' rows='5' id='ismerch' class='form-control '  
				           >{{ $row['ismerch'] }}</textarea> 
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
	var form = $('#vendorFormAjax'); 
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