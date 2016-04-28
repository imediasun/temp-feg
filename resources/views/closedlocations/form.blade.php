
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'closedlocations/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'closedlocationsFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Closed Locations Report</legend>
									
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
					<label for="Location Name" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Location Name', (isset($fields['location_name']['language'])? $fields['location_name']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='location_name' rows='5' id='location_name' class='form-control '  
				           >{{ $row['location_name'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Location Name Short" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Location Name Short', (isset($fields['location_name_short']['language'])? $fields['location_name_short']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='location_name_short' rows='5' id='location_name_short' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Mail Attention" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Mail Attention', (isset($fields['mail_attention']['language'])? $fields['mail_attention']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='mail_attention' rows='5' id='mail_attention' class='form-control '  
				           >{{ $row['mail_attention'] }}</textarea> 
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
					<label for="Attn" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Attn', (isset($fields['attn']['language'])? $fields['attn']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='attn' rows='5' id='attn' class='form-control '  
				           >{{ $row['attn'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Company Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Company Id', (isset($fields['company_id']['language'])? $fields['company_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='company_id' rows='5' id='company_id' class='form-control '  
				           >{{ $row['company_id'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Self Owned" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Self Owned', (isset($fields['self_owned']['language'])? $fields['self_owned']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='self_owned' rows='5' id='self_owned' class='form-control '  
				           >{{ $row['self_owned'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Loading Info" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Loading Info', (isset($fields['loading_info']['language'])? $fields['loading_info']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='loading_info' rows='5' id='loading_info' class='form-control '  
				           >{{ $row['loading_info'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Date Opened" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Date Opened', (isset($fields['date_opened']['language'])? $fields['date_opened']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='date_opened' rows='5' id='date_opened' class='form-control '  
				           >{{ $row['date_opened'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Date Closed" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Date Closed', (isset($fields['date_closed']['language'])? $fields['date_closed']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='date_closed' rows='5' id='date_closed' class='form-control '  
				           >{{ $row['date_closed'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Region Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Region Id', (isset($fields['region_id']['language'])? $fields['region_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='region_id' rows='5' id='region_id' class='form-control '  
				           >{{ $row['region_id'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Loc Group Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Loc Group Id', (isset($fields['loc_group_id']['language'])? $fields['loc_group_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='loc_group_id' rows='5' id='loc_group_id' class='form-control '  
				           >{{ $row['loc_group_id'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Company" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Company', (isset($fields['debit_type_id']['language'])? $fields['debit_type_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='debit_type_id' rows='5' id='debit_type_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Can Ship" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Can Ship', (isset($fields['can_ship']['language'])? $fields['can_ship']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='can_ship' rows='5' id='can_ship' class='form-control '  
				           >{{ $row['can_ship'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Loc Ship To" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Loc Ship To', (isset($fields['loc_ship_to']['language'])? $fields['loc_ship_to']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='loc_ship_to' rows='5' id='loc_ship_to' class='form-control '  
				           >{{ $row['loc_ship_to'] }}</textarea> 
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
					<label for="Bestbuy Store Number" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bestbuy Store Number', (isset($fields['bestbuy_store_number']['language'])? $fields['bestbuy_store_number']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bestbuy_store_number' rows='5' id='bestbuy_store_number' class='form-control '  
				           >{{ $row['bestbuy_store_number'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Debit Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Debit Type', (isset($fields['bill_debit_type']['language'])? $fields['bill_debit_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_debit_type' rows='5' id='bill_debit_type' class='form-control '  
				           >{{ $row['bill_debit_type'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Debit Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Debit Amt', (isset($fields['bill_debit_amt']['language'])? $fields['bill_debit_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_debit_amt' rows='5' id='bill_debit_amt' class='form-control '  
				           >{{ $row['bill_debit_amt'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Debit Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Debit Detail', (isset($fields['bill_debit_detail']['language'])? $fields['bill_debit_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_debit_detail' rows='5' id='bill_debit_detail' class='form-control '  
				           >{{ $row['bill_debit_detail'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Ticket Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Ticket Type', (isset($fields['bill_ticket_type']['language'])? $fields['bill_ticket_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_ticket_type' rows='5' id='bill_ticket_type' class='form-control '  
				           >{{ $row['bill_ticket_type'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Ticket Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Ticket Amt', (isset($fields['bill_ticket_amt']['language'])? $fields['bill_ticket_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_ticket_amt' rows='5' id='bill_ticket_amt' class='form-control '  
				           >{{ $row['bill_ticket_amt'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Ticket Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Ticket Detail', (isset($fields['bill_ticket_detail']['language'])? $fields['bill_ticket_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_ticket_detail' rows='5' id='bill_ticket_detail' class='form-control '  
				           >{{ $row['bill_ticket_detail'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Thermalpaper Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Thermalpaper Type', (isset($fields['bill_thermalpaper_type']['language'])? $fields['bill_thermalpaper_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_thermalpaper_type' rows='5' id='bill_thermalpaper_type' class='form-control '  
				           >{{ $row['bill_thermalpaper_type'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Thermalpaper Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Thermalpaper Amt', (isset($fields['bill_thermalpaper_amt']['language'])? $fields['bill_thermalpaper_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_thermalpaper_amt' rows='5' id='bill_thermalpaper_amt' class='form-control '  
				           >{{ $row['bill_thermalpaper_amt'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Thermalpaper Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Thermalpaper Detail', (isset($fields['bill_thermalpaper_detail']['language'])? $fields['bill_thermalpaper_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_thermalpaper_detail' rows='5' id='bill_thermalpaper_detail' class='form-control '  
				           >{{ $row['bill_thermalpaper_detail'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Token Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Token Type', (isset($fields['bill_token_type']['language'])? $fields['bill_token_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_token_type' rows='5' id='bill_token_type' class='form-control '  
				           >{{ $row['bill_token_type'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Token Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Token Amt', (isset($fields['bill_token_amt']['language'])? $fields['bill_token_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_token_amt' rows='5' id='bill_token_amt' class='form-control '  
				           >{{ $row['bill_token_amt'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Token Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Token Detail', (isset($fields['bill_token_detail']['language'])? $fields['bill_token_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_token_detail' rows='5' id='bill_token_detail' class='form-control '  
				           >{{ $row['bill_token_detail'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill License Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill License Type', (isset($fields['bill_license_type']['language'])? $fields['bill_license_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_license_type' rows='5' id='bill_license_type' class='form-control '  
				           >{{ $row['bill_license_type'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill License Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill License Amt', (isset($fields['bill_license_amt']['language'])? $fields['bill_license_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_license_amt' rows='5' id='bill_license_amt' class='form-control '  
				           >{{ $row['bill_license_amt'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill License Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill License Detail', (isset($fields['bill_license_detail']['language'])? $fields['bill_license_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_license_detail' rows='5' id='bill_license_detail' class='form-control '  
				           >{{ $row['bill_license_detail'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Attraction Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Attraction Type', (isset($fields['bill_attraction_type']['language'])? $fields['bill_attraction_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_attraction_type' rows='5' id='bill_attraction_type' class='form-control '  
				           >{{ $row['bill_attraction_type'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Attraction Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Attraction Amt', (isset($fields['bill_attraction_amt']['language'])? $fields['bill_attraction_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_attraction_amt' rows='5' id='bill_attraction_amt' class='form-control '  
				           >{{ $row['bill_attraction_amt'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Attraction Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Attraction Detail', (isset($fields['bill_attraction_detail']['language'])? $fields['bill_attraction_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_attraction_detail' rows='5' id='bill_attraction_detail' class='form-control '  
				           >{{ $row['bill_attraction_detail'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Redemption Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Redemption Type', (isset($fields['bill_redemption_type']['language'])? $fields['bill_redemption_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_redemption_type' rows='5' id='bill_redemption_type' class='form-control '  
				           >{{ $row['bill_redemption_type'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Redemption Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Redemption Amt', (isset($fields['bill_redemption_amt']['language'])? $fields['bill_redemption_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_redemption_amt' rows='5' id='bill_redemption_amt' class='form-control '  
				           >{{ $row['bill_redemption_amt'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Redemption Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Redemption Detail', (isset($fields['bill_redemption_detail']['language'])? $fields['bill_redemption_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_redemption_detail' rows='5' id='bill_redemption_detail' class='form-control '  
				           >{{ $row['bill_redemption_detail'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Instant Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Instant Type', (isset($fields['bill_instant_type']['language'])? $fields['bill_instant_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_instant_type' rows='5' id='bill_instant_type' class='form-control '  
				           >{{ $row['bill_instant_type'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Instant Amt" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Instant Amt', (isset($fields['bill_instant_amt']['language'])? $fields['bill_instant_amt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_instant_amt' rows='5' id='bill_instant_amt' class='form-control '  
				           >{{ $row['bill_instant_amt'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Bill Instant Detail" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Bill Instant Detail', (isset($fields['bill_instant_detail']['language'])? $fields['bill_instant_detail']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='bill_instant_detail' rows='5' id='bill_instant_detail' class='form-control '  
				           >{{ $row['bill_instant_detail'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Contact Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Contact Id', (isset($fields['contact_id']['language'])? $fields['contact_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='contact_id' rows='5' id='contact_id' class='form-control '  
				           >{{ $row['contact_id'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Merch Contact Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Merch Contact Id', (isset($fields['merch_contact_id']['language'])? $fields['merch_contact_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='merch_contact_id' rows='5' id='merch_contact_id' class='form-control '  
				           >{{ $row['merch_contact_id'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Field Manager Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Field Manager Id', (isset($fields['field_manager_id']['language'])? $fields['field_manager_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='field_manager_id' rows='5' id='field_manager_id' class='form-control '  
				           >{{ $row['field_manager_id'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Tech Manager Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Tech Manager Id', (isset($fields['tech_manager_id']['language'])? $fields['tech_manager_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='tech_manager_id' rows='5' id='tech_manager_id' class='form-control '  
				           >{{ $row['tech_manager_id'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="No Games" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('No Games', (isset($fields['no_games']['language'])? $fields['no_games']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='no_games' rows='5' id='no_games' class='form-control '  
				           >{{ $row['no_games'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Liftgate" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Liftgate', (isset($fields['liftgate']['language'])? $fields['liftgate']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='liftgate' rows='5' id='liftgate' class='form-control '  
				           >{{ $row['liftgate'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Ipaddress" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Ipaddress', (isset($fields['ipaddress']['language'])? $fields['ipaddress']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='ipaddress' rows='5' id='ipaddress' class='form-control '  
				           >{{ $row['ipaddress'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Reporting" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Reporting', (isset($fields['reporting']['language'])? $fields['reporting']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='reporting' rows='5' id='reporting' class='form-control '  
				           >{{ $row['reporting'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Sun" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Sun', (isset($fields['not_reporting_Sun']['language'])? $fields['not_reporting_Sun']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='not_reporting_Sun' rows='5' id='not_reporting_Sun' class='form-control '  
				           >{{ $row['not_reporting_Sun'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Mon" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Mon', (isset($fields['not_reporting_Mon']['language'])? $fields['not_reporting_Mon']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='not_reporting_Mon' rows='5' id='not_reporting_Mon' class='form-control '  
				           >{{ $row['not_reporting_Mon'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Tue" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Tue', (isset($fields['not_reporting_Tue']['language'])? $fields['not_reporting_Tue']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='not_reporting_Tue' rows='5' id='not_reporting_Tue' class='form-control '  
				           >{{ $row['not_reporting_Tue'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Wed" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Wed', (isset($fields['not_reporting_Wed']['language'])? $fields['not_reporting_Wed']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='not_reporting_Wed' rows='5' id='not_reporting_Wed' class='form-control '  
				           >{{ $row['not_reporting_Wed'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Thu" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Thu', (isset($fields['not_reporting_Thu']['language'])? $fields['not_reporting_Thu']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='not_reporting_Thu' rows='5' id='not_reporting_Thu' class='form-control '  
				           >{{ $row['not_reporting_Thu'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Fri" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Fri', (isset($fields['not_reporting_Fri']['language'])? $fields['not_reporting_Fri']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='not_reporting_Fri' rows='5' id='not_reporting_Fri' class='form-control '  
				           >{{ $row['not_reporting_Fri'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Sat" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Sat', (isset($fields['not_reporting_Sat']['language'])? $fields['not_reporting_Sat']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='not_reporting_Sat' rows='5' id='not_reporting_Sat' class='form-control '  
				           >{{ $row['not_reporting_Sat'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Active" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Active', (isset($fields['active']['language'])? $fields['active']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='active' rows='5' id='active' class='form-control '  
				           >{{ $row['active'] }}</textarea> 
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
	
        $("#location_name_short").jCombo("{{ URL::to('closedlocations/comboselect?filter=location::location_name_short') }}",
        {  selected_value : '{{ $row["location_name_short"] }}' });
        
        $("#debit_type_id").jCombo("{{ URL::to('closedlocations/comboselect?filter=debit_type:id:company') }}",
        {  selected_value : '{{ $row["debit_type_id"] }}' });
         
	
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
	var form = $('#closedlocationsFormAjax'); 
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