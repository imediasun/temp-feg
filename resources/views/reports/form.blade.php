
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'reports/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'reportsFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Report Location Not Responding</legend>
									
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
					  {!! Form::text('company_id', $row['company_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Self Owned" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Self Owned', (isset($fields['self_owned']['language'])? $fields['self_owned']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('self_owned', $row['self_owned'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					  {!! Form::text('date_opened', $row['date_opened'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Date Closed" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Date Closed', (isset($fields['date_closed']['language'])? $fields['date_closed']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('date_closed', $row['date_closed'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Region Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Region Id', (isset($fields['region_id']['language'])? $fields['region_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('region_id', $row['region_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Loc Group Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Loc Group Id', (isset($fields['loc_group_id']['language'])? $fields['loc_group_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_group_id', $row['loc_group_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Debit Type Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Debit Type Id', (isset($fields['debit_type_id']['language'])? $fields['debit_type_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('debit_type_id', $row['debit_type_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Can Ship" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Can Ship', (isset($fields['can_ship']['language'])? $fields['can_ship']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('can_ship', $row['can_ship'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Loc Ship To" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Loc Ship To', (isset($fields['loc_ship_to']['language'])? $fields['loc_ship_to']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_ship_to', $row['loc_ship_to'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					  {!! Form::text('bill_ticket_type', $row['bill_ticket_type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					  {!! Form::text('bill_thermalpaper_type', $row['bill_thermalpaper_type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					  {!! Form::text('bill_token_type', $row['bill_token_type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					  {!! Form::text('bill_attraction_type', $row['bill_attraction_type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					  {!! Form::text('bill_redemption_type', $row['bill_redemption_type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					  {!! Form::text('bill_instant_type', $row['bill_instant_type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<label for="Jan 2012" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jan 2012', (isset($fields['Jan_2012']['language'])? $fields['Jan_2012']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jan_2012', $row['Jan_2012'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Feb 2012" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Feb 2012', (isset($fields['Feb_2012']['language'])? $fields['Feb_2012']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Feb_2012', $row['Feb_2012'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Mar 2012" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Mar 2012', (isset($fields['Mar_2012']['language'])? $fields['Mar_2012']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Mar_2012', $row['Mar_2012'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Apr 2012" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Apr 2012', (isset($fields['Apr_2012']['language'])? $fields['Apr_2012']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Apr_2012', $row['Apr_2012'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="May 2012" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('May 2012', (isset($fields['May_2012']['language'])? $fields['May_2012']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('May_2012', $row['May_2012'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jun 2012" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jun 2012', (isset($fields['Jun_2012']['language'])? $fields['Jun_2012']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jun_2012', $row['Jun_2012'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jul 2012" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jul 2012', (isset($fields['Jul_2012']['language'])? $fields['Jul_2012']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jul_2012', $row['Jul_2012'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Aug 2012" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Aug 2012', (isset($fields['Aug_2012']['language'])? $fields['Aug_2012']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Aug_2012', $row['Aug_2012'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Sep 2012" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Sep 2012', (isset($fields['Sep_2012']['language'])? $fields['Sep_2012']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Sep_2012', $row['Sep_2012'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Oct 2012" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Oct 2012', (isset($fields['Oct_2012']['language'])? $fields['Oct_2012']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Oct_2012', $row['Oct_2012'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Nov 2012" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Nov 2012', (isset($fields['Nov_2012']['language'])? $fields['Nov_2012']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Nov_2012', $row['Nov_2012'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Dec 2012" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Dec 2012', (isset($fields['Dec_2012']['language'])? $fields['Dec_2012']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Dec_2012', $row['Dec_2012'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jan 2013" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jan 2013', (isset($fields['Jan_2013']['language'])? $fields['Jan_2013']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jan_2013', $row['Jan_2013'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Feb 2013" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Feb 2013', (isset($fields['Feb_2013']['language'])? $fields['Feb_2013']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Feb_2013', $row['Feb_2013'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Mar 2013" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Mar 2013', (isset($fields['Mar_2013']['language'])? $fields['Mar_2013']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Mar_2013', $row['Mar_2013'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Apr 2013" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Apr 2013', (isset($fields['Apr_2013']['language'])? $fields['Apr_2013']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Apr_2013', $row['Apr_2013'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="May 2013" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('May 2013', (isset($fields['May_2013']['language'])? $fields['May_2013']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('May_2013', $row['May_2013'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jun 2013" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jun 2013', (isset($fields['Jun_2013']['language'])? $fields['Jun_2013']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jun_2013', $row['Jun_2013'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jul 2013" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jul 2013', (isset($fields['Jul_2013']['language'])? $fields['Jul_2013']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jul_2013', $row['Jul_2013'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Aug 2013" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Aug 2013', (isset($fields['Aug_2013']['language'])? $fields['Aug_2013']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Aug_2013', $row['Aug_2013'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Sep 2013" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Sep 2013', (isset($fields['Sep_2013']['language'])? $fields['Sep_2013']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Sep_2013', $row['Sep_2013'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Oct 2013" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Oct 2013', (isset($fields['Oct_2013']['language'])? $fields['Oct_2013']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Oct_2013', $row['Oct_2013'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Nov 2013" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Nov 2013', (isset($fields['Nov_2013']['language'])? $fields['Nov_2013']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Nov_2013', $row['Nov_2013'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Dec 2013" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Dec 2013', (isset($fields['Dec_2013']['language'])? $fields['Dec_2013']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Dec_2013', $row['Dec_2013'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jan 2014" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jan 2014', (isset($fields['Jan_2014']['language'])? $fields['Jan_2014']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jan_2014', $row['Jan_2014'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Feb 2014" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Feb 2014', (isset($fields['Feb_2014']['language'])? $fields['Feb_2014']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Feb_2014', $row['Feb_2014'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Mar 2014" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Mar 2014', (isset($fields['Mar_2014']['language'])? $fields['Mar_2014']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Mar_2014', $row['Mar_2014'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Apr 2014" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Apr 2014', (isset($fields['Apr_2014']['language'])? $fields['Apr_2014']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Apr_2014', $row['Apr_2014'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="May 2014" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('May 2014', (isset($fields['May_2014']['language'])? $fields['May_2014']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('May_2014', $row['May_2014'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jun 2014" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jun 2014', (isset($fields['Jun_2014']['language'])? $fields['Jun_2014']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jun_2014', $row['Jun_2014'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jul 2014" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jul 2014', (isset($fields['Jul_2014']['language'])? $fields['Jul_2014']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jul_2014', $row['Jul_2014'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Aug 2014" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Aug 2014', (isset($fields['Aug_2014']['language'])? $fields['Aug_2014']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Aug_2014', $row['Aug_2014'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Sep 2014" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Sep 2014', (isset($fields['Sep_2014']['language'])? $fields['Sep_2014']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Sep_2014', $row['Sep_2014'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Oct 2014" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Oct 2014', (isset($fields['Oct_2014']['language'])? $fields['Oct_2014']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Oct_2014', $row['Oct_2014'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Nov 2014" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Nov 2014', (isset($fields['Nov_2014']['language'])? $fields['Nov_2014']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Nov_2014', $row['Nov_2014'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Dec 2014" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Dec 2014', (isset($fields['Dec_2014']['language'])? $fields['Dec_2014']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Dec_2014', $row['Dec_2014'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jan 2015" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jan 2015', (isset($fields['Jan_2015']['language'])? $fields['Jan_2015']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jan_2015', $row['Jan_2015'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Feb 2015" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Feb 2015', (isset($fields['Feb_2015']['language'])? $fields['Feb_2015']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Feb_2015', $row['Feb_2015'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Mar 2015" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Mar 2015', (isset($fields['Mar_2015']['language'])? $fields['Mar_2015']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Mar_2015', $row['Mar_2015'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Apr 2015" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Apr 2015', (isset($fields['Apr_2015']['language'])? $fields['Apr_2015']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Apr_2015', $row['Apr_2015'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="May 2015" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('May 2015', (isset($fields['May_2015']['language'])? $fields['May_2015']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('May_2015', $row['May_2015'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jun 2015" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jun 2015', (isset($fields['Jun_2015']['language'])? $fields['Jun_2015']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jun_2015', $row['Jun_2015'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jul 2015" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jul 2015', (isset($fields['Jul_2015']['language'])? $fields['Jul_2015']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jul_2015', $row['Jul_2015'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Aug 2015" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Aug 2015', (isset($fields['Aug_2015']['language'])? $fields['Aug_2015']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Aug_2015', $row['Aug_2015'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Sep 2015" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Sep 2015', (isset($fields['Sep_2015']['language'])? $fields['Sep_2015']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Sep_2015', $row['Sep_2015'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Oct 2015" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Oct 2015', (isset($fields['Oct_2015']['language'])? $fields['Oct_2015']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Oct_2015', $row['Oct_2015'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Nov 2015" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Nov 2015', (isset($fields['Nov_2015']['language'])? $fields['Nov_2015']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Nov_2015', $row['Nov_2015'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Dec 2015" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Dec 2015', (isset($fields['Dec_2015']['language'])? $fields['Dec_2015']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Dec_2015', $row['Dec_2015'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jan 2016" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jan 2016', (isset($fields['Jan_2016']['language'])? $fields['Jan_2016']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jan_2016', $row['Jan_2016'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Feb 2016" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Feb 2016', (isset($fields['Feb_2016']['language'])? $fields['Feb_2016']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Feb_2016', $row['Feb_2016'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Mar 2016" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Mar 2016', (isset($fields['Mar_2016']['language'])? $fields['Mar_2016']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Mar_2016', $row['Mar_2016'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Apr 2016" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Apr 2016', (isset($fields['Apr_2016']['language'])? $fields['Apr_2016']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Apr_2016', $row['Apr_2016'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="May 2016" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('May 2016', (isset($fields['May_2016']['language'])? $fields['May_2016']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('May_2016', $row['May_2016'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jun 2016" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jun 2016', (isset($fields['Jun_2016']['language'])? $fields['Jun_2016']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jun_2016', $row['Jun_2016'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jul 2016" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jul 2016', (isset($fields['Jul_2016']['language'])? $fields['Jul_2016']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jul_2016', $row['Jul_2016'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Aug 2016" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Aug 2016', (isset($fields['Aug_2016']['language'])? $fields['Aug_2016']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Aug_2016', $row['Aug_2016'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Sep 2016" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Sep 2016', (isset($fields['Sep_2016']['language'])? $fields['Sep_2016']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Sep_2016', $row['Sep_2016'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Oct 2016" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Oct 2016', (isset($fields['Oct_2016']['language'])? $fields['Oct_2016']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Oct_2016', $row['Oct_2016'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Nov 2016" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Nov 2016', (isset($fields['Nov_2016']['language'])? $fields['Nov_2016']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Nov_2016', $row['Nov_2016'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Dec 2016" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Dec 2016', (isset($fields['Dec_2016']['language'])? $fields['Dec_2016']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Dec_2016', $row['Dec_2016'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jan 2017" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jan 2017', (isset($fields['Jan_2017']['language'])? $fields['Jan_2017']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jan_2017', $row['Jan_2017'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Feb 2017" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Feb 2017', (isset($fields['Feb_2017']['language'])? $fields['Feb_2017']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Feb_2017', $row['Feb_2017'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Mar 2017" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Mar 2017', (isset($fields['Mar_2017']['language'])? $fields['Mar_2017']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Mar_2017', $row['Mar_2017'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Apr 2017" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Apr 2017', (isset($fields['Apr_2017']['language'])? $fields['Apr_2017']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Apr_2017', $row['Apr_2017'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="May 2017" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('May 2017', (isset($fields['May_2017']['language'])? $fields['May_2017']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('May_2017', $row['May_2017'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jun 2017" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jun 2017', (isset($fields['Jun_2017']['language'])? $fields['Jun_2017']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jun_2017', $row['Jun_2017'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jul 2017" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jul 2017', (isset($fields['Jul_2017']['language'])? $fields['Jul_2017']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jul_2017', $row['Jul_2017'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Aug 2017" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Aug 2017', (isset($fields['Aug_2017']['language'])? $fields['Aug_2017']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Aug_2017', $row['Aug_2017'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Sep 2017" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Sep 2017', (isset($fields['Sep_2017']['language'])? $fields['Sep_2017']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Sep_2017', $row['Sep_2017'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Oct 2017" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Oct 2017', (isset($fields['Oct_2017']['language'])? $fields['Oct_2017']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Oct_2017', $row['Oct_2017'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Nov 2017" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Nov 2017', (isset($fields['Nov_2017']['language'])? $fields['Nov_2017']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Nov_2017', $row['Nov_2017'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Dec 2017" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Dec 2017', (isset($fields['Dec_2017']['language'])? $fields['Dec_2017']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Dec_2017', $row['Dec_2017'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jan 2018" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jan 2018', (isset($fields['Jan_2018']['language'])? $fields['Jan_2018']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jan_2018', $row['Jan_2018'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Feb 2018" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Feb 2018', (isset($fields['Feb_2018']['language'])? $fields['Feb_2018']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Feb_2018', $row['Feb_2018'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Mar 2018" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Mar 2018', (isset($fields['Mar_2018']['language'])? $fields['Mar_2018']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Mar_2018', $row['Mar_2018'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Apr 2018" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Apr 2018', (isset($fields['Apr_2018']['language'])? $fields['Apr_2018']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Apr_2018', $row['Apr_2018'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="May 2018" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('May 2018', (isset($fields['May_2018']['language'])? $fields['May_2018']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('May_2018', $row['May_2018'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jun 2018" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jun 2018', (isset($fields['Jun_2018']['language'])? $fields['Jun_2018']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jun_2018', $row['Jun_2018'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jul 2018" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jul 2018', (isset($fields['Jul_2018']['language'])? $fields['Jul_2018']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jul_2018', $row['Jul_2018'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Aug 2018" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Aug 2018', (isset($fields['Aug_2018']['language'])? $fields['Aug_2018']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Aug_2018', $row['Aug_2018'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Sep 2018" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Sep 2018', (isset($fields['Sep_2018']['language'])? $fields['Sep_2018']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Sep_2018', $row['Sep_2018'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Oct 2018" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Oct 2018', (isset($fields['Oct_2018']['language'])? $fields['Oct_2018']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Oct_2018', $row['Oct_2018'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Nov 2018" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Nov 2018', (isset($fields['Nov_2018']['language'])? $fields['Nov_2018']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Nov_2018', $row['Nov_2018'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Dec 2018" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Dec 2018', (isset($fields['Dec_2018']['language'])? $fields['Dec_2018']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Dec_2018', $row['Dec_2018'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jan 2019" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jan 2019', (isset($fields['Jan_2019']['language'])? $fields['Jan_2019']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jan_2019', $row['Jan_2019'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Feb 2019" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Feb 2019', (isset($fields['Feb_2019']['language'])? $fields['Feb_2019']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Feb_2019', $row['Feb_2019'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Mar 2019" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Mar 2019', (isset($fields['Mar_2019']['language'])? $fields['Mar_2019']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Mar_2019', $row['Mar_2019'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Apr 2019" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Apr 2019', (isset($fields['Apr_2019']['language'])? $fields['Apr_2019']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Apr_2019', $row['Apr_2019'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="May 2019" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('May 2019', (isset($fields['May_2019']['language'])? $fields['May_2019']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('May_2019', $row['May_2019'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jun 2019" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jun 2019', (isset($fields['Jun_2019']['language'])? $fields['Jun_2019']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jun_2019', $row['Jun_2019'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jul 2019" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jul 2019', (isset($fields['Jul_2019']['language'])? $fields['Jul_2019']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jul_2019', $row['Jul_2019'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Aug 2019" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Aug 2019', (isset($fields['Aug_2019']['language'])? $fields['Aug_2019']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Aug_2019', $row['Aug_2019'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Sep 2019" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Sep 2019', (isset($fields['Sep_2019']['language'])? $fields['Sep_2019']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Sep_2019', $row['Sep_2019'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Oct 2019" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Oct 2019', (isset($fields['Oct_2019']['language'])? $fields['Oct_2019']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Oct_2019', $row['Oct_2019'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Nov 2019" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Nov 2019', (isset($fields['Nov_2019']['language'])? $fields['Nov_2019']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Nov_2019', $row['Nov_2019'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Dec 2019" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Dec 2019', (isset($fields['Dec_2019']['language'])? $fields['Dec_2019']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Dec_2019', $row['Dec_2019'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jan 2020" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jan 2020', (isset($fields['Jan_2020']['language'])? $fields['Jan_2020']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jan_2020', $row['Jan_2020'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Feb 2020" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Feb 2020', (isset($fields['Feb_2020']['language'])? $fields['Feb_2020']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Feb_2020', $row['Feb_2020'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Mar 2020" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Mar 2020', (isset($fields['Mar_2020']['language'])? $fields['Mar_2020']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Mar_2020', $row['Mar_2020'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Apr 2020" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Apr 2020', (isset($fields['Apr_2020']['language'])? $fields['Apr_2020']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Apr_2020', $row['Apr_2020'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="May 2020" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('May 2020', (isset($fields['May_2020']['language'])? $fields['May_2020']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('May_2020', $row['May_2020'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jun 2020" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jun 2020', (isset($fields['Jun_2020']['language'])? $fields['Jun_2020']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jun_2020', $row['Jun_2020'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Jul 2020" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Jul 2020', (isset($fields['Jul_2020']['language'])? $fields['Jul_2020']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Jul_2020', $row['Jul_2020'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Aug 2020" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Aug 2020', (isset($fields['Aug_2020']['language'])? $fields['Aug_2020']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Aug_2020', $row['Aug_2020'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Sep 2020" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Sep 2020', (isset($fields['Sep_2020']['language'])? $fields['Sep_2020']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Sep_2020', $row['Sep_2020'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Oct 2020" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Oct 2020', (isset($fields['Oct_2020']['language'])? $fields['Oct_2020']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Oct_2020', $row['Oct_2020'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Nov 2020" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Nov 2020', (isset($fields['Nov_2020']['language'])? $fields['Nov_2020']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Nov_2020', $row['Nov_2020'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Dec 2020" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Dec 2020', (isset($fields['Dec_2020']['language'])? $fields['Dec_2020']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Dec_2020', $row['Dec_2020'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Contact Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Contact Id', (isset($fields['contact_id']['language'])? $fields['contact_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('contact_id', $row['contact_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Merch Contact Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Merch Contact Id', (isset($fields['merch_contact_id']['language'])? $fields['merch_contact_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('merch_contact_id', $row['merch_contact_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Field Manager Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Field Manager Id', (isset($fields['field_manager_id']['language'])? $fields['field_manager_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('field_manager_id', $row['field_manager_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Tech Manager Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Tech Manager Id', (isset($fields['tech_manager_id']['language'])? $fields['tech_manager_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('tech_manager_id', $row['tech_manager_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="No Games" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('No Games', (isset($fields['no_games']['language'])? $fields['no_games']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('no_games', $row['no_games'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Liftgate" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Liftgate', (isset($fields['liftgate']['language'])? $fields['liftgate']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('liftgate', $row['liftgate'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					  {!! Form::text('reporting', $row['reporting'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Sun" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Sun', (isset($fields['not_reporting_Sun']['language'])? $fields['not_reporting_Sun']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('not_reporting_Sun', $row['not_reporting_Sun'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Mon" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Mon', (isset($fields['not_reporting_Mon']['language'])? $fields['not_reporting_Mon']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('not_reporting_Mon', $row['not_reporting_Mon'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Tue" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Tue', (isset($fields['not_reporting_Tue']['language'])? $fields['not_reporting_Tue']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('not_reporting_Tue', $row['not_reporting_Tue'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Wed" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Wed', (isset($fields['not_reporting_Wed']['language'])? $fields['not_reporting_Wed']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('not_reporting_Wed', $row['not_reporting_Wed'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Thu" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Thu', (isset($fields['not_reporting_Thu']['language'])? $fields['not_reporting_Thu']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('not_reporting_Thu', $row['not_reporting_Thu'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Fri" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Fri', (isset($fields['not_reporting_Fri']['language'])? $fields['not_reporting_Fri']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('not_reporting_Fri', $row['not_reporting_Fri'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Not Reporting Sat" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Not Reporting Sat', (isset($fields['not_reporting_Sat']['language'])? $fields['not_reporting_Sat']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('not_reporting_Sat', $row['not_reporting_Sat'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Active" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Active', (isset($fields['active']['language'])? $fields['active']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('active', $row['active'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
	var form = $('#reportsFormAjax'); 
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