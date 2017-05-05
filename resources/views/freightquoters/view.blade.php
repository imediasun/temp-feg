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
						<td>{{ \DateHelpers::formatZeroValue($row->id) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Company Name', (isset($fields['company_name']['language'])? $fields['company_name']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->company_name) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Rep Name', (isset($fields['rep_name']['language'])? $fields['rep_name']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->rep_name) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Rep Email', (isset($fields['rep_email']['language'])? $fields['rep_email']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->rep_email) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Phone', (isset($fields['phone']['language'])? $fields['phone']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->phone) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('City', (isset($fields['city']['language'])? $fields['city']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->city) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('State', (isset($fields['state']['language'])? $fields['state']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->state) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Zip', (isset($fields['zip']['language'])? $fields['zip']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->zip) }} </td>
						
					</tr>	<tr>
                        <td width='30%' class='label-view text-right'>
                            {{ SiteHelpers::activeLang('Active', (isset($fields['active']['language'])? $fields['active']['language'] : array())) }}
                        </td>
                        <td>  {!!
                            SiteHelpers::gridDisplayView($row->active,'active','1:yes_no:id:yesno')
                            !!} </td>

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
