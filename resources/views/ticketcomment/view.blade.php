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
							{{ SiteHelpers::activeLang('CommentID', (isset($fields['CommentID']['language'])? $fields['CommentID']['language'] : array())) }}	
						</td>
						<td>{{ $row->CommentID }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('TicketID', (isset($fields['TicketID']['language'])? $fields['TicketID']['language'] : array())) }}	
						</td>
						<td>{{ $row->TicketID }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Comments', (isset($fields['Comments']['language'])? $fields['Comments']['language'] : array())) }}	
						</td>
						<td>{{ $row->Comments }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Posted', (isset($fields['Posted']['language'])? $fields['Posted']['language'] : array())) }}	
						</td>
						<td>{{ $row->Posted }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('UserID', (isset($fields['UserID']['language'])? $fields['UserID']['language'] : array())) }}	
						</td>
						<td>{{ $row->UserID }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Attachments', (isset($fields['Attachments']['language'])? $fields['Attachments']['language'] : array())) }}	
						</td>
						<td>{{ $row->Attachments }} </td>
						
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