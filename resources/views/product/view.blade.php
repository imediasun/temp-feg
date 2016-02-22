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
							{{ SiteHelpers::activeLang('IMG', (isset($fields['img']['language'])? $fields['img']['language'] : array())) }}	
						</td>
						<td>{{ $row->img }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Vendor', (isset($fields['vendor_id']['language'])? $fields['vendor_id']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->vendor_id,'vendor_id','1:vendor:id:vendor_name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Description', (isset($fields['vendor_description']['language'])? $fields['vendor_description']['language'] : array())) }}	
						</td>
						<td>{{ $row->vendor_description }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Size', (isset($fields['size']['language'])? $fields['size']['language'] : array())) }}	
						</td>
						<td>{{ $row->size }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Type', (isset($fields['prod_type_id']['language'])? $fields['prod_type_id']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->prod_type_id,'prod_type_id','1:product_type:id:product_type') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Sub Type', (isset($fields['prod_sub_type_id']['language'])? $fields['prod_sub_type_id']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->prod_sub_type_id,'prod_sub_type_id','1:product_type:id:product_type') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])? $fields['ticket_value']['language'] : array())) }}	
						</td>
						<td>{{ $row->ticket_value }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Price Per Unit', (isset($fields['unit_price']['language'])? $fields['unit_price']['language'] : array())) }}	
						</td>
						<td>{{ $row->unit_price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Retail Price', (isset($fields['retail_price']['language'])? $fields['retail_price']['language'] : array())) }}	
						</td>
						<td>{{ $row->retail_price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Case  Per Price', (isset($fields['case_price']['language'])? $fields['case_price']['language'] : array())) }}	
						</td>
						<td>{{ $row->case_price }} </td>
						
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