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
							{{ SiteHelpers::activeLang('Sku', (isset($fields['sku']['language'])? $fields['sku']['language'] : array())) }}	
						</td>
						<td>{{ $row->sku }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Vendor Description', (isset($fields['vendor_description']['language'])? $fields['vendor_description']['language'] : array())) }}	
						</td>
						<td>{{ $row->vendor_description }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Item Description', (isset($fields['item_description']['language'])? $fields['item_description']['language'] : array())) }}	
						</td>
						<td>{{ $row->item_description }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Size', (isset($fields['size']['language'])? $fields['size']['language'] : array())) }}	
						</td>
						<td>{{ $row->size }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Details', (isset($fields['details']['language'])? $fields['details']['language'] : array())) }}	
						</td>
						<td>{{ $row->details }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Num Items', (isset($fields['num_items']['language'])? $fields['num_items']['language'] : array())) }}	
						</td>
						<td>{{ $row->num_items }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Vendor Id', (isset($fields['vendor_id']['language'])? $fields['vendor_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->vendor_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Unit Price', (isset($fields['unit_price']['language'])? $fields['unit_price']['language'] : array())) }}	
						</td>
						<td>{{ $row->unit_price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Case Price', (isset($fields['case_price']['language'])? $fields['case_price']['language'] : array())) }}	
						</td>
						<td>{{ $row->case_price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Retail Price', (isset($fields['retail_price']['language'])? $fields['retail_price']['language'] : array())) }}	
						</td>
						<td>{{ $row->retail_price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])? $fields['ticket_value']['language'] : array())) }}	
						</td>
						<td>{{ $row->ticket_value }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Prod Type Id', (isset($fields['prod_type_id']['language'])? $fields['prod_type_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->prod_type_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Prod Sub Type Id', (isset($fields['prod_sub_type_id']['language'])? $fields['prod_sub_type_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->prod_sub_type_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Is Reserved', (isset($fields['is_reserved']['language'])? $fields['is_reserved']['language'] : array())) }}	
						</td>
						<td>{{ $row->is_reserved }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Reserved Qty', (isset($fields['reserved_qty']['language'])? $fields['reserved_qty']['language'] : array())) }}	
						</td>
						<td>{{ $row->reserved_qty }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Min Order Amt', (isset($fields['min_order_amt']['language'])? $fields['min_order_amt']['language'] : array())) }}	
						</td>
						<td>{{ $row->min_order_amt }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Img', (isset($fields['img']['language'])? $fields['img']['language'] : array())) }}	
						</td>
						<td>{{ $row->img }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Inactive', (isset($fields['inactive']['language'])? $fields['inactive']['language'] : array())) }}	
						</td>
						<td>{{ $row->inactive }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Eta', (isset($fields['eta']['language'])? $fields['eta']['language'] : array())) }}	
						</td>
						<td>{{ $row->eta }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('In Development', (isset($fields['in_development']['language'])? $fields['in_development']['language'] : array())) }}	
						</td>
						<td>{{ $row->in_development }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Limit To Loc Group Id', (isset($fields['limit_to_loc_group_id']['language'])? $fields['limit_to_loc_group_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->limit_to_loc_group_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Added', (isset($fields['date_added']['language'])? $fields['date_added']['language'] : array())) }}	
						</td>
						<td>{{ $row->date_added }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Hot Item', (isset($fields['hot_item']['language'])? $fields['hot_item']['language'] : array())) }}	
						</td>
						<td>{{ $row->hot_item }} </td>
						
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