
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
					<label for="Vendor Name" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Vendor Name', (isset($fields['vendor_name']['language'])? $fields['vendor_name']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('vendor_name', $row['vendor_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<label for="Contact" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Contact', (isset($fields['contact']['language'])? $fields['contact']['language'] : array())) !!}	
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
					  {!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Website" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Website', (isset($fields['website']['language'])? $fields['website']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('website', $row['website'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Games Contact Name" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Games Contact Name', (isset($fields['games_contact_name']['language'])? $fields['games_contact_name']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('games_contact_name', $row['games_contact_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Games Contact Phone" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Games Contact Phone', (isset($fields['games_contact_phone']['language'])? $fields['games_contact_phone']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('games_contact_phone', $row['games_contact_phone'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Partner Hide" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Partner Hide', (isset($fields['partner_hide']['language'])? $fields['partner_hide']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $partner_hide = explode(',',$row['partner_hide']);
					$partner_hide_opt = array( '0' => 'No' ,  '1' => 'Yes' , ); ?>
					<select name='partner_hide' rows='5'   class='select2 '  > 
						<?php 
						foreach($partner_hide_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['partner_hide'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Isgame" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Isgame', (isset($fields['isgame']['language'])? $fields['isgame']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $isgame = explode(',',$row['isgame']);
					$isgame_opt = array( '0' => 'No' ,  '1' => 'Yes' , ); ?>
					<select name='isgame' rows='5'   class='select2 '  > 
						<?php 
						foreach($isgame_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['isgame'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Ismerch" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Ismerch', (isset($fields['ismerch']['language'])? $fields['ismerch']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $ismerch = explode(',',$row['ismerch']);
					$ismerch_opt = array( '0' => 'No' ,  '1' => 'Yes' , ); ?>
					<select name='ismerch' rows='5'   class='select2 '  > 
						<?php 
						foreach($ismerch_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['ismerch'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
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
 </fieldset>
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