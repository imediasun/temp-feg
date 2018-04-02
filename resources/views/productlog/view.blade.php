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
							{{ SiteHelpers::activeLang('Item Name', (isset($fields['vendor_description']['language'])? $fields['vendor_description']['language'] : array())) }}
						</td>
						<td>{{ $row->vendor_description }} </td>
						
					</tr>
				


					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Is Reserved', (isset($fields['is_reserved']['language'])? $fields['is_reserved']['language'] : array())) }}
						</td>
						<td>{!! $row->is_reserved == 0 ? 'No':'Yes' !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Reserved Qty', (isset($fields['reserved_qty']['language'])? $fields['reserved_qty']['language'] : array())) }}
						</td>
						<td>{{ $row->reserved_qty }} </td>
						
					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Inactive', (isset($fields['inactive']['language'])? $fields['inactive']['language'] : array())) }}
						</td>
						<td>{!! $row->inactive == 0 ? 'No':'Yes' !!} </td>
						
					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Updated At', (isset($fields['updated_at']['language'])? $fields['updated_at']['language'] : array())) }}
						</td>
						<td>{{ $row->updated_at }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Allow Negative Reserved Qty', (isset($fields['allow_negative_reserve_qty']['language'])? $fields['allow_negative_reserve_qty']['language'] : array())) }}
						</td>
						<td>{!! $row->allow_negative_reserve_qty == 0 ? 'No':'Yes' !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Reserved Qty Per Amount', (isset($fields['reserved_qty_limit']['language'])? $fields['reserved_qty_limit']['language'] : array())) }}
						</td>
						<td>{{ !empty($row->reserved_qty_limit) ? $row->reserved_qty_limit :"No Data" }} </td>
						
					</tr>

				
			</tbody>	
		</table>
@if(!empty($ProductLogContent))
		<br /><br />
		<?php
			$reducedByOrder = $ProductLogContent['reducedByOrder'];
			$addedFromProductList  = $ProductLogContent['addedFromProductList'];
			?>
		<table class="table table-striped table-bordered" >
			<tbody>
				<tr>
					<th colspan="6"><h4>Reserved Quantity Added/Reduced from Product List</h4></th>
				</tr>
				<tr>
					<th>Item ID</th>
					<th>Added Amount</th>
					<th>Added/Reduced</th>
					<th>Reason</th>
					<th>Added By</th>
					<th>Added At</th>
				</tr>
				@if(count($addedFromProductList)>0)
@foreach($addedFromProductList as $logContent)
			<tr>
				<td>{{ $logContent->product_id }}</td>
				<td>{{ ($logContent->adjustment_amount > 0 ? $logContent->adjustment_amount:($logContent->adjustment_amount * -1)) }}</td>
				<td>{{ !empty($logContent->adjustment_type=='negative') ? 'Reduced':'Added' }}</td>
				<td>{{ !empty($logContent->reserved_qty_reason) ? $logContent->reserved_qty_reason:"No Data" }}</td>
				<td>{{ $logContent->adjusted_by }}</td>
				<td>{{ $logContent->created_at }}</td>
			</tr>
	@endforeach
					@else
			<tr>
				<td colspan="6" align="center">No existing record found.</td>
			</tr>
			@endif
			</tbody>
		</table>
	<br /> <br />
			<table class="table table-striped table-bordered" >
				<tbody>
				<tr>
					<th colspan="6"><h4>Reserved Quantity Added/Reduced from Orders</h4></th>
				</tr>
				<tr>
					<th>Item ID</th>
					<th>Order ID</th>
					<th>Used Amount</th>
					<th>Added/Reduced</th>
					<th>Used By</th>
					<th>Used At</th>
				</tr>
				@if(count($reducedByOrder) > 0)
				@foreach($reducedByOrder as $logContent)
					<tr>
						<td>{{ $logContent->product_id }}</td>
						<td>{{ $logContent->order_id }}</td>
						<td>{{ ($logContent->adjustment_amount > 0 ? $logContent->adjustment_amount:($logContent->adjustment_amount * -1)) }}</td>
						<td>{{ !empty($logContent->adjustment_type=='negative') ? 'Reduced':'Added' }}</td>
						<td>{{ $logContent->adjusted_by }}</td>
						<td>{{ $logContent->created_at }}</td>
					</tr>
				@endforeach
				@else
					<tr>
						<td colspan="6" align="center">No existing record found.</td>
					</tr>
				@endif
				</tbody>
			</table>
@endif
@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

<script>
$(document).ready(function(){

});
</script>	