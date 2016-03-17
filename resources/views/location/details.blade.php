@extends('layouts.app')
@section('content')

    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3>
                    <small>{{ $pageTitle }}</small>
                </h3>
            </div>
        </div>
        <div class="page-content-wrapper m-t">
            <div class="sbox animated fadeInRight">
                <div class="sbox-title">
                    <h4><i class="fa fa-table"></i> {{ $pageTitle }}
                        <small>{{ $row[0]->id}}</small>
                    </h4>
                </div>
                <div class="sbox-content">


                    <div class="row">
                        <div class="col-md-12" style="text-align: center;margin-bottom: 10px;">
                            <h1>Location {{ $location_id }} Details</h1>

                            <h3 style="color:forestgreen;">
                                @if($row[0]->active==1)
                                    Active
                                @else
                                    Inactive
                                @endif
                            </h3>
                        </div>

                        <div class="col-md-offset-3 col-md-6">

                            <hr/>
                            <div class="table-responsive">
                                <div style="padding:10px;">
                                    <h3>BILL-BACK SUMMARY:</h3>
                                    <h4>Debit Cards: {{ $row[0]->bill_debit_amt }} %</h4>
                                    <h4>Licenses: {{ $row[0]->bill_license_amt }} %</h4>
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

                                <?php $titles = array('bill_debit_amt' => 'Debit Cards', 'bill_ticket_amt' => 'Tickets', 'bill_thermalpaper_amt' => 'Thermal Paper', 'bill_token_amt' => 'Tokens', 'bill_license_amt' => 'Licenses', 'bill_attraction_amt' => 'Major Attractions', 'bill_redemption_amt' => 'Redemption Prizes', 'bill_instant_amt' => 'Instant Win Prizes'); ?>
                                {!! Form::open(array('url'=>'location/save/'.$row[0]->id, 'class'=>'form-horizontal' ,
                                'parsley-validate'=>'','novalidate'=>' ', 'id'=>'locationFormAjax')) !!}
                                <table class="table">
                                    @foreach($titles as $key=>$title)
                                        <tr>

                                            <td> <h3>{{ $title }}</h3>
                                            </td>
                                            <td>
                                                <label><input type="radio" name="{{ $key }}" value="0"> NONE</label>
                                                <label><input @if($row[0]->$key > 0) checked @endif type="radio" name="{{ $key }}" value="1" data-pc="{{ $row[0]->$key }}"> PCT%</label>
                                                <label><input  type="radio" name="{{ $key }}" value="2"
                                                              data-fixed="{{ $row[0]->$key}}"> FIXED</label>
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
                    </div>
                </div>

                <script>
                    $('input').on('ifChecked', function (event) {
                        var val = $(this).val();
                        var dataval = 0.00;
                        var label = "";
                        var name=$(this).attr('name');
                        var name=name+val;
                        var checked=false;
                        if(val==0)
                        {
                            $('.test').hide();
                        }
                        if (val == 1) {
                            label = "PCT % Billed: ";
                            dataval = $(this).attr('data-pc');
                            if(dataval > 0)
                            {
                                checked=true;

                            }
                        }
                        else if (val == 2) {
                            label = "Amount $ Billed:";
                            dataval = $(this).attr('data-fixed');
                            if(dataval > 0)
                            {
                                checked=true;
                            }

                        }
                        if (val == 1 || val == 2) {
                            $(this).parents("tr").next("tr").remove();
                            var html = '<tr class="test"><td colspan="4"><div class="form-group  col-md-8  col-sm-8"><label class="control-label  col-md-4 col-sm-5">' + label + '</label> <div class=" col-md-5 col-sm-5 "><input  type="text"   name="pct" value="' + dataval + '" class="form-control"/></div><label class="col-md-2 col-sm-2"> Details</label>' +
                                    '<div col-md-8 col-sm-8><input type="text" name=' + name+ ' class="form-control" /></div></div></td></tr>';
                        }
                        $(this).parents("tr").after(html);

                    });

                    $(document).ready(function () {

                        var form = $('#locationFormAjax');
                        form.parsley();
                        form.submit(function () {
                            if (form.parsley('isValid') == true) {
                                var options = {
                                    dataType: 'json',
                                    beforeSubmit: showRequest,
                                    success: showResponse
                                }
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
function showinput()
{
    $('input').iCheck('check', function(){
        alert('Well done, Sir');
    });
}
                </script>

@stop
