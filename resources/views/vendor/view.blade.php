@if($setting['view-method'] =='native')
<div class="sbox">
	<div class="sbox-title">  
		<h4> <i class="fa fa-eye"></i> <?php echo $pageTitle ;?>
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
							{{ SiteHelpers::activeLang('Vendor', (isset($fields['vendor_name']['language'])? $fields['vendor_name']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->vendor_name) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Street1', (isset($fields['street1']['language'])? $fields['street1']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->street1) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('City', (isset($fields['city']['language'])? $fields['city']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->city) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('State', (isset($fields['state']['language'])? $fields['state']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->state) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Zip Code', (isset($fields['zip']['language'])? $fields['zip']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->zip) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Contact Person', (isset($fields['contact']['language'])? $fields['contact']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->contact) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Email 1', (isset($fields['email']['language'])? $fields['email']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->email) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Website', (isset($fields['website']['language'])? $fields['website']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->website) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Games Contact', (isset($fields['games_contact_name']['language'])? $fields['games_contact_name']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->games_contact_name) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Games Phone', (isset($fields['games_contact_phone']['language'])? $fields['games_contact_phone']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->games_contact_phone) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Partner Hide', (isset($fields['partner_hide']['language'])? $fields['partner_hide']['language'] : array())) }}	
						</td>
						<td>{{ $row->partner_hide }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Vendor', (isset($fields['ismerch']['language'])? $fields['ismerch']['language'] : array())) }}	
						</td>
						<td>{{ $row->ismerch }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Merchant Vendor', (isset($fields['isgame']['language'])? $fields['isgame']['language'] : array())) }}	
						</td>
						<td>{{ $row->isgame }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Min Order Amt', (isset($fields['min_order_amt']['language'])? $fields['min_order_amt']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatZeroValue($row->min_order_amt,$nodata['min_order_amt']) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}	
						</td>
						<td>{{ $row->id }} </td>
						
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
