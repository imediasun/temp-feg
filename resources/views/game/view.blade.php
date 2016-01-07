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
							{{ SiteHelpers::activeLang('Game Name', (isset($fields['game_name']['language'])? $fields['game_name']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Prev Game Name', (isset($fields['prev_game_name']['language'])? $fields['prev_game_name']['language'] : array())) }}	
						</td>
						<td>{{ $row->prev_game_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Type Id', (isset($fields['game_type_id']['language'])? $fields['game_type_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_type_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Version Id', (isset($fields['version_id']['language'])? $fields['version_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->version_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Players', (isset($fields['players']['language'])? $fields['players']['language'] : array())) }}	
						</td>
						<td>{{ $row->players }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Monitor Size', (isset($fields['monitor_size']['language'])? $fields['monitor_size']['language'] : array())) }}	
						</td>
						<td>{{ $row->monitor_size }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Dba', (isset($fields['dba']['language'])? $fields['dba']['language'] : array())) }}	
						</td>
						<td>{{ $row->dba }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Sacoa', (isset($fields['sacoa']['language'])? $fields['sacoa']['language'] : array())) }}	
						</td>
						<td>{{ $row->sacoa }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Embed', (isset($fields['embed']['language'])? $fields['embed']['language'] : array())) }}	
						</td>
						<td>{{ $row->embed }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Rfid', (isset($fields['rfid']['language'])? $fields['rfid']['language'] : array())) }}	
						</td>
						<td>{{ $row->rfid }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) }}	
						</td>
						<td>{{ $row->notes }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location Id', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->location_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Mfg Id', (isset($fields['mfg_id']['language'])? $fields['mfg_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->mfg_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Source', (isset($fields['source']['language'])? $fields['source']['language'] : array())) }}	
						</td>
						<td>{{ $row->source }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Serial', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) }}	
						</td>
						<td>{{ $row->serial }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date In Service', (isset($fields['date_in_service']['language'])? $fields['date_in_service']['language'] : array())) }}	
						</td>
						<td>{{ $row->date_in_service }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Status Id', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->status_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Setup Status Id', (isset($fields['game_setup_status_id']['language'])? $fields['game_setup_status_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_setup_status_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Intended First Location', (isset($fields['intended_first_location']['language'])? $fields['intended_first_location']['language'] : array())) }}	
						</td>
						<td>{{ $row->intended_first_location }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Ship Delay Reason', (isset($fields['ship_delay_reason']['language'])? $fields['ship_delay_reason']['language'] : array())) }}	
						</td>
						<td>{{ $row->ship_delay_reason }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Shipped', (isset($fields['date_shipped']['language'])? $fields['date_shipped']['language'] : array())) }}	
						</td>
						<td>{{ $row->date_shipped }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Freight Order Id', (isset($fields['freight_order_id']['language'])? $fields['freight_order_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->freight_order_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Last Move', (isset($fields['date_last_move']['language'])? $fields['date_last_move']['language'] : array())) }}	
						</td>
						<td>{{ $row->date_last_move }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Last Edited By', (isset($fields['last_edited_by']['language'])? $fields['last_edited_by']['language'] : array())) }}	
						</td>
						<td>{{ $row->last_edited_by }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Last Edited On', (isset($fields['last_edited_on']['language'])? $fields['last_edited_on']['language'] : array())) }}	
						</td>
						<td>{{ $row->last_edited_on }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Prev Location Id', (isset($fields['prev_location_id']['language'])? $fields['prev_location_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->prev_location_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('For Sale', (isset($fields['for_sale']['language'])? $fields['for_sale']['language'] : array())) }}	
						</td>
						<td>{{ $row->for_sale }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Sale Price', (isset($fields['sale_price']['language'])? $fields['sale_price']['language'] : array())) }}	
						</td>
						<td>{{ $row->sale_price }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Sale Pending', (isset($fields['sale_pending']['language'])? $fields['sale_pending']['language'] : array())) }}	
						</td>
						<td>{{ $row->sale_pending }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Sold', (isset($fields['sold']['language'])? $fields['sold']['language'] : array())) }}	
						</td>
						<td>{{ $row->sold }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Sold', (isset($fields['date_sold']['language'])? $fields['date_sold']['language'] : array())) }}	
						</td>
						<td>{{ $row->date_sold }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Sold To', (isset($fields['sold_to']['language'])? $fields['sold_to']['language'] : array())) }}	
						</td>
						<td>{{ $row->sold_to }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Move Id', (isset($fields['game_move_id']['language'])? $fields['game_move_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_move_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Service Id', (isset($fields['game_service_id']['language'])? $fields['game_service_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_service_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Title Id', (isset($fields['game_title_id']['language'])? $fields['game_title_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_title_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Version', (isset($fields['version']['language'])? $fields['version']['language'] : array())) }}	
						</td>
						<td>{{ $row->version }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Num Prize Meters', (isset($fields['num_prize_meters']['language'])? $fields['num_prize_meters']['language'] : array())) }}	
						</td>
						<td>{{ $row->num_prize_meters }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Num Prizes', (isset($fields['num_prizes']['language'])? $fields['num_prizes']['language'] : array())) }}	
						</td>
						<td>{{ $row->num_prizes }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Id', (isset($fields['product_id']['language'])? $fields['product_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Id 1', (isset($fields['product_id_1']['language'])? $fields['product_id_1']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_id_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Qty 1', (isset($fields['product_qty_1']['language'])? $fields['product_qty_1']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_qty_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Id 2', (isset($fields['product_id_2']['language'])? $fields['product_id_2']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_id_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Qty 2', (isset($fields['product_qty_2']['language'])? $fields['product_qty_2']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_qty_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Id 3', (isset($fields['product_id_3']['language'])? $fields['product_id_3']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_id_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Qty 3', (isset($fields['product_qty_3']['language'])? $fields['product_qty_3']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_qty_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Id 4', (isset($fields['product_id_4']['language'])? $fields['product_id_4']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_id_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Qty 4', (isset($fields['product_qty_4']['language'])? $fields['product_qty_4']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_qty_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Id 5', (isset($fields['product_id_5']['language'])? $fields['product_id_5']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_id_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Qty 5', (isset($fields['product_qty_5']['language'])? $fields['product_qty_5']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_qty_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Price Per Play', (isset($fields['price_per_play']['language'])? $fields['price_per_play']['language'] : array())) }}	
						</td>
						<td>{{ $row->price_per_play }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Last Product Meter 1', (isset($fields['last_product_meter_1']['language'])? $fields['last_product_meter_1']['language'] : array())) }}	
						</td>
						<td>{{ $row->last_product_meter_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Last Product Meter 2', (isset($fields['last_product_meter_2']['language'])? $fields['last_product_meter_2']['language'] : array())) }}	
						</td>
						<td>{{ $row->last_product_meter_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Last Product Meter 3', (isset($fields['last_product_meter_3']['language'])? $fields['last_product_meter_3']['language'] : array())) }}	
						</td>
						<td>{{ $row->last_product_meter_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Last Product Meter 4', (isset($fields['last_product_meter_4']['language'])? $fields['last_product_meter_4']['language'] : array())) }}	
						</td>
						<td>{{ $row->last_product_meter_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Last Product Meter 5', (isset($fields['last_product_meter_5']['language'])? $fields['last_product_meter_5']['language'] : array())) }}	
						</td>
						<td>{{ $row->last_product_meter_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Last Product Meter 6', (isset($fields['last_product_meter_6']['language'])? $fields['last_product_meter_6']['language'] : array())) }}	
						</td>
						<td>{{ $row->last_product_meter_6 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Last Product Meter 7', (isset($fields['last_product_meter_7']['language'])? $fields['last_product_meter_7']['language'] : array())) }}	
						</td>
						<td>{{ $row->last_product_meter_7 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Last Product Meter 8', (isset($fields['last_product_meter_8']['language'])? $fields['last_product_meter_8']['language'] : array())) }}	
						</td>
						<td>{{ $row->last_product_meter_8 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Last Meter Date', (isset($fields['last_meter_date']['language'])? $fields['last_meter_date']['language'] : array())) }}	
						</td>
						<td>{{ $row->last_meter_date }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Not Debit', (isset($fields['not_debit']['language'])? $fields['not_debit']['language'] : array())) }}	
						</td>
						<td>{{ $row->not_debit }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Not Debit Reason', (isset($fields['not_debit_reason']['language'])? $fields['not_debit_reason']['language'] : array())) }}	
						</td>
						<td>{{ $row->not_debit_reason }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Linked To Game', (isset($fields['linked_to_game']['language'])? $fields['linked_to_game']['language'] : array())) }}	
						</td>
						<td>{{ $row->linked_to_game }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Test Piece', (isset($fields['test_piece']['language'])? $fields['test_piece']['language'] : array())) }}	
						</td>
						<td>{{ $row->test_piece }} </td>
						
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