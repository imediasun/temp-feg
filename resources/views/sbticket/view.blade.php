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