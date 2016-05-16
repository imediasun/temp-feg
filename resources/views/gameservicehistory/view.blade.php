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
							{{ SiteHelpers::activeLang('Game Id', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) }}
						</td>
						<td>{{ $row->game_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Down', (isset($fields['date_down']['language'])? $fields['date_down']['language'] : array())) }}
						</td>
						<td>{{ $row->date_down }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Problem', (isset($fields['problem']['language'])? $fields['problem']['language'] : array())) }}
						</td>
						<td>{{ $row->problem }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Down User Id', (isset($fields['down_user_id']['language'])? $fields['down_user_id']['language'] : array())) }}
						</td>
						<td>{{ $row->down_user_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Solution', (isset($fields['solution']['language'])? $fields['solution']['language'] : array())) }}
						</td>
						<td>{{ $row->solution }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Up', (isset($fields['date_up']['language'])? $fields['date_up']['language'] : array())) }}
						</td>
						<td>{{ $row->date_up }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Up User Id', (isset($fields['up_user_id']['language'])? $fields['up_user_id']['language'] : array())) }}
						</td>
						<td>{{ $row->up_user_id }} </td>
						
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