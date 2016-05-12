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
							{{ SiteHelpers::activeLang('Priority Id', (isset($fields['priority_id']['language'])? $fields['priority_id']['language'] : array())) }}
						</td>
						<td>{{ $row->priority_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location Id', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}
						</td>
						<td>{{ $row->location_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Requestor Id', (isset($fields['requestor_id']['language'])? $fields['requestor_id']['language'] : array())) }}
						</td>
						<td>{{ $row->requestor_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Request Date', (isset($fields['request_date']['language'])? $fields['request_date']['language'] : array())) }}
						</td>
						<td>{{ $row->request_date }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Problem', (isset($fields['problem']['language'])? $fields['problem']['language'] : array())) }}
						</td>
						<td>{{ $row->problem }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Need By Date', (isset($fields['need_by_date']['language'])? $fields['need_by_date']['language'] : array())) }}
						</td>
						<td>{{ $row->need_by_date }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Solved Date', (isset($fields['solved_date']['language'])? $fields['solved_date']['language'] : array())) }}
						</td>
						<td>{{ $row->solved_date }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Solver Id', (isset($fields['solver_id']['language'])? $fields['solver_id']['language'] : array())) }}
						</td>
						<td>{{ $row->solver_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Solution', (isset($fields['solution']['language'])? $fields['solution']['language'] : array())) }}
						</td>
						<td>{{ $row->solution }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Status Id', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) }}
						</td>
						<td>{{ $row->status_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Request Title', (isset($fields['request_title']['language'])? $fields['request_title']['language'] : array())) }}
						</td>
						<td>{{ $row->request_title }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Attachment Path', (isset($fields['attachment_path']['language'])? $fields['attachment_path']['language'] : array())) }}
						</td>
						<td>{{ $row->attachment_path }} </td>
						
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