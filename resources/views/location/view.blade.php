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
        <div class="row">
            <div class="col-md-12" style="text-align: center;margin-bottom: 10px;">
                <h1>Location {{ $id }} Details</h1>
                <h3 style="color:forestgreen;">
                    @if($row[0]->active==1)
                        Active
                    @else
                        Inactive
                    @endif
                </h3>
            </div>
            <div class="col-md-offset-3 col-md-6">
                <h1>BILL-BACK SUMMARY:</h1>
                <div class="table-responsive">
                    <div style="padding:10px;">
                        <h4>Debit Cards: <span style="display:inline-block;margin-left:10px"> {{ $row[0]->bill_debit_amt }} % </span></h4>
                        <h4>Licenses: <span style="display:inline-block;margin-left:20px">{{ $row[0]->bill_license_amt }} %</span></h4>
                    </div>
                    <table class="table">
                        <tbody>
                        <tr rowspan="2">
                            <td colspan="1"><h3>Location ID:</h3></td>
                            <td><h4> {{ $row[0]->id }} </h4></td>
                        </tr>
                        <tr>
                            <td><h3>Address: </h3></td>
                            <td>
                                <h4>{{ $row[0]->location_name.' ' .$row[0]->street1 .' '.$row[0]->city.','.$row[0]->state.' '.$row[0]->zip }}</h4>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1"><h3>Location Short Name:</h3></td>
                            <td><h4> {{ $row[0]->location_name_short }} </h4></td>
                        </tr>
                        <tr>
                            <td colspan="1"><h3>Shipping Restrictions:</h3></td>
                            <td><h4> {{$row[0]->loading_info}} </h4></td>
                        </tr>
                        <tr>
                            <td colspan="1"><h3>Alt. Shipping Location:</h3></td>
                            <td><h4>{{ $row[0]->loc_ship_to }}  </h4></td>
                        </tr>
                        <tr>
                            <td colspan="1"><h3>Date Opened:</h3></td>
                            <td><h4>{{ $row[0]->date_opened }}  </h4></td>
                        </tr>
                        <tr>
                            <td colspan="1"><h3>Date Closed:</h3></td>
                            <td><h4>{{ $row[0]->date_closed }}  </h4></td>
                        </tr>
                        <tr>
                            <td colspan="1"><h3>Location Phone:</h3></td>
                            <td><h4>{{ $row[0]->phone }}  </h4></td>
                        </tr>
                        <tr>
                            <td colspan="1"><h3>Internal Contact:</h3></td>
                            <td><h4></h4></td>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1"><h3>Region:</h3></td>
                            <td><h4>{{ $row[0]->region }}  </h4></td>
                        </tr>
                        <tr>
                            <td colspan="1"><h3>Company:</h3></td>
                            <td><h4> {{ $row[0]->company_name_short }} </h4></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <hr/>
                <h1>Bill-Backs</h1>

                <div class="table-responsive">

                    <?php $titles = array('bill_debit' => 'Debit Cards', 'bill_ticket' => 'Tickets', 'bill_thermalpaper' => 'Thermal Paper', 'bill_token' => 'Tokens', 'bill_license' => 'Licenses', 'bill_attraction' => 'Major Attractions', 'bill_redemption' => 'Redemption Prizes', 'bill_instant' => 'Instant Win Prizes'); ?>
                    {!! Form::open(array('url'=>'location/updatelocation/'.$row[0]->id, 'class'=>'form-horizontal' ,
                    'parsley-validate'=>'','novalidate'=>' ', 'id'=>'locationFormAjax')) !!}
                    <table class="table">
                        @foreach($titles as $key=>$title)
                            <tr>
                                <td> <h3>{{ $title }}</h3>
                                </td>
                                <td>
                                    <?php $keytype=$key."_type";
                                          $keyamt=$key."_amt";
                                          $keydetails=$key."_detail";
                                    ?>
                                    <label><input type="radio" @if( $row[0]->$keytype == 0 ) checked @endif name="{{ $keytype  }}" value="0" data-name="{{ $key }}" > NONE</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label><input @if( $row[0]->$keytype == 1 ) checked @endif type="radio" name="{{ $keytype }}" value="1" data-detail="{{ $row[0]->$keydetails }}" data-pc="{{ $row[0]->$keyamt }}" data-name="{{ $key }}"> PCT%</label>
                                    &nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;<label><input  type="radio" @if( $row[0]->$keytype == 2 ) checked @endif name="{{ $keytype }}" value="2"
                                                                                   data-detail="{{ $row[0]->$keydetails }}" data-pc="{{ $row[0]->$keyamt }}" data-name="{{ $key }}"> FIXED</label>
                                </td>
                            </tr>
                        @endforeach
                        <tr> <td colspan="1"><button style="margin-top:20px;" type="submit"
                                                     class=" col-md-12 btn btn-primary  btn-lg" name="update">SAVE
                                </button></td><tr></tr></tr>
                    </table>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-3">
                    <ul class="parsley-error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>



@if($setting['form-method'] =='native')
	</div>
</div>
@endif

    <script>
        $('input').on('change', function (event) {
        showRadioboxes(this);

        });
        function showRadioboxes(event)
        {
            var val = $(event).val();
            var  dataval = $(event).attr('data-pc');
            var datadetail=$(event).attr('data-detail');
            var label = "";
            var name=$(event).attr('data-name');
            var name1=name+"_amt";
            var name2=name+"_detail";
            var checked=false;
            var remove=name1+"test";
            if(val==0)
            {
                $('.'+remove).hide();
            }
            if (val == 1) {
                label = "PCT % Billed: ";
            }
            else if (val == 2) {
                label = "Amount $ Billed:";
            }
            if (val == 1 || val == 2) {

                $(event).parents("tr").next("tr."+remove).remove();
                var html = '<tr class="'+name1+'test"><td colspan="4"><div class="form-group  col-md-10  col-sm-8"><label class="control-label  col-md-4 col-sm-5">' + label + '</label> <div class=" col-md-5 col-sm-5 "><input  type="text"   name="'+name1+'" value="' + dataval + '" class="form-control"/></div><label class="col-md-2 col-sm-2"> Details</label>' +
                        '<div col-md-8 col-sm-8><input type="text" name="' + name2+ '" value="'+datadetail+'" class="form-control" /></div></div></td></tr>';
            }
            $(event).parents("tr").after(html);
        }

        $(document).ready(function () {
            $('input').each(function () {
                if ($(this).is(":checked") && $(this).val() != 0) {
                    showRadioboxes(this);
                }
            });
            var form = $('#locationFormAjax');
            form.parsley();
            form.submit(function(){

                if(form.parsley('isValid') == true){
                    var options = {
                        dataType:      'json',
                        beforeSubmit :  showRequest,
                        success:       showResponse
                    }
                    $(this).ajaxSubmit(options);
                    return false;

                } else {
                    return false;
                }

            });

        function showRequest()
        {
            $('.ajaxLoading').show();
        }
        function showResponse(data)  {

            if(data.status == 'success')
            {
                ajaxViewClose('#{{ $pageModule }}');
                ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
                notyMessage(data.message);
                $('.ajaxLoading').hide();
                $('#sximo-modal').modal('hide');
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }
        });

    </script>
    </div>