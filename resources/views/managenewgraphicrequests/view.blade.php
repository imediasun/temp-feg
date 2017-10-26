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
							{{ SiteHelpers::activeLang('Location', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}
						</td>
                        <td>{{ (!empty($row->location_id))?$row->location_id ." | ":"" }}{!! SiteHelpers::gridDisplayView($row->location_id,'location_id','1:location:id:location_name',$nodata['location_id']) !!} </td>


                    </tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Request User ', (isset($fields['request_user_id']['language'])? $fields['request_user_id']['language'] : array())) }}
						</td>
                        <td>{!! SiteHelpers::gridDisplayView($row->request_user_id,'request_user_id','1:users:id:username',$nodata['request_user_id']) !!} </td>


                    </tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Request Date', (isset($fields['request_date']['language'])? $fields['request_date']['language'] : array())) }}	
						</td>
						<td>{{  \DateHelpers::formatDate($row->request_date) }}</td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Needed By', (isset($fields['need_by_date']['language'])? $fields['need_by_date']['language'] : array())) }}
						</td>
						<td>{{  \DateHelpers::formatDate($row->need_by_date) }}</td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Description', (isset($fields['description']['language'])? $fields['description']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->description,$nodata['description']) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Qty', (isset($fields['qty']['language'])? $fields['qty']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatZeroValue($row->qty,$nodata['qty']) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Status ', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) }}
						</td>
                        <td>{!! SiteHelpers::gridDisplayView($row->status_id,'status_id','1:new_graphics_request_status:id:status',$nodata['status_id']) !!} </td>


                    </tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Priority ', (isset($fields['priority_id']['language'])? $fields['priority_id']['language'] : array())) }}
						</td>
                        <td>{!! SiteHelpers::gridDisplayView($row->priority_id,'priority_id','1:new_graphics_priority:id:id_plus',$nodata['priority_id']) !!} </td>


                    </tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Media Type', (isset($fields['media_type']['language'])? $fields['media_type']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->media_type,$nodata['media_type']) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) }}	
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->notes,$nodata['notes']) }} </td>
						
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
