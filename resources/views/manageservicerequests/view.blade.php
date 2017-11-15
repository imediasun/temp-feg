<style>

	#description_td {
		white-space: pre-wrap !important;
	}
</style>
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

		<div class="table-responsive">
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
							{{ SiteHelpers::activeLang('Priority ', (isset($fields['priority_id']['language'])? $fields['priority_id']['language'] : array())) }}
						</td>
                        <td>{!! SiteHelpers::gridDisplayView($row->priority_id,'priority_id','1:new_graphics_priority:id:id_plus',$nodata['priority_id']) !!} </td>


                    </tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location ', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}
						</td>
                        <td>{{DateHelpers::formatMultiValues($row->location_id,$row->location_name) }} </td>


                    </tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Requested By ', (isset($fields['requestor_id']['language'])? $fields['requestor_id']['language'] : array())) }}
						</td>
                        <td>{!! SiteHelpers::gridDisplayView($row->requestor_id,'requestor_id','1:users:id:username',$nodata['requestor_id']) !!} </td>


                    </tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Request Date', (isset($fields['request_date']['language'])? $fields['request_date']['language'] : array())) }}	
						</td>
						<td>{{  \DateHelpers::formatDate($row->request_date)   }}</td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Description', (isset($fields['problem']['language'])? $fields['problem']['language'] : array())) }}
						</td>
						<td id="description_td">{{ DateHelpers::formatStringValue($row->problem,$nodata['problem']) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Needed By ', (isset($fields['need_by_date']['language'])? $fields['need_by_date']['language'] : array())) }}
						</td>
						<td>{{  DateHelpers::formatDate($row->need_by_date)   }}</td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Resolved', (isset($fields['solved_date']['language'])? $fields['solved_date']['language'] : array())) }}
						</td>
						<td>{{  DateHelpers::formatDate($row->solved_date)  }}</td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Resolved By ', (isset($fields['solver_id']['language'])? $fields['solver_id']['language'] : array())) }}
						</td>
                        <td>{!! SiteHelpers::gridDisplayView($row->solver_id,'solver_id','1:users:id:username',$nodata['solver_id']) !!} </td>


                    </tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Solution', (isset($fields['solution']['language'])? $fields['solution']['language'] : array())) }}	
						</td>
						<td>{{ DateHelpers::formatStringValue($row->solution,$nodata['solution']) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Status ', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) }}
						</td>
                        <td>{!! SiteHelpers::gridDisplayView($row->status_id,'status_id','1:service_status:id:status',$nodata['status_id']) !!} </td>


                    </tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Title', (isset($fields['request_title']['language'])? $fields['request_title']['language'] : array())) }}
						</td>
						<td>{{ DateHelpers::formatStringValue($row->request_title,$nodata['request_title']) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Attachments', (isset($fields['attachment_path']['language'])? $fields['attachment_path']['language'] : array())) }}
						</td>
						<td>{{ DateHelpers::formatStringValue($row->attachment_path,$nodata['attachment_path']) }} </td>
						
					</tr>
				
			</tbody>	
		</table>  
			</div>
		 	

@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

<script>
$(document).ready(function(){

});
</script>

