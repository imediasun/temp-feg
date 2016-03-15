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
							{{ SiteHelpers::activeLang('InvoiceID', (isset($fields['InvoiceID']['language'])? $fields['InvoiceID']['language'] : array())) }}	
						</td>
						<td>{{ $row->InvoiceID }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Number', (isset($fields['Number']['language'])? $fields['Number']['language'] : array())) }}	
						</td>
						<td>{{ $row->Number }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('ClientID', (isset($fields['UserID']['language'])? $fields['UserID']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->UserID,'UserID','1:users:id:first_name|last_name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('DateIssued', (isset($fields['DateIssued']['language'])? $fields['DateIssued']['language'] : array())) }}	
						</td>
						<td>{{ $row->DateIssued }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('DueDate', (isset($fields['DueDate']['language'])? $fields['DueDate']['language'] : array())) }}	
						</td>
						<td>{{ $row->DueDate }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Amount', (isset($fields['Amount']['language'])? $fields['Amount']['language'] : array())) }}	
						</td>
						<td>{{ $row->Amount }} </td>
						
					</tr>
				
			</tbody>	
		</table>  
			
		 	
	@if($subgrid['access']['is_detail'] == '1')	
		<hr />	
		<h5> Item Details </h5>
	
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