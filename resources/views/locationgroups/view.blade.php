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
							{{ SiteHelpers::activeLang('Name', (isset($fields['name']['language'])? $fields['name']['language'] : array())) }}
						</td>
						<td>{{ $row->name }} </td>
						
					</tr>
				
					{{--<tr>--}}
						{{--<td width='30%' class='label-view text-right'>--}}
							{{--{{ SiteHelpers::activeLang('Created At', (isset($fields['created_at']['language'])? $fields['created_at']['language'] : array())) }}--}}
						{{--</td>--}}
						{{--<td>{{ $row->created_at }} </td>--}}
						{{----}}
					{{--</tr>--}}
				{{----}}
					{{--<tr>--}}
						{{--<td width='30%' class='label-view text-right'>--}}
							{{--{{ SiteHelpers::activeLang('Updated At', (isset($fields['updated_at']['language'])? $fields['updated_at']['language'] : array())) }}--}}
						{{--</td>--}}
						{{--<td>{{ $row->updated_at }} </td>--}}
						{{----}}
					{{--</tr>--}}
				
			</tbody>	
		</table>

		<br>
		<br>
		<h3>Associated Locations</h3>
		<table class="table table-striped table-bordered" >
			@if(count($locations) == 0)
				<p style="vertical-align: middle; text-align: center !important; font-size: larger">No locations associated</p>
			@else
			<thead>
				<tr>
					<th colspan="1">ID</th>
					<th colspan="11">Location Name</th>
				</tr>
			</thead>
			<tbody>
				@foreach($locations as $location)
					<tr>
						<td colspan="1">{{$location->id}}</td>
						<td colspan="11">{{$location->location_name}}</td>
					</tr>
				@endforeach
			</tbody>
			@endif
		</table>


		<br>
		<br>
		<h3>Excluded Product Types</h3>
		<table class="table table-striped table-bordered" >
			@if(count($excludedProductTypes) == 0)
				<p style="vertical-align: middle; text-align: center !important; font-size: larger">No Product Type excluded</p>
			@else
			<thead>
				<tr>
					<th colspan="1">ID</th>
					<th colspan="11">Excluded Product Type</th>
				</tr>
			</thead>
			<tbody>
				@foreach($excludedProductTypes as $excludedProductType)
					<tr>
						<td colspan="1">{{$excludedProductType->id}}</td>
						<td colspan="11">{{$excludedProductType->order_type}}</td>
					</tr>
				@endforeach
			</tbody>
			@endif
		</table>

		<br>
		<br>
		<h3>Excluded Products</h3>
		<table class="table table-striped table-bordered" >
			@if(count($excludedProducts) == 0)
				<p style="vertical-align: middle; text-align: center !important; font-size: larger">No Products excluded</p>
			@else
			<thead>
				<tr>
					<th colspan="1">ID</th>
					<th colspan="11">Excluded Product</th>
				</tr>
			</thead>
			<tbody>
				@foreach($excludedProducts as $excludedProduct)
					<tr>
						<td colspan="1">{{$excludedProduct->id}}</td>
						<td colspan="11">{{$excludedProduct->vendor_description}}</td>
					</tr>
				@endforeach
			</tbody>
			@endif
		</table>

		 	

@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

<script>
$(document).ready(function(){

});
</script>	