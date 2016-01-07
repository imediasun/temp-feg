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
							{{ SiteHelpers::activeLang('Subject', (isset($fields['Subject']['language'])? $fields['Subject']['language'] : array())) }}	
						</td>
						<td>{{ $row->Subject }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Description', (isset($fields['Description']['language'])? $fields['Description']['language'] : array())) }}	
						</td>
						<td>{{ $row->Description }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Priority', (isset($fields['Priority']['language'])? $fields['Priority']['language'] : array())) }}	
						</td>
						<td>{{ $row->Priority }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date', (isset($fields['Created']['language'])? $fields['Created']['language'] : array())) }}	
						</td>
						<td>{{ $row->Created }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Status', (isset($fields['Status']['language'])? $fields['Status']['language'] : array())) }}	
						</td>
						<td>{{ $row->Status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Issue Type', (isset($fields['issue_type']['language'])? $fields['issue_type']['language'] : array())) }}	
						</td>
						<td>{{ $row->issue_type }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location Id', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->location_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Department Id', (isset($fields['department_id']['language'])? $fields['department_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->department_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Closed', (isset($fields['closed']['language'])? $fields['closed']['language'] : array())) }}	
						</td>
						<td>{{ $row->closed }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Assign To', (isset($fields['assign_to']['language'])? $fields['assign_to']['language'] : array())) }}	
						</td>
						<td>{{ $row->assign_to }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('File Path', (isset($fields['file_path']['language'])? $fields['file_path']['language'] : array())) }}	
						</td>
						<td>{{ $row->file_path }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Game Id', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) }}	
						</td>
						<td>{{ $row->game_id }} </td>
						
					</tr>
				
			</tbody>	
		</table>  
			
		 	
	@if($subgrid['access']['is_detail'] == '1')	
		<hr />	
		<h5> get games on location </h5>
	
		<div class="table-responsive">
	    <table class="table table-striped ">
	        <thead>
				<tr>
					<th class="number"> No </th>
						@foreach ($subgrid['tableGrid'] as $t)
						@if($t['view'] =='1')
							<th>
								{{ SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) }}
							</th>
						@endif
					@endforeach
					
				  </tr>
	        </thead>

	        <tbody>
	            @foreach ($subgrid['rowData'] as $row)
	            <tr>
					<td width="30">  </td>		
				 @foreach ($subgrid['tableGrid'] as $field)
					 @if($field['view'] =='1' )
					 <td>					 
					 	@if($field['attribute']['image']['active'] =='1')
							{!! SiteHelpers::showUploadedFile($row->$field['field'],$field['attribute']['image']['path']) !!}
						@else	
							{{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array() ) /*--}}
							{!! SiteHelpers::gridDisplay($row->$field['field'],$field['field'],$conn) !!}	
						@endif						 
					 </td>
					 @endif					 
				 
				 @endforeach
				@endforeach
				</tr> 


	        </tbody>	

	     </table>   
	     </div>
	@endif
     	

@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

<script>
$(document).ready(function(){

});
</script>	