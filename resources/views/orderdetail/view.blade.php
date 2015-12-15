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
							{{ SiteHelpers::activeLang('OrderNumber', (isset($fields['orderNumber']['language'])? $fields['orderNumber']['language'] : array())) }}	
						</td>
						<td>{{ $row->orderNumber }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('ProductCode', (isset($fields['productCode']['language'])? $fields['productCode']['language'] : array())) }}	
						</td>
						<td>{{ $row->productCode }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('QuantityOrdered', (isset($fields['quantityOrdered']['language'])? $fields['quantityOrdered']['language'] : array())) }}	
						</td>
						<td>{{ $row->quantityOrdered }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('PriceEach', (isset($fields['priceEach']['language'])? $fields['priceEach']['language'] : array())) }}	
						</td>
						<td>{{ $row->priceEach }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('OrderLineNumber', (isset($fields['orderLineNumber']['language'])? $fields['orderLineNumber']['language'] : array())) }}	
						</td>
						<td>{{ $row->orderLineNumber }} </td>
						
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