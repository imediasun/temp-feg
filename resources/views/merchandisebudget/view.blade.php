<div class="sbox">
	<div class="sbox-title">
		<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
			<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')">
			<i class="fa fa fa-times"></i></a>
		</h4>
	 </div>

	<div class="sbox-content">


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
							{{ SiteHelpers::activeLang('Location ', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}
						</td>
						<td>{{ $row->location }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
						January
								</td>
						<td>{{ $row->Jan }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
						February</td>
						<td>{{ $row->Feb }} </td>

					</tr>
                    <tr>
                        <td width='30%' class='label-view text-right'>
                            March</td>
                        <td>{{ $row->March }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            April</td>
                        <td>{{ $row->April }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            May</td>
                        <td>{{ $row->May }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            June</td>
                        <td>{{ $row->June }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            July</td>
                        <td>{{ $row->July }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            August</td>
                        <td>{{ $row->August }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                        September</td>
                        <td>{{ $row->September }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            Octuber</td>
                        <td>{{ $row->Octuber }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            November</td>
                        <td>{{ $row->November }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            December</td>
                        <td>{{ $row->December }} </td>

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