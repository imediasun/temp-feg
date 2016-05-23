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
							{{ SiteHelpers::activeLang('Date Submitted', (isset($fields['date_submitted']['language'])? $fields['date_submitted']['language'] : array())) }}
						</td>
						<td>{{ $row->date_submitted }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Booked', (isset($fields['date_booked']['language'])? $fields['date_booked']['language'] : array())) }}
						</td>
						<td>{{ $row->date_booked }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Paid', (isset($fields['date_paid']['language'])? $fields['date_paid']['language'] : array())) }}
						</td>
						<td>{{ $row->date_paid }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Vend From', (isset($fields['vend_from']['language'])? $fields['vend_from']['language'] : array())) }}
						</td>
						<td>{{ $row->vend_from }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Vend To', (isset($fields['vend_to']['language'])? $fields['vend_to']['language'] : array())) }}
						</td>
						<td>{{ $row->vend_to }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc From', (isset($fields['loc_from']['language'])? $fields['loc_from']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_from }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Add Name', (isset($fields['from_add_name']['language'])? $fields['from_add_name']['language'] : array())) }}
						</td>
						<td>{{ $row->from_add_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Add Street', (isset($fields['from_add_street']['language'])? $fields['from_add_street']['language'] : array())) }}
						</td>
						<td>{{ $row->from_add_street }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Add City', (isset($fields['from_add_city']['language'])? $fields['from_add_city']['language'] : array())) }}
						</td>
						<td>{{ $row->from_add_city }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Add State', (isset($fields['from_add_state']['language'])? $fields['from_add_state']['language'] : array())) }}
						</td>
						<td>{{ $row->from_add_state }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Add Zip', (isset($fields['from_add_zip']['language'])? $fields['from_add_zip']['language'] : array())) }}
						</td>
						<td>{{ $row->from_add_zip }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Contact Name', (isset($fields['from_contact_name']['language'])? $fields['from_contact_name']['language'] : array())) }}
						</td>
						<td>{{ $row->from_contact_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Contact Email', (isset($fields['from_contact_email']['language'])? $fields['from_contact_email']['language'] : array())) }}
						</td>
						<td>{{ $row->from_contact_email }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Contact Phone', (isset($fields['from_contact_phone']['language'])? $fields['from_contact_phone']['language'] : array())) }}
						</td>
						<td>{{ $row->from_contact_phone }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Loading Info', (isset($fields['from_loading_info']['language'])? $fields['from_loading_info']['language'] : array())) }}
						</td>
						<td>{{ $row->from_loading_info }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Add Name', (isset($fields['to_add_name']['language'])? $fields['to_add_name']['language'] : array())) }}
						</td>
						<td>{{ $row->to_add_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Add Street', (isset($fields['to_add_street']['language'])? $fields['to_add_street']['language'] : array())) }}
						</td>
						<td>{{ $row->to_add_street }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Add City', (isset($fields['to_add_city']['language'])? $fields['to_add_city']['language'] : array())) }}
						</td>
						<td>{{ $row->to_add_city }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Add State', (isset($fields['to_add_state']['language'])? $fields['to_add_state']['language'] : array())) }}
						</td>
						<td>{{ $row->to_add_state }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Add Zip', (isset($fields['to_add_zip']['language'])? $fields['to_add_zip']['language'] : array())) }}
						</td>
						<td>{{ $row->to_add_zip }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Contact Name', (isset($fields['to_contact_name']['language'])? $fields['to_contact_name']['language'] : array())) }}
						</td>
						<td>{{ $row->to_contact_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Contact Email', (isset($fields['to_contact_email']['language'])? $fields['to_contact_email']['language'] : array())) }}
						</td>
						<td>{{ $row->to_contact_email }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Contact Phone', (isset($fields['to_contact_phone']['language'])? $fields['to_contact_phone']['language'] : array())) }}
						</td>
						<td>{{ $row->to_contact_phone }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Loading Info', (isset($fields['to_loading_info']['language'])? $fields['to_loading_info']['language'] : array())) }}
						</td>
						<td>{{ $row->to_loading_info }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) }}
						</td>
						<td>{{ $row->notes }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Num Games Per Destination', (isset($fields['num_games_per_destination']['language'])? $fields['num_games_per_destination']['language'] : array())) }}
						</td>
						<td>{{ $row->num_games_per_destination }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('External Ship Quote', (isset($fields['external_ship_quote']['language'])? $fields['external_ship_quote']['language'] : array())) }}
						</td>
						<td>{{ $row->external_ship_quote }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('External Ship Trucking Co', (isset($fields['external_ship_trucking_co']['language'])? $fields['external_ship_trucking_co']['language'] : array())) }}
						</td>
						<td>{{ $row->external_ship_trucking_co }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('External Ship Pro', (isset($fields['external_ship_pro']['language'])? $fields['external_ship_pro']['language'] : array())) }}
						</td>
						<td>{{ $row->external_ship_pro }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Email Notes', (isset($fields['email_notes']['language'])? $fields['email_notes']['language'] : array())) }}
						</td>
						<td>{{ $row->email_notes }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Status', (isset($fields['status']['language'])? $fields['status']['language'] : array())) }}
						</td>
						<td>{{ $row->status }} </td>
						
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