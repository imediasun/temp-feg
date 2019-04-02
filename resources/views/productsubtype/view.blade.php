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
							{{ SiteHelpers::activeLang('Product Type', (isset($fields['request_type_id']['language'])? $fields['request_type_id']['language'] : array())) }}
						</td>
						<td>{{ \App\Models\Ordertyperestrictions::find($row->request_type_id) ? \App\Models\Ordertyperestrictions::find($row->request_type_id)->order_type : "No Data" }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Product Sub Type', (isset($fields['product_type']['language'])? $fields['product_type']['language'] : array())) }}
						</td>
						<td>{{ $row->product_type }} </td>

					</tr>
				
					{{--<tr>--}}
						{{--<td width='30%' class='label-view text-right'>--}}
							{{--{{ SiteHelpers::activeLang('Type Description', (isset($fields['type_description']['language'])? $fields['type_description']['language'] : array())) }}--}}
						{{--</td>--}}
						{{--<td>{{ $row->type_description }} </td>--}}
						{{----}}
					{{--</tr>--}}
				


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