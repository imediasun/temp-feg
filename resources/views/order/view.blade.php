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
							{{ SiteHelpers::activeLang('Po Number', (isset($fields['po_number']['language'])? $fields['po_number']['language'] : array())) }}	
						</td>
						<td>{{ $row->po_number }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Ordered', (isset($fields['date_ordered']['language'])? $fields['date_ordered']['language'] : array())) }}	
						</td>
						<td>{{ $row->date_ordered }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Ordered By', (isset($fields['user_id']['language'])? $fields['user_id']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->user_id,'user_id','1:users:id:username') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->location_id,'location_id','1:location:id:location_name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Order Type', (isset($fields['order_type_id']['language'])? $fields['order_type_id']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->order_type_id,'order_type_id','1:orders:id:order_type_id') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Vendor', (isset($fields['vendor_id']['language'])? $fields['vendor_id']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->vendor_id,'vendor_id','1:vendor:id:vendor_name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Order Description', (isset($fields['order_description']['language'])? $fields['order_description']['language'] : array())) }}	
						</td>
						<td>{{ $row->order_description }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Total Cost (NO "$")', (isset($fields['order_total']['language'])? $fields['order_total']['language'] : array())) }}
						</td>
						<td>{{ $row->order_total }} </td>

					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Order Status', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->status_id,'status_id','1:orders:id:status_id') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('PoONotes', (isset($fields['po_notes']['language'])? $fields['po_notes']['language'] : array())) }}	
						</td>
						<td>{{ $row->po_notes }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Office Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) }}	
						</td>
						<td>{{ $row->notes }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Company Id', (isset($fields['company_id']['language'])? $fields['company_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->company_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Warranty', (isset($fields['warranty']['language'])? $fields['warranty']['language'] : array())) }}	
						</td>
						<td>{{ $row->warranty }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Id', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Freight Id', (isset($fields['freight_id']['language'])? $fields['freight_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->freight_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Received', (isset($fields['date_received']['language'])? $fields['date_received']['language'] : array())) }}	
						</td>
						<td>{{ $row->date_received }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Received By', (isset($fields['received_by']['language'])? $fields['received_by']['language'] : array())) }}	
						</td>
						<td>{{ $row->received_by }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Quantity', (isset($fields['quantity']['language'])? $fields['quantity']['language'] : array())) }}	
						</td>
						<td>{{ $row->quantity }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Alt Address', (isset($fields['alt_address']['language'])? $fields['alt_address']['language'] : array())) }}	
						</td>
						<td>{{ $row->alt_address }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Request Ids', (isset($fields['request_ids']['language'])? $fields['request_ids']['language'] : array())) }}	
						</td>
						<td>{{ $row->request_ids }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Ids', (isset($fields['game_ids']['language'])? $fields['game_ids']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_ids }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Tracking Number', (isset($fields['tracking_number']['language'])? $fields['tracking_number']['language'] : array())) }}	
						</td>
						<td>{{ $row->tracking_number }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Added To Inventory', (isset($fields['added_to_inventory']['language'])? $fields['added_to_inventory']['language'] : array())) }}	
						</td>
						<td>{{ $row->added_to_inventory }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Order Content', (isset($fields['order_content']['language'])? $fields['order_content']['language'] : array())) }}	
						</td>
						<td>{{ $row->order_content }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('New Format', (isset($fields['new_format']['language'])? $fields['new_format']['language'] : array())) }}	
						</td>
						<td>{{ $row->new_format }} </td>
						
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