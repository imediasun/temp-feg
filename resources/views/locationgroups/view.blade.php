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
					<th>ID</th>
					<th>Location Name</th>
				</tr>
			</thead>
			<tbody>
				@foreach($locations as $location)
					<tr>
						<td>{{$location->id}}</td>
						<td>{{$location->location_name}}</td>
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