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
							{{ SiteHelpers::activeLang('Name', (isset($fields['name']['language'])? $fields['name']['language'] : array())) }}	
						</td>
						<td>{{ $row->name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Responsible Agents Count', (isset($fields['assign_employee_ids']['language'])? $fields['assign_employee_ids']['language'] : array())) }}
						</td>
						<?php
						$count = count(explode(',',$row->assign_employee_ids));
						?>
						<td>{{ $count }} </td>
						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ 'Open Tickets Count' }}
						</td>
						<td>{{ $row->open_tickets }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ 'Pending Tickets Count' }}
						</td>
						<td>{{ $row->pending_tickets }} </td>

					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ 'Close Tickets Count' }}
						</td>
						<td>{{ $row->close_tickets }} </td>

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