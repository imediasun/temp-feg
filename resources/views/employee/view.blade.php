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
							{{ SiteHelpers::activeLang('EmployeeNumber', (isset($fields['employeeNumber']['language'])? $fields['employeeNumber']['language'] : array())) }}	
						</td>
						<td>{{ $row->employeeNumber }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('LastName', (isset($fields['lastName']['language'])? $fields['lastName']['language'] : array())) }}	
						</td>
						<td>{{ $row->lastName }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('FirstName', (isset($fields['firstName']['language'])? $fields['firstName']['language'] : array())) }}	
						</td>
						<td>{{ $row->firstName }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Extension', (isset($fields['extension']['language'])? $fields['extension']['language'] : array())) }}	
						</td>
						<td>{{ $row->extension }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Email', (isset($fields['email']['language'])? $fields['email']['language'] : array())) }}	
						</td>
						<td>{{ $row->email }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('ReportsTo', (isset($fields['reportsTo']['language'])? $fields['reportsTo']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->reportsTo,'reportsTo','1:employees:employeeNumber:firstName|lastName') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('JobTitle', (isset($fields['jobTitle']['language'])? $fields['jobTitle']['language'] : array())) }}	
						</td>
						<td>{{ $row->jobTitle }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Foto', (isset($fields['foto']['language'])? $fields['foto']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::showUploadedFile($row->foto,'/uploads/images/') !!} </td>
						
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