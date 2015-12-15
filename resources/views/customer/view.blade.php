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
							{{ SiteHelpers::activeLang('CustomerNumber', (isset($fields['customerNumber']['language'])? $fields['customerNumber']['language'] : array())) }}	
						</td>
						<td>{{ $row->customerNumber }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('CustomerName', (isset($fields['customerName']['language'])? $fields['customerName']['language'] : array())) }}	
						</td>
						<td>{{ $row->customerName }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('ContactLastName', (isset($fields['contactLastName']['language'])? $fields['contactLastName']['language'] : array())) }}	
						</td>
						<td>{{ $row->contactLastName }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('ContactFirstName', (isset($fields['contactFirstName']['language'])? $fields['contactFirstName']['language'] : array())) }}	
						</td>
						<td>{{ $row->contactFirstName }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Phone', (isset($fields['phone']['language'])? $fields['phone']['language'] : array())) }}	
						</td>
						<td>{{ $row->phone }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('AddressLine1', (isset($fields['addressLine1']['language'])? $fields['addressLine1']['language'] : array())) }}	
						</td>
						<td>{{ $row->addressLine1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('AddressLine2', (isset($fields['addressLine2']['language'])? $fields['addressLine2']['language'] : array())) }}	
						</td>
						<td>{{ $row->addressLine2 }} </td>
						
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
							{{ SiteHelpers::activeLang('PostalCode', (isset($fields['postalCode']['language'])? $fields['postalCode']['language'] : array())) }}	
						</td>
						<td>{{ $row->postalCode }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Country', (isset($fields['country']['language'])? $fields['country']['language'] : array())) }}	
						</td>
						<td>{{ $row->country }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('SalesRepEmployeeNumber', (isset($fields['salesRepEmployeeNumber']['language'])? $fields['salesRepEmployeeNumber']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->salesRepEmployeeNumber,'salesRepEmployeeNumber','1:employees:employeeNumber:firstName|lastName') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('CreditLimit', (isset($fields['creditLimit']['language'])? $fields['creditLimit']['language'] : array())) }}	
						</td>
						<td>{{ $row->creditLimit }} </td>
						
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