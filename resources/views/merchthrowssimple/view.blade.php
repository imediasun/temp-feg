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
							{{ SiteHelpers::activeLang('Date Start', (isset($fields['date_start']['language'])? $fields['date_start']['language'] : array())) }}	
						</td>
						<td>{{ $row->date_start }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date End', (isset($fields['date_end']['language'])? $fields['date_end']['language'] : array())) }}	
						</td>
						<td>{{ $row->date_end }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('User Id', (isset($fields['user_id']['language'])? $fields['user_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->user_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Id', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location Id', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->location_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Price Per Play', (isset($fields['price_per_play']['language'])? $fields['price_per_play']['language'] : array())) }}	
						</td>
						<td>{{ $row->price_per_play }} </td>
						
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
							{{ SiteHelpers::activeLang('Product Cogs 1', (isset($fields['product_cogs_1']['language'])? $fields['product_cogs_1']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_cogs_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Throw 1', (isset($fields['product_throw_1']['language'])? $fields['product_throw_1']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_throw_1 }} </td>
						
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
							{{ SiteHelpers::activeLang('Product Cogs 2', (isset($fields['product_cogs_2']['language'])? $fields['product_cogs_2']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_cogs_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Throw 2', (isset($fields['product_throw_2']['language'])? $fields['product_throw_2']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_throw_2 }} </td>
						
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
							{{ SiteHelpers::activeLang('Product Cogs 3', (isset($fields['product_cogs_3']['language'])? $fields['product_cogs_3']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_cogs_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Throw 3', (isset($fields['product_throw_3']['language'])? $fields['product_throw_3']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_throw_3 }} </td>
						
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
							{{ SiteHelpers::activeLang('Product Cogs 4', (isset($fields['product_cogs_4']['language'])? $fields['product_cogs_4']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_cogs_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Throw 4', (isset($fields['product_throw_4']['language'])? $fields['product_throw_4']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_throw_4 }} </td>
						
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
							{{ SiteHelpers::activeLang('Product Cogs 5', (isset($fields['product_cogs_5']['language'])? $fields['product_cogs_5']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_cogs_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Throw 5', (isset($fields['product_throw_5']['language'])? $fields['product_throw_5']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_throw_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Earnings', (isset($fields['game_earnings']['language'])? $fields['game_earnings']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_earnings }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Throw', (isset($fields['game_throw']['language'])? $fields['game_throw']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_throw }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) }}	
						</td>
						<td>{{ $row->notes }} </td>
						
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