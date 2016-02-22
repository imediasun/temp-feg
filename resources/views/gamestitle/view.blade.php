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
							{{ SiteHelpers::activeLang('Game Title', (isset($fields['game_title']['language'])? $fields['game_title']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_title }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Mfg Id', (isset($fields['mfg_id']['language'])? $fields['mfg_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->mfg_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Type Id', (isset($fields['game_type_id']['language'])? $fields['game_type_id']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->game_type_id,'game_type_id','1:game_type:id:game_type_short') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Has Manual', (isset($fields['has_manual']['language'])? $fields['has_manual']['language'] : array())) }}	
						</td>
						<td>{{ $row->has_manual }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Has Servicebulletin', (isset($fields['has_servicebulletin']['language'])? $fields['has_servicebulletin']['language'] : array())) }}	
						</td>
						<td>{{ $row->has_servicebulletin }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Num Prize Meters', (isset($fields['num_prize_meters']['language'])? $fields['num_prize_meters']['language'] : array())) }}	
						</td>
						<td>{{ $row->num_prize_meters }} </td>
						
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