
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
					<label for="Mail Attention" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Mail Attention', (isset($fields['mail_attention']['language'])? $fields['mail_attention']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('mail_attention', $row['mail_attention'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<label for="Company Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Company Id', (isset($fields['company_id']['language'])? $fields['company_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='company_id' rows='5' id='company_id' class='select2 '   ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Self Owned" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Self Owned', (isset($fields['self_owned']['language'])? $fields['self_owned']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $self_owned = explode(',',$row['self_owned']);
					$self_owned_opt = array( '1' => 'Yes' ,  '0' => 'No' , ); ?>
					<select name='self_owned' rows='5'   class='select2 '  > 
						<?php 
						foreach($self_owned_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['self_owned'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
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
					<label for="Date Opened" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Date Opened', (isset($fields['date_opened']['language'])? $fields['date_opened']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('date_opened', $row['date_opened'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Date Closed" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Date Closed', (isset($fields['date_closed']['language'])? $fields['date_closed']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('date_closed', $row['date_closed'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Region Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Region Id', (isset($fields['region_id']['language'])? $fields['region_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='region_id' rows='5' id='region_id' class='select2 '   ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Loc Group Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Loc Group Id', (isset($fields['loc_group_id']['language'])? $fields['loc_group_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='loc_group_id' rows='5' id='loc_group_id' class='select2 '   ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Debit Type Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Debit Type Id', (isset($fields['debit_type_id']['language'])? $fields['debit_type_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $debit_type_id = explode(',',$row['debit_type_id']);
					$debit_type_id_opt = array( '1' => 'Yes' ,  '0' => 'No' , ); ?>
					<select name='debit_type_id' rows='5'   class='select2 '  > 
						<?php 
						foreach($debit_type_id_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['debit_type_id'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Can Ship" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Can Ship', (isset($fields['can_ship']['language'])? $fields['can_ship']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $can_ship = explode(',',$row['can_ship']);
					$can_ship_opt = array( '1' => 'Yes' , ); ?>
					<select name='can_ship' rows='5'   class='select2 '  > 
						<?php 
						foreach($can_ship_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['can_ship'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Loc Ship To" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Loc Ship To', (isset($fields['loc_ship_to']['language'])? $fields['loc_ship_to']['language'] : array())) !!}	
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
					<label for="Bill Debit Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Debit Type', (isset($fields['bill_debit_type']['language'])? $fields['bill_debit_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_debit_type', $row['bill_debit_type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Debit Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Debit Amt', (isset($fields['bill_debit_amt']['language'])? $fields['bill_debit_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_debit_amt', $row['bill_debit_amt'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Debit Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Debit Detail', (isset($fields['bill_debit_detail']['language'])? $fields['bill_debit_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_debit_detail', $row['bill_debit_detail'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Ticket Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Ticket Type', (isset($fields['bill_ticket_type']['language'])? $fields['bill_ticket_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $bill_ticket_type = explode(',',$row['bill_ticket_type']);
					$bill_ticket_type_opt = array( '0' => 'No' , ); ?>
					<select name='bill_ticket_type' rows='5' required  class='select2 '  > 
						<?php 
						foreach($bill_ticket_type_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['bill_ticket_type'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Ticket Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Ticket Amt', (isset($fields['bill_ticket_amt']['language'])? $fields['bill_ticket_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_ticket_amt', $row['bill_ticket_amt'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Ticket Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Ticket Detail', (isset($fields['bill_ticket_detail']['language'])? $fields['bill_ticket_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_ticket_detail', $row['bill_ticket_detail'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Thermalpaper Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Thermalpaper Type', (isset($fields['bill_thermalpaper_type']['language'])? $fields['bill_thermalpaper_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $bill_thermalpaper_type = explode(',',$row['bill_thermalpaper_type']);
					$bill_thermalpaper_type_opt = array( '0' => 'No' , ); ?>
					<select name='bill_thermalpaper_type' rows='5' required  class='select2 '  > 
						<?php 
						foreach($bill_thermalpaper_type_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['bill_thermalpaper_type'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Thermalpaper Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Thermalpaper Amt', (isset($fields['bill_thermalpaper_amt']['language'])? $fields['bill_thermalpaper_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_thermalpaper_amt', $row['bill_thermalpaper_amt'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Thermalpaper Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Thermalpaper Detail', (isset($fields['bill_thermalpaper_detail']['language'])? $fields['bill_thermalpaper_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_thermalpaper_detail', $row['bill_thermalpaper_detail'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Token Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Token Type', (isset($fields['bill_token_type']['language'])? $fields['bill_token_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $bill_token_type = explode(',',$row['bill_token_type']);
					$bill_token_type_opt = array( '0' => 'No' , ); ?>
					<select name='bill_token_type' rows='5' required  class='select2 '  > 
						<?php 
						foreach($bill_token_type_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['bill_token_type'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Token Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Token Amt', (isset($fields['bill_token_amt']['language'])? $fields['bill_token_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_token_amt', $row['bill_token_amt'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Token Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Token Detail', (isset($fields['bill_token_detail']['language'])? $fields['bill_token_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_token_detail', $row['bill_token_detail'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill License Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill License Type', (isset($fields['bill_license_type']['language'])? $fields['bill_license_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_license_type', $row['bill_license_type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill License Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill License Amt', (isset($fields['bill_license_amt']['language'])? $fields['bill_license_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_license_amt', $row['bill_license_amt'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill License Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill License Detail', (isset($fields['bill_license_detail']['language'])? $fields['bill_license_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_license_detail', $row['bill_license_detail'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Attraction Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Attraction Type', (isset($fields['bill_attraction_type']['language'])? $fields['bill_attraction_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $bill_attraction_type = explode(',',$row['bill_attraction_type']);
					$bill_attraction_type_opt = array( '0' => 'No' , ); ?>
					<select name='bill_attraction_type' rows='5' required  class='select2 '  > 
						<?php 
						foreach($bill_attraction_type_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['bill_attraction_type'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Attraction Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Attraction Amt', (isset($fields['bill_attraction_amt']['language'])? $fields['bill_attraction_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_attraction_amt', $row['bill_attraction_amt'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Attraction Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Attraction Detail', (isset($fields['bill_attraction_detail']['language'])? $fields['bill_attraction_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_attraction_detail', $row['bill_attraction_detail'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Redemption Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Redemption Type', (isset($fields['bill_redemption_type']['language'])? $fields['bill_redemption_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $bill_redemption_type = explode(',',$row['bill_redemption_type']);
					$bill_redemption_type_opt = array( '0' => 'No' , ); ?>
					<select name='bill_redemption_type' rows='5' required  class='select2 '  > 
						<?php 
						foreach($bill_redemption_type_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['bill_redemption_type'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Redemption Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Redemption Amt', (isset($fields['bill_redemption_amt']['language'])? $fields['bill_redemption_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_redemption_amt', $row['bill_redemption_amt'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Redemption Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Redemption Detail', (isset($fields['bill_redemption_detail']['language'])? $fields['bill_redemption_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_redemption_detail', $row['bill_redemption_detail'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Instant Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Instant Type', (isset($fields['bill_instant_type']['language'])? $fields['bill_instant_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $bill_instant_type = explode(',',$row['bill_instant_type']);
					$bill_instant_type_opt = array( '0' => 'No' , ); ?>
					<select name='bill_instant_type' rows='5' required  class='select2 '  > 
						<?php 
						foreach($bill_instant_type_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['bill_instant_type'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Instant Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Instant Amt', (isset($fields['bill_instant_amt']['language'])? $fields['bill_instant_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_instant_amt', $row['bill_instant_amt'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Instant Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Instant Detail', (isset($fields['bill_instant_detail']['language'])? $fields['bill_instant_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('bill_instant_detail', $row['bill_instant_detail'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Contact Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Contact Id', (isset($fields['contact_id']['language'])? $fields['contact_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='contact_id' rows='5' id='contact_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Merch Contact Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Merch Contact Id', (isset($fields['merch_contact_id']['language'])? $fields['merch_contact_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='merch_contact_id' rows='5' id='merch_contact_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Field Manager Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Field Manager Id', (isset($fields['field_manager_id']['language'])? $fields['field_manager_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='field_manager_id' rows='5' id='field_manager_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Tech Manager Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Tech Manager Id', (isset($fields['tech_manager_id']['language'])? $fields['tech_manager_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='tech_manager_id' rows='5' id='tech_manager_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="No Games" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('No Games', (isset($fields['no_games']['language'])? $fields['no_games']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $no_games = explode(',',$row['no_games']);
					$no_games_opt = array( '0' => 'No' , ); ?>
					<select name='no_games' rows='5' required  class='select2 '  > 
						<?php 
						foreach($no_games_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['no_games'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Liftgate" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Liftgate', (isset($fields['liftgate']['language'])? $fields['liftgate']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $liftgate = explode(',',$row['liftgate']);
					$liftgate_opt = array( '1' => 'Yes' , ); ?>
					<select name='liftgate' rows='5' required  class='select2 '  > 
						<?php 
						foreach($liftgate_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['liftgate'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Ipaddress" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Ipaddress', (isset($fields['ipaddress']['language'])? $fields['ipaddress']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('ipaddress', $row['ipaddress'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Reporting" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Reporting', (isset($fields['reporting']['language'])? $fields['reporting']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $reporting = explode(',',$row['reporting']);
					$reporting_opt = array( '1' => 'Yes' ,  '0' => 'No' , ); ?>
					<select name='reporting' rows='5' required  class='select2 '  > 
						<?php 
						foreach($reporting_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['reporting'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Sun" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Sun', (isset($fields['not_reporting_Sun']['language'])? $fields['not_reporting_Sun']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $not_reporting_Sun = explode(',',$row['not_reporting_Sun']);
					$not_reporting_Sun_opt = array( '0' => 'No' , ); ?>
					<select name='not_reporting_Sun' rows='5' required  class='select2 '  > 
						<?php 
						foreach($not_reporting_Sun_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['not_reporting_Sun'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Mon" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Mon', (isset($fields['not_reporting_Mon']['language'])? $fields['not_reporting_Mon']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $not_reporting_Mon = explode(',',$row['not_reporting_Mon']);
					$not_reporting_Mon_opt = array( '0' => 'No' , ); ?>
					<select name='not_reporting_Mon' rows='5' required  class='select2 '  > 
						<?php 
						foreach($not_reporting_Mon_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['not_reporting_Mon'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Tue" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Tue', (isset($fields['not_reporting_Tue']['language'])? $fields['not_reporting_Tue']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $not_reporting_Tue = explode(',',$row['not_reporting_Tue']);
					$not_reporting_Tue_opt = array( '0' => 'No' , ); ?>
					<select name='not_reporting_Tue' rows='5' required  class='select2 '  > 
						<?php 
						foreach($not_reporting_Tue_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['not_reporting_Tue'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Wed" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Wed', (isset($fields['not_reporting_Wed']['language'])? $fields['not_reporting_Wed']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $not_reporting_Wed = explode(',',$row['not_reporting_Wed']);
					$not_reporting_Wed_opt = array( '0' => 'No' , ); ?>
					<select name='not_reporting_Wed' rows='5' required  class='select2 '  > 
						<?php 
						foreach($not_reporting_Wed_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['not_reporting_Wed'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Thu" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Thu', (isset($fields['not_reporting_Thu']['language'])? $fields['not_reporting_Thu']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $not_reporting_Thu = explode(',',$row['not_reporting_Thu']);
					$not_reporting_Thu_opt = array( '0' => 'No' , ); ?>
					<select name='not_reporting_Thu' rows='5' required  class='select2 '  > 
						<?php 
						foreach($not_reporting_Thu_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['not_reporting_Thu'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Fri" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Fri', (isset($fields['not_reporting_Fri']['language'])? $fields['not_reporting_Fri']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $not_reporting_Fri = explode(',',$row['not_reporting_Fri']);
					$not_reporting_Fri_opt = array( '0' => 'No' , ); ?>
					<select name='not_reporting_Fri' rows='5' required  class='select2 '  > 
						<?php 
						foreach($not_reporting_Fri_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['not_reporting_Fri'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Sat" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Sat', (isset($fields['not_reporting_Sat']['language'])? $fields['not_reporting_Sat']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $not_reporting_Sat = explode(',',$row['not_reporting_Sat']);
					$not_reporting_Sat_opt = array( '0' => 'No' , ); ?>
					<select name='not_reporting_Sat' rows='5' required  class='select2 '  > 
						<?php 
						foreach($not_reporting_Sat_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['not_reporting_Sat'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Active" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Active', (isset($fields['active']['language'])? $fields['active']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $active = explode(',',$row['active']);
					$active_opt = array( '1' => 'Yes' ,  '0' => 'No' , ); ?>
					<select name='active' rows='5' required  class='select2 '  > 
						<?php 
						foreach($active_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['active'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
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
	
        $("#company_id").jCombo("{{ URL::to('location/comboselect?filter=location:id:company_id') }}",
        {  selected_value : '{{ $row["company_id"] }}' });
        
        $("#region_id").jCombo("{{ URL::to('location/comboselect?filter=location:id:region_id') }}",
        {  selected_value : '{{ $row["region_id"] }}' });
        
        $("#loc_group_id").jCombo("{{ URL::to('location/comboselect?filter=location:id:loc_group_id') }}",
        {  selected_value : '{{ $row["loc_group_id"] }}' });
        
        $("#contact_id").jCombo("{{ URL::to('location/comboselect?filter=location:id:contact_id') }}",
        {  selected_value : '{{ $row["contact_id"] }}' });
        
        $("#merch_contact_id").jCombo("{{ URL::to('location/comboselect?filter=location:id:merch_contact_id') }}",
        {  selected_value : '{{ $row["merch_contact_id"] }}' });
        
        $("#field_manager_id").jCombo("{{ URL::to('location/comboselect?filter=location:id:field_manager_id') }}",
        {  selected_value : '{{ $row["field_manager_id"] }}' });
        
        $("#tech_manager_id").jCombo("{{ URL::to('location/comboselect?filter=location:id:tech_manager_id') }}",
        {  selected_value : '{{ $row["tech_manager_id"] }}' });
         
	
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