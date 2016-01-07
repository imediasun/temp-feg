@if($setting['view-method'] =='native')
<div class="sbox">
	<div class="sbox-title">  
		<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
			<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')">
			<i class="fa fa fa-times"></i></a>
		</h4>
	 </div>

	<div class="sbox-content"> 
@endif	

		<table class="table table-striped table-bordered" >
			<tbody>	
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}	
						</td>
						<td>{{ $row->id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location Name', (isset($fields['location_name']['language'])? $fields['location_name']['language'] : array())) }}	
						</td>
						<td>{{ $row->location_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location Name Short', (isset($fields['location_name_short']['language'])? $fields['location_name_short']['language'] : array())) }}	
						</td>
						<td>{{ $row->location_name_short }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Mail Attention', (isset($fields['mail_attention']['language'])? $fields['mail_attention']['language'] : array())) }}	
						</td>
						<td>{{ $row->mail_attention }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Street1', (isset($fields['street1']['language'])? $fields['street1']['language'] : array())) }}	
						</td>
						<td>{{ $row->street1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('City', (isset($fields['city']['language'])? $fields['city']['language'] : array())) }}	
						</td>
						<td>{{ $row->city }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('State', (isset($fields['state']['language'])? $fields['state']['language'] : array())) }}	
						</td>
						<td>{{ $row->state }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Zip', (isset($fields['zip']['language'])? $fields['zip']['language'] : array())) }}	
						</td>
						<td>{{ $row->zip }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Attn', (isset($fields['attn']['language'])? $fields['attn']['language'] : array())) }}	
						</td>
						<td>{{ $row->attn }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Company Id', (isset($fields['company_id']['language'])? $fields['company_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->company_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Self Owned', (isset($fields['self_owned']['language'])? $fields['self_owned']['language'] : array())) }}	
						</td>
						<td>{{ $row->self_owned }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loading Info', (isset($fields['loading_info']['language'])? $fields['loading_info']['language'] : array())) }}	
						</td>
						<td>{{ $row->loading_info }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Opened', (isset($fields['date_opened']['language'])? $fields['date_opened']['language'] : array())) }}	
						</td>
						<td>{{ $row->date_opened }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Closed', (isset($fields['date_closed']['language'])? $fields['date_closed']['language'] : array())) }}	
						</td>
						<td>{{ $row->date_closed }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Region Id', (isset($fields['region_id']['language'])? $fields['region_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->region_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc Group Id', (isset($fields['loc_group_id']['language'])? $fields['loc_group_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->loc_group_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Debit Type Id', (isset($fields['debit_type_id']['language'])? $fields['debit_type_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->debit_type_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Can Ship', (isset($fields['can_ship']['language'])? $fields['can_ship']['language'] : array())) }}	
						</td>
						<td>{{ $row->can_ship }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc Ship To', (isset($fields['loc_ship_to']['language'])? $fields['loc_ship_to']['language'] : array())) }}	
						</td>
						<td>{{ $row->loc_ship_to }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Phone', (isset($fields['phone']['language'])? $fields['phone']['language'] : array())) }}	
						</td>
						<td>{{ $row->phone }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bestbuy Store Number', (isset($fields['bestbuy_store_number']['language'])? $fields['bestbuy_store_number']['language'] : array())) }}	
						</td>
						<td>{{ $row->bestbuy_store_number }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Debit Type', (isset($fields['bill_debit_type']['language'])? $fields['bill_debit_type']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_debit_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Debit Amt', (isset($fields['bill_debit_amt']['language'])? $fields['bill_debit_amt']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_debit_amt }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Debit Detail', (isset($fields['bill_debit_detail']['language'])? $fields['bill_debit_detail']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_debit_detail }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Ticket Type', (isset($fields['bill_ticket_type']['language'])? $fields['bill_ticket_type']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_ticket_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Ticket Amt', (isset($fields['bill_ticket_amt']['language'])? $fields['bill_ticket_amt']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_ticket_amt }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Ticket Detail', (isset($fields['bill_ticket_detail']['language'])? $fields['bill_ticket_detail']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_ticket_detail }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Thermalpaper Type', (isset($fields['bill_thermalpaper_type']['language'])? $fields['bill_thermalpaper_type']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_thermalpaper_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Thermalpaper Amt', (isset($fields['bill_thermalpaper_amt']['language'])? $fields['bill_thermalpaper_amt']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_thermalpaper_amt }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Thermalpaper Detail', (isset($fields['bill_thermalpaper_detail']['language'])? $fields['bill_thermalpaper_detail']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_thermalpaper_detail }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Token Type', (isset($fields['bill_token_type']['language'])? $fields['bill_token_type']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_token_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Token Amt', (isset($fields['bill_token_amt']['language'])? $fields['bill_token_amt']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_token_amt }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Token Detail', (isset($fields['bill_token_detail']['language'])? $fields['bill_token_detail']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_token_detail }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill License Type', (isset($fields['bill_license_type']['language'])? $fields['bill_license_type']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_license_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill License Amt', (isset($fields['bill_license_amt']['language'])? $fields['bill_license_amt']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_license_amt }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill License Detail', (isset($fields['bill_license_detail']['language'])? $fields['bill_license_detail']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_license_detail }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Attraction Type', (isset($fields['bill_attraction_type']['language'])? $fields['bill_attraction_type']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_attraction_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Attraction Amt', (isset($fields['bill_attraction_amt']['language'])? $fields['bill_attraction_amt']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_attraction_amt }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Attraction Detail', (isset($fields['bill_attraction_detail']['language'])? $fields['bill_attraction_detail']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_attraction_detail }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Redemption Type', (isset($fields['bill_redemption_type']['language'])? $fields['bill_redemption_type']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_redemption_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Redemption Amt', (isset($fields['bill_redemption_amt']['language'])? $fields['bill_redemption_amt']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_redemption_amt }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Redemption Detail', (isset($fields['bill_redemption_detail']['language'])? $fields['bill_redemption_detail']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_redemption_detail }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Instant Type', (isset($fields['bill_instant_type']['language'])? $fields['bill_instant_type']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_instant_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Instant Amt', (isset($fields['bill_instant_amt']['language'])? $fields['bill_instant_amt']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_instant_amt }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Bill Instant Detail', (isset($fields['bill_instant_detail']['language'])? $fields['bill_instant_detail']['language'] : array())) }}	
						</td>
						<td>{{ $row->bill_instant_detail }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Contact Id', (isset($fields['contact_id']['language'])? $fields['contact_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->contact_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Merch Contact Id', (isset($fields['merch_contact_id']['language'])? $fields['merch_contact_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->merch_contact_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Field Manager Id', (isset($fields['field_manager_id']['language'])? $fields['field_manager_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->field_manager_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Tech Manager Id', (isset($fields['tech_manager_id']['language'])? $fields['tech_manager_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->tech_manager_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('No Games', (isset($fields['no_games']['language'])? $fields['no_games']['language'] : array())) }}	
						</td>
						<td>{{ $row->no_games }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Liftgate', (isset($fields['liftgate']['language'])? $fields['liftgate']['language'] : array())) }}	
						</td>
						<td>{{ $row->liftgate }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Ipaddress', (isset($fields['ipaddress']['language'])? $fields['ipaddress']['language'] : array())) }}	
						</td>
						<td>{{ $row->ipaddress }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Reporting', (isset($fields['reporting']['language'])? $fields['reporting']['language'] : array())) }}	
						</td>
						<td>{{ $row->reporting }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Not Reporting Sun', (isset($fields['not_reporting_Sun']['language'])? $fields['not_reporting_Sun']['language'] : array())) }}	
						</td>
						<td>{{ $row->not_reporting_Sun }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Not Reporting Mon', (isset($fields['not_reporting_Mon']['language'])? $fields['not_reporting_Mon']['language'] : array())) }}	
						</td>
						<td>{{ $row->not_reporting_Mon }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Not Reporting Tue', (isset($fields['not_reporting_Tue']['language'])? $fields['not_reporting_Tue']['language'] : array())) }}	
						</td>
						<td>{{ $row->not_reporting_Tue }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Not Reporting Wed', (isset($fields['not_reporting_Wed']['language'])? $fields['not_reporting_Wed']['language'] : array())) }}	
						</td>
						<td>{{ $row->not_reporting_Wed }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Not Reporting Thu', (isset($fields['not_reporting_Thu']['language'])? $fields['not_reporting_Thu']['language'] : array())) }}	
						</td>
						<td>{{ $row->not_reporting_Thu }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Not Reporting Fri', (isset($fields['not_reporting_Fri']['language'])? $fields['not_reporting_Fri']['language'] : array())) }}	
						</td>
						<td>{{ $row->not_reporting_Fri }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Not Reporting Sat', (isset($fields['not_reporting_Sat']['language'])? $fields['not_reporting_Sat']['language'] : array())) }}	
						</td>
						<td>{{ $row->not_reporting_Sat }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Active', (isset($fields['active']['language'])? $fields['active']['language'] : array())) }}	
						</td>
						<td>{{ $row->active }} </td>
						
					</tr>
				
			</tbody>	
		</table>  
			
		 	

@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

<script>
$(document).ready(function(){

});
</script>	