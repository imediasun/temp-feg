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
							{{ SiteHelpers::activeLang('Description', (isset($fields['description']['language'])? $fields['description']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->description) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location', (isset($fields['loc_id']['language'])? $fields['loc_id']['language'] : array())) }}
						</td>
                        <td>{!! SiteHelpers::gridDisplayView($row->loc_id,'loc_id','1:location:id:location_name',$nodata['loc_id'])!!}
                        </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('For Game', (isset($fields['game_title_id']['language'])? $fields['game_title_id']['language'] : array())) }}
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->game_title_id,'game_title_id','1:game_title:id:game_title',$nodata['game_title_id'])!!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Qty', (isset($fields['qty']['language'])? $fields['qty']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatZeroValue($row->qty,$nodata['qty']) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Approx. Value', (isset($fields['value']['language'])? $fields['value']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatZeroValue($row->value) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Submitted By', (isset($fields['user']['language'])? $fields['user']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->user) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Status', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) }}
						</td>
                        <td>{!! SiteHelpers::gridDisplayView($row->status_id,'status_id','1:spare_status:id:status',$nodata['status_id'])!!}
                        </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('User Claim', (isset($fields['user_claim']['language'])? $fields['user_claim']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->user_claim) }} </td>
						
					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Claimed By User', (isset($fields['claimed_by']['language'])? $fields['claimed_by']['language'] : array())) }}
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->claimed_by,'claimed_by','1:users:id:first_name | last_name',$nodata['claimed_by'])!!}</td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Claimed Location', (isset($fields['claimed_location_id']['language'])? $fields['claimed_location_id']['language'] : array())) }}
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->claimed_location_id,'claimed_location_id','1:location:id:location_name',$nodata['claimed_location_id'])!!} </td>

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
