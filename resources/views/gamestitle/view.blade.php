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
							Manual
						</td>
						<td> @if($row->has_manual=="Yes")<a href="uploads/games/manuals/{{ $row->id }}.pdf"  target="_blank">Manual</a>@else <a href="{{ URL::to('gamestitle/upload/'.$row->id.'?type=2')}}">Upload manual</a>@endif</td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
                            Servicebulletin
						</td>
						<td> @if($row->has_servicebulletin=="Yes")  <a href="uploads/games/bulletins/{{ $row->id }}.pdf"  target="_blank">Bulletin</a>@else  <a href="{{ URL::to('gamestitle/upload/'.$row->id.'?type=3')}}">Upload Bulletin</a>@endif</td>

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