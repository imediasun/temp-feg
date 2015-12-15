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
							{{ SiteHelpers::activeLang('ItemID', (isset($fields['ItemID']['language'])? $fields['ItemID']['language'] : array())) }}	
						</td>
						<td>{{ $row->ItemID }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('InvoiceID', (isset($fields['InvoiceID']['language'])? $fields['InvoiceID']['language'] : array())) }}	
						</td>
						<td>{{ $row->InvoiceID }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('ProductID', (isset($fields['ProductID']['language'])? $fields['ProductID']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->ProductID,'ProductID','1:sb_invoiceproducts:ProductID:Name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Qty', (isset($fields['Qty']['language'])? $fields['Qty']['language'] : array())) }}	
						</td>
						<td>{{ $row->Qty }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Price', (isset($fields['Price']['language'])? $fields['Price']['language'] : array())) }}	
						</td>
						<td>{{ $row->Price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Total', (isset($fields['Total']['language'])? $fields['Total']['language'] : array())) }}	
						</td>
						<td>{{ $row->Total }} </td>
						
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