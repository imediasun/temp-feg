@if($setting['view-method'] =='native')
<div class="sbox">
	<div class="sbox-title">  
		<h4> <i class="fa fa-eye"></i> <?php echo $pageTitle ;?>
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
							{{ SiteHelpers::activeLang('Debit Type Id', (isset($fields['debit_type_id']['language'])? $fields['debit_type_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->debit_type_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc Id', (isset($fields['loc_id']['language'])? $fields['loc_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->loc_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Id', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Reader Id', (isset($fields['reader_id']['language'])? $fields['reader_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->reader_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Play Value', (isset($fields['play_value']['language'])? $fields['play_value']['language'] : array())) }}	
						</td>
						<td>{{ $row->play_value }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Total Notional Value', (isset($fields['total_notional_value']['language'])? $fields['total_notional_value']['language'] : array())) }}	
						</td>
						<td>{{ $row->total_notional_value }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Std Plays', (isset($fields['std_plays']['language'])? $fields['std_plays']['language'] : array())) }}	
						</td>
						<td>{{ $row->std_plays }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Std Card Credit', (isset($fields['std_card_credit']['language'])? $fields['std_card_credit']['language'] : array())) }}	
						</td>
						<td>{{ $row->std_card_credit }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Std Card Credit Bonus', (isset($fields['std_card_credit_bonus']['language'])? $fields['std_card_credit_bonus']['language'] : array())) }}	
						</td>
						<td>{{ $row->std_card_credit_bonus }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Std Actual Cash', (isset($fields['std_actual_cash']['language'])? $fields['std_actual_cash']['language'] : array())) }}	
						</td>
						<td>{{ $row->std_actual_cash }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Std Card Dollar', (isset($fields['std_card_dollar']['language'])? $fields['std_card_dollar']['language'] : array())) }}	
						</td>
						<td>{{ $row->std_card_dollar }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Std Card Dollar Bonus', (isset($fields['std_card_dollar_bonus']['language'])? $fields['std_card_dollar_bonus']['language'] : array())) }}	
						</td>
						<td>{{ $row->std_card_dollar_bonus }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Time Plays', (isset($fields['time_plays']['language'])? $fields['time_plays']['language'] : array())) }}	
						</td>
						<td>{{ $row->time_plays }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Time Play Dollar', (isset($fields['time_play_dollar']['language'])? $fields['time_play_dollar']['language'] : array())) }}	
						</td>
						<td>{{ $row->time_play_dollar }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Time Play Dollar Bonus', (isset($fields['time_play_dollar_bonus']['language'])? $fields['time_play_dollar_bonus']['language'] : array())) }}	
						</td>
						<td>{{ $row->time_play_dollar_bonus }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Plays', (isset($fields['product_plays']['language'])? $fields['product_plays']['language'] : array())) }}	
						</td>
						<td>{{ $row->product_plays }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Service Plays', (isset($fields['service_plays']['language'])? $fields['service_plays']['language'] : array())) }}	
						</td>
						<td>{{ $row->service_plays }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Courtesy Plays', (isset($fields['courtesy_plays']['language'])? $fields['courtesy_plays']['language'] : array())) }}	
						</td>
						<td>{{ $row->courtesy_plays }} </td>
						
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
							{{ SiteHelpers::activeLang('Ticket Payout', (isset($fields['ticket_payout']['language'])? $fields['ticket_payout']['language'] : array())) }}	
						</td>
						<td>{{ $row->ticket_payout }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])? $fields['ticket_value']['language'] : array())) }}	
						</td>
						<td>{{ $row->ticket_value }} </td>
						
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
