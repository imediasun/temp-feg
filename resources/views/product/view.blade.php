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
							{{ SiteHelpers::activeLang('Code', (isset($fields['productCode']['language'])? $fields['productCode']['language'] : array())) }}	
						</td>
						<td>{{ $row->productCode }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Name', (isset($fields['productName']['language'])? $fields['productName']['language'] : array())) }}	
						</td>
						<td>{{ $row->productName }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Scale', (isset($fields['productScale']['language'])? $fields['productScale']['language'] : array())) }}	
						</td>
						<td>{{ $row->productScale }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Vendor', (isset($fields['productVendor']['language'])? $fields['productVendor']['language'] : array())) }}	
						</td>
						<td>{{ $row->productVendor }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Description', (isset($fields['productDescription']['language'])? $fields['productDescription']['language'] : array())) }}	
						</td>
						<td>{{ $row->productDescription }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Stock', (isset($fields['quantityInStock']['language'])? $fields['quantityInStock']['language'] : array())) }}	
						</td>
						<td>{{ $row->quantityInStock }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('BuyPrice', (isset($fields['buyPrice']['language'])? $fields['buyPrice']['language'] : array())) }}	
						</td>
						<td>{{ $row->buyPrice }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('MSRP', (isset($fields['MSRP']['language'])? $fields['MSRP']['language'] : array())) }}	
						</td>
						<td>{{ $row->MSRP }} </td>
						
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