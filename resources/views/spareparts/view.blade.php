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
		{!! Form::open(array('url'=>'spareparts/save/'.$row->id,
                                'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>
                                'sparepartsFormAjax')) !!}

		<div class="hidden_inputs">
			<input type="hidden" name="description" value="{{$row->description}}">
			<input type="hidden" name="value" value="{{$row->value}}">
			<input type="hidden" name="qty" value="{{$row->qty}}">
			<input type="hidden" name="user" value="{{$row->user}}">
			<input type="hidden" name="game_title_id" value="{{$row->game_title_id}}">
			<input type="hidden" name="loc_id" value="{{$row->loc_id}}">
			<input type="hidden" name="user_claim" value="{{$row->user_claim}}">
			<input type="hidden" id="is_edit" value="@if(!empty($row->id)) 1 @else 0 @endif">
		</div>
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
							{{ SiteHelpers::activeLang('Description', (isset($fields['description']['language'])? $fields['description']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->description) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location', (isset($fields['loc_id']['language'])? $fields['loc_id']['language'] : array())) }}
						</td>
                        <td>{!! SiteHelpers::gridDisplayView($row->loc_id,'loc_id','1:location:id:location_name',$nodata['loc_id'])!!}
                        </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('For Game', (isset($fields['game_title_id']['language'])? $fields['game_title_id']['language'] : array())) }}
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->game_title_id,'game_title_id','1:game_title:id:game_title',$nodata['game_title_id'])!!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Qty', (isset($fields['qty']['language'])? $fields['qty']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatZeroValue($row->qty,$nodata['qty']) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Approx. Value', (isset($fields['value']['language'])? $fields['value']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatZeroValue($row->value) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Submitted By', (isset($fields['user']['language'])? $fields['user']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->user) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Status', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) }}
						</td>
						<td><div class="label_fields col-md-3" style="padding : 0">{!! SiteHelpers::gridDisplayView($row->status_id,'status_id','1:spare_status:id:status',$nodata['status_id'])!!}</div>
							<div class="claim_fields col-md-5" style="padding : 0">
								<select name="status_id" id="status_id" class="select4" required />
							</div>

							<div class="col-sm-2 text-center" style="padding : 0">
								<button type="submit" id="save_status" class="btn btn-primary claim_fields"><i
											class="fa  fa-save "></i></button>
								<button type="button" id="change_status" class="btn btn-success">
									<i class="fa  fa-pencil "></i></button>
							</div>

                        </td>
						
					</tr>
                    <tr>
                        <td width='30%' class='label-view text-right'>
                            {{ SiteHelpers::activeLang('Claimed Location', (isset($fields['claimed_location_id']['language'])? $fields['claimed_location_id']['language'] : array())) }}
                        </td>
                        <td>
                            <div class="label_fields col-md-4" style="padding : 0">{!! SiteHelpers::gridDisplayView($row->claimed_location_id,'claimed_location_id','1:location:id:location_name',$nodata['claimed_location_id'])!!}</div>
                            <div class="claim_fields col-md-5" style="padding : 0">
                                <select name="claimed_location_id" id="claimed_location_id" class="select4" />
                            </div>
                        </td>

                    </tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Claim Reason', (isset($fields['user_claim']['language'])? $fields['user_claim']['language'] : array())) }}
						</td>
						<td>{{ \DateHelpers::formatStringValue($row->user_claim) }} </td>
						
					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Claimed By', (isset($fields['claimed_by']['language'])? $fields['claimed_by']['language'] : array())) }}
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->claimed_by,'claimed_by','1:users:id:first_name|last_name',$nodata['claimed_by'])!!}</td>

					</tr>


				
			</tbody>	
		</table>

			{!! Form::close() !!}

@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

<script>
	$(document).ready(function(){
        $("#status_id").jCombo("{{ URL::to('spareparts/comboselect?filter=spare_status:id:status') }}",
            {selected_value: '{{ $row->status_id }}', initial_text: 'Select Status'});
        $("#claimed_location_id").jCombo("{{ URL::to('spareparts/comboselect?filter=location:id:location_name') }}",
            {selected_value: '{{ $row->claimed_location_id }}', initial_text: 'Select Location'});


		$('.claim_fields').hide();
		$('.label_fields').show();

        var form = $('#sparepartsFormAjax');
        form.parsley();

        $('#change_status').on('click',function () {
            $('.label_fields').toggle(300);
            $('.claim_fields').toggle(300);
        });
        $('#save_status').on('click',function () {
            if($('#status_id').val() == {{App\Models\spareparts::$CLAIMED}})
            {
                form.parsley().destroy();
                $('#claimed_location_id').attr('required','required');
                form.parsley();
            }
            else
            {
                form.parsley().destroy();
                $('#claimed_location_id').removeAttr('required');
                form.parsley();
            }
            if (form.parsley('isValid') == true) {
                $('.label_fields').show(300);
                $('.claim_fields').hide(300);
            }

        });
        renderDropdown($(".select4 "), { width:"100%"});
        form.submit(function () {

            if (form.parsley('isValid') == true) {
                var options = {
                    dataType: 'json',
                    beforeSubmit: showRequest,
                    success: showResponse
                };
                $(this).ajaxSubmit(options);
                return false;

            } else {
                return false;
            }

        });

    });

    function showRequest() {
        $('.ajaxLoading').show();
    }
    function showResponse(data) {

        if (data.status == 'success') {
            ajaxViewClose('#{{ $pageModule }}');
            ajaxFilter('#{{ $pageModule }}', '{{ $pageUrl }}/data');
            notyMessage(data.message);
            $('#sximo-modal').modal('hide');
        } else {
            notyMessageError(data.message);
            $('.ajaxLoading').hide();
            return false;
        }

    }
</script>	
