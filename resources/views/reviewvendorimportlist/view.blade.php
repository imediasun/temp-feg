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
							{{ SiteHelpers::activeLang('Upc Barcode', (isset($fields['upc_barcode']['language'])? $fields['upc_barcode']['language'] : array())) }}
						</td>
						<td>{{ $row->upc_barcode }} </td>
						
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
							{{ SiteHelpers::activeLang('Netsuite Description', (isset($fields['netsuite_description']['language'])? $fields['netsuite_description']['language'] : array())) }}
						</td>
						<td>{{ $row->netsuite_description }} </td>
						
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
							{{ SiteHelpers::activeLang('Reserved Qty Reason', (isset($fields['reserved_qty_reason']['language'])? $fields['reserved_qty_reason']['language'] : array())) }}
						</td>
						<td>{{ $row->reserved_qty_reason }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Variation Id', (isset($fields['variation_id']['language'])? $fields['variation_id']['language'] : array())) }}
						</td>
						<td>{{ $row->variation_id }} </td>
						
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
							{{ SiteHelpers::activeLang('Inactive By', (isset($fields['inactive_by']['language'])? $fields['inactive_by']['language'] : array())) }}
						</td>
						<td>{{ $row->inactive_by }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Activated At', (isset($fields['activated_at']['language'])? $fields['activated_at']['language'] : array())) }}
						</td>
						<td>{{ $row->activated_at }} </td>
						
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
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Created At', (isset($fields['created_at']['language'])? $fields['created_at']['language'] : array())) }}
						</td>
						<td>{{ $row->created_at }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Updated At', (isset($fields['updated_at']['language'])? $fields['updated_at']['language'] : array())) }}
						</td>
						<td>{{ $row->updated_at }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Expense Category', (isset($fields['expense_category']['language'])? $fields['expense_category']['language'] : array())) }}
						</td>
						<td>{{ $row->expense_category }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Exclude Export', (isset($fields['exclude_export']['language'])? $fields['exclude_export']['language'] : array())) }}
						</td>
						<td>{{ $row->exclude_export }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Allow Negative Reserve Qty', (isset($fields['allow_negative_reserve_qty']['language'])? $fields['allow_negative_reserve_qty']['language'] : array())) }}
						</td>
						<td>{{ $row->allow_negative_reserve_qty }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Reserved Qty Limit', (isset($fields['reserved_qty_limit']['language'])? $fields['reserved_qty_limit']['language'] : array())) }}
						</td>
						<td>{{ $row->reserved_qty_limit }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Is Default Expense Category', (isset($fields['is_default_expense_category']['language'])? $fields['is_default_expense_category']['language'] : array())) }}
						</td>
						<td>{{ $row->is_default_expense_category }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Send Email Alert', (isset($fields['send_email_alert']['language'])? $fields['send_email_alert']['language'] : array())) }}
						</td>
						<td>{{ $row->send_email_alert }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Import Vendor Id', (isset($fields['import_vendor_id']['language'])? $fields['import_vendor_id']['language'] : array())) }}
						</td>
						<td>{{ $row->import_vendor_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Id', (isset($fields['product_id']['language'])? $fields['product_id']['language'] : array())) }}
						</td>
						<td>{{ $row->product_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Is Imported', (isset($fields['is_imported']['language'])? $fields['is_imported']['language'] : array())) }}
						</td>
						<td>{{ $row->is_imported }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Imported By', (isset($fields['imported_by']['language'])? $fields['imported_by']['language'] : array())) }}
						</td>
						<td>{{ $row->imported_by }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Imported At', (isset($fields['imported_at']['language'])? $fields['imported_at']['language'] : array())) }}
						</td>
						<td>{{ $row->imported_at }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Is Excluded', (isset($fields['is_omitted']['language'])? $fields['is_omitted']['language'] : array())) }}
						</td>
						<td>{{ $row->is_omitted }} </td>
						
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