<div class="sbox">
	<div class="sbox-title">
		<h4> <i class="fa fa-eye"></i> {{ $row->location_name }} <small><?php echo \Session::get('budget_year') ?></small>
			<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')">
			<i class="fa fa fa-times"></i></a>
		</h4>
	 </div>

	<div class="sbox-content">

        <div class="table-responsive">

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
						<td>{{ \CurrencyHelpers::formatPrice($row->Jan) }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
						February</td>
						<td>{{ \CurrencyHelpers::formatPrice($row->Feb) }} </td>

					</tr>
                    <tr>
                        <td width='30%' class='label-view text-right'>
                            March</td>
                        <td>{{ \CurrencyHelpers::formatPrice($row->March) }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            April</td>
                        <td>{{ \CurrencyHelpers::formatPrice($row->April) }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            May</td>
                        <td>{{ \CurrencyHelpers::formatPrice($row->May )}} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            June</td>
                        <td>{{ \CurrencyHelpers::formatPrice($row->June )}} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            July</td>
                        <td>{{ \CurrencyHelpers::formatPrice($row->July) }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            August</td>
                        <td>{{ \CurrencyHelpers::formatPrice($row->August) }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                        September</td>
                        <td>{{ \CurrencyHelpers::formatPrice($row->September )}} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            October</td>
                        <td>{{ \CurrencyHelpers::formatPrice($row->October )}} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            November</td>
                        <td>{{ \CurrencyHelpers::formatPrice($row->November) }} </td>

                    </tr><tr>
                        <td width='30%' class='label-view text-right'>
                            December</td>
                        <td>{{ \CurrencyHelpers::formatPrice($row->December) }} </td>

                    </tr>

			</tbody>
		</table>
</div>


@if($setting['form-method'] =='native')
	</div>
</div>
@endif

<script>
$(document).ready(function(){

});
</script>
