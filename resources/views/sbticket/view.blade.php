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
							{{ SiteHelpers::activeLang('Last Event date', (isset($fields['updated']['language'])? $fields['updated']['language'] : array())) }}	
						</td>
						<td>{{ $row->updated }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Title', (isset($fields['Subject']['language'])? $fields['Subject']['language'] : array())) }}	
						</td>
						<td>{{ $row->Subject }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->location_id,'location_id','1:location:id:location_name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Asset Id', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->game_id,'game_id','1:game:id:game_name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Assign To', (isset($fields['assign_to']['language'])? $fields['assign_to']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->assign_to,'assign_to','1:employees:id:first_name|last_name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Priority', (isset($fields['Priority']['language'])? $fields['Priority']['language'] : array())) }}	
						</td>
						<td>{{ $row->Priority }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Status', (isset($fields['Status']['language'])? $fields['Status']['language'] : array())) }}	
						</td>
						<td>{{ $row->Status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Description', (isset($fields['Description']['language'])? $fields['Description']['language'] : array())) }}	
						</td>
						<td>{{ $row->Description }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Debit Card', (isset($fields['debit_card']['language'])? $fields['debit_card']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->debit_card,'debit_card','1:debit_type:company:company') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Issue Type', (isset($fields['issue_type']['language'])? $fields['issue_type']['language'] : array())) }}	
						</td>
						<td>{{ $row->issue_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Department', (isset($fields['department_id']['language'])? $fields['department_id']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->department_id,'department_id','1:departments:id:name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Close', (isset($fields['closed']['language'])? $fields['closed']['language'] : array())) }}	
						</td>
						<td>{{ $row->closed }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('File Path', (isset($fields['file_path']['language'])? $fields['file_path']['language'] : array())) }}	
						</td>
						<td>{{ $row->file_path }} </td>
						
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