@extends('layouts.app')
@section('content')

    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3>   <small>{{ $pageTitle }}</small></h3>
            </div>
        </div>
        <div class="page-content-wrapper m-t">
            <div class="sbox animated fadeInRight">
                <div class="sbox-title"> <h4> <i class="fa fa-table"></i> {{ $pageTitle }} <small>{{ $pageNote}}</small></h4></div>
                <div class="sbox-content">


                    <div class="row" >
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
                                <td><h4>{{ $row[0]->location_name.' ' .$row[0]->street1 .' '.$row[0]->city.','.$row[0]->state.' '.$row[0]->zip }}</h4></td>
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
                                <td><h4> {{ $row[0]->first_name.' '.$row[0]->last_name }} </h4></td></td>
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

   <div class="table-responsive">
       <h1>Bill-Backs</h1>
                   <table class=" table">
                       <?php $titles=array('debitcard'=>'Debit Cards','tickets'=>'Tickets','thermalpaper'=>'Thermal Paper','tokens'=>'Tokens','licenses'=>'Licenses','majorattractions'=>'Major Attractions','redemptionprizes'=>'Redemption Prizes','instantwinprizes'=>'Instant Win Prizes'); ?>
                       {!! Form::open(array('url'=>'location/update/'.$row[0]->id, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

                  @foreach($titles as $key=>$title)
                               <tr>
                                    <td><h4> {{ $title }} </h4></td>
                                    <td><label><input type="radio" name="{{ $key }}" value="0"> NONE</label></td>
                                   <td><label><input type="radio" name="{{ $key }}" value="{{ $key }}_pct" onchange="showIBoxPct()">  PCT%</label></td>
                                   <td><label><input type="radio" name="{{ $key }}" value="{{ $key }}_fixed" onchange="showIBoxFixed()">  FIXED</label></td>
                                </tr>
                  @endforeach
                           <br/>
                           <tr>
                               <td></td>
                               <td>
                           <button style="margin-top:20px;"  type="submit" class=" col-md-12 btn btn-primary  btn-lg" name="update">SAVE</button>
                               </td>
                           </tr>
                               {!! Form::close() !!}
   </table>
       </div>


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
                $('.pct').on('ifChecked', function(event){
                   var html='<input type="text" name="pct"/>'+
                                   '<input type="text" name=""';
                });

            </script>

@stop
