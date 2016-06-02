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
							{{ SiteHelpers::activeLang('Theme Name', (isset($fields['theme_name']['language'])? $fields['theme_name']['language'] : array())) }}
						</td>
						<td>{{ $row->theme_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc Id', (isset($fields['loc_id']['language'])? $fields['loc_id']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Users', (isset($fields['users']['language'])? $fields['users']['language'] : array())) }}
						</td>
						<td>{{ $row->users }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date', (isset($fields['date']['language'])? $fields['date']['language'] : array())) }}
						</td>
						<td>{{ $row->date }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Image Category', (isset($fields['image_category']['language'])? $fields['image_category']['language'] : array())) }}
						</td>
						<td>{{ $row->image_category }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Type', (isset($fields['type']['language'])? $fields['type']['language'] : array())) }}
						</td>
						<td>{{ $row->type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Video Path', (isset($fields['video_path']['language'])? $fields['video_path']['language'] : array())) }}
						</td>
						<td>{{ $row->video_path }} </td>
						
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