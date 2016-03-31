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
              {!! Form::open(array('url'=>'mylocationgame/update/'.$row[0]->asset_number, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'mylocationgameFormAjax')) !!}


              <input type="hidden" name="prev_game_name" value="{{ $row[0]->game_name }}">
              <input type="hidden" name="prev_location_id" value="{{ $row[0]->location_id }}">
              <input type="hidden" name="game_service_id" value="{{ $row[0]->game_service_id }}">
                        <div class="col-md-offset-1 col-md-5" style="background:#FFF;box-shadow:1px 1px 5px gray;padding:20px auto;">
                            <h1>Game Details</h1><br/>
                            <div class="form-group" >
                                <label for="sold" class=" control-label col-md-1">
                                    {!! SiteHelpers::activeLang('Sold', (isset($fields['sold']['language'])? $fields['sold']['language'] : array())) !!}
                                </label>
                                <div class="col-md-1">
                                    <input type="hidden" name="sold" value="0"/>
                                    <input type="checkbox" @if($row[0]->sold==1) checked @endif  name="sold" value="1" id="sold" style="vertical-align: middle;margin-top:9px;"/>
                                </div>
                                <div class="col-md-5">
                                    <input type="date" name="date_sold" class="form-control" id="date_sold" placeholder="Date Sold" value="{{ $row[0]->date_sold }}"/>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="sold_to" class="form-control" id="sold_to" placeholder="Sold To" value="{{ $row[0]->sold_to }}"/>
                                </div>


                            </div>
                            <div class="clearfix"></div><br/>
                            <div class="form-group">
                                <label for="status" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Status', (isset($fields['status']['language'])? $fields['status']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    <select name='status_id'  class='select2 form-control' id="status"></select>
                                </div>

                            </div><br/>
<div class="hideshow">

                                <div class="form-group">
                                    <label for="down_date" class=" control-label col-md-4">
                                        Date Game Down    </label>
                                    <div class="col-md-8">
                                        <input type="date" name="date_down" id="date_down" class="form-control" value=""/>
                                    </div>
                                </div><br/>

                                <div class="form-group">
                                    <label for="status" class=" control-label col-md-4">
                                        Explain Problem
                                        **USE AS MUCH DETAIL AS POSSIBLE**        </label>
                                    <div class="col-md-8">
                                        <textarea rows="5" class="form-control" name="problem"></textarea>
                                    </div>
                            </div>
    </div>
                            <div class="form-group">
                                <label for="status" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Location', (isset($fields['location']['language'])? $fields['location']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    <select  name='location_id' rows='5' id='location_id' class='select2 form-control'></select>
                                </div>

                            </div>

                            <div class="clearfix" style="border-bottom: 1px solid #a9a9a9;padding: 30px"></div><br/>

                            <input type="submit" class="btn btn-large btn-success col-md-offset-4 col-md-4" name="submit" id="submit" value="Save"/>

                            <div class="clearfix" style="border-bottom: 1px solid #a9a9a9;padding: 30px"></div><br/>
                            <div class="form-group  " >
                                <label for="Serial Number" class=" control-label col-md-4 text-left">
                                    {!! SiteHelpers::activeLang('Serial Number', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    <input type="text" name="serial" value="{{ $row[0]->serial }}" class="form-control" @if(empty($row[0]->serial))) disabled @endif placeholder="serial #"/>
                                </div>

                            </div><br/><br/>
                            <div class="form-group  " >
                                <label for="Alt. Version Signage" class=" control-label col-md-4 text-left">
                                    {!! SiteHelpers::activeLang('Alt. Version/Signage', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    <input type="text" name="version_id"  class="form-control" value="{{ $row[0]->version }}" id="version_id"/>

                                </div>

                            </div> <br/><br/>
                            <div class="form-group  " >
                                <label for="game_name" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Game Converted from:', (isset($fields['prev_game_name']['language'])? $fields['prev_game_name']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    <input type="text" name="game_name" id="game_name"  class="form-control" value="{{ $row[0]->game_name }}" />
                                </div>

                            </div>
                            <br/><br/>
                            <div class="form-group  " >
                                <label for="Game Title" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Game Title', (isset($fields['game_title']['language'])? $fields['game_title']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    {{ $row[0]->game_title }}
                                </div>

                            </div><div class="clearfix"></div>
                            <div class="form-group  " >
                                <label for="Manufacturer" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Manufacturer', (isset($fields['vendor_name']['language'])? $fields['vendor_name']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    {{ $row[0]->vendor_name }}
                                </div>

                            </div><div class="clearfix"></div>
                            <div class="form-group  " >
                                <label for="Game Type" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Game Type', (isset($fields['game_type']['language'])? $fields['game_type']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    {{ $row[0]->game_type }}
                                </div>
                                </div>
                            <div class="clearfix"></div>
                            <div class="form-group  " >
                                <label for="Asset Number" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Asset Number', (isset($fields['asset_number']['language'])? $fields['asset_number']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    {{ $row[0]->asset_number }}
                                </div>

                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group  " >
                                <label for="Serial #" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Serial #', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    {{ $row[0]->serial }}
                                </div>

                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group  " >
                                <label for="Current Location" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Current Location', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    @if(isset($row[0]->location_id))
                                    {{ $row[0]->location_id }} {{ "|" }} @endif{{ $row[0]->location_name }}
                                </div>

                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group  " >
                                <label for="Previous Location" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Previous Location', (isset($fields['']['language'])? $fields['serial']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    {{ $row[0]->prev_location_id }} {{ "|" }}  {{ $row[0]->previous_location }}
                                </div>

                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group  " >
                                <label for="Last Edited By" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Last Edited By', (isset($fields['last_edited_by']['language'])? $fields['last_edited_by']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                {{ $row[0]->first_name }} {{ $row[0]->last_name }} {{ "on" }} {{$row[0]->last_edited_on}}
                                </div>

                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group  " >
                                <label for="Current Product" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Current Product', (isset($fields['']['language'])? $fields['serial']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">

                                </div>

                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group  " >
                                <label for="Game Manual" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Game Manual', (isset($fields['has_manual']['language'])? $fields['has_manual']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">

                                        @if($row[0]->has_manual==1)
                                            <a href="uploads/games/manuals/{{ $row[0]->game_title_id }}.pdf"  target="_blank">Click to View</a>
                                        @endif
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group  " >
                                <label for="Game Bulletin" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Game Bulletin', (isset($fields['has_bulletin']['language'])? $fields['has_bulletin']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    @if($row[0]->has_servicebulletin==1)
                                        <a href="uploads/games/bulletins/{{ $row[0]->game_title_id }}.pdf"  target="_blank">Click to View</a>
                                    @endif
                                </div>

                            </div>
                        </div>

                        <div class="col-md-6 text-center nogallary">
                            {!! SiteHelpers::showUploadedFile(SiteHelpers::getGameImage($row[0]->game_title_id),'/uploads/games/images/',400,false) !!}
                            <div class="col-md-offset-2 col-md-6" style="background: #fff;padding:10px;text-align: center">
                                <h3>{{ $row[0]->game_title }} </h3>
                            </div>
                        </div>
                    </div>
        <div class="row" style="background: #FFF;padding:20px auto;box-shadow: 1px 1px 5px gray;margin:50px auto">
        <div class="col-md-12">
            <h2>Game Service History</h2>
            <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Asset Number</th>
                    <th>Date Down</th>
                    <th>By User</th>
                    <th>Problem</th>
                    <th>Solution</th>
                    <th>By User </th>
                    <th>Date Up</th>
                </tr>
                </thead>
                <tbody>

                @if($row['service_history'])
                @foreach($row['service_history'] as $service_history)
                <tr>
                    <td> {{ $service_history->game_id }}</td>
                    <td>{{ $service_history->date_down }}</td>
                    <td>{{ $service_history->down_first_name}} {{ $service_history->down_last_name }}</td>
                    <td>{{ $service_history->problem }}</td>
                    <td>{{ $service_history->solution }}</td>
                    <td>{{ $service_history->up_first_name }} {{ $service_history->up_last_name }}</td>
                    <td>{{ $service_history->date_up }}</td>

                </tr>
                    @endforeach
                    @else
                    <tr><td> Nothing Found </td></tr>
                    @endif
                </tbody>

            </table>
        </div>
        </div>
        <div class="col-md-12" style="margin-bottom:50px">
            <h2>Game Move History</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Depart Date</th>
                        <th>Departed</th>
                        <th>Arrived</th>
                        <th>Moved By</th>
                        <th>Accepted By</th>
                        <th>Arrival Date</th>
                        <th>Days in Transit</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if(($row['move_history']))
                        @foreach($row['move_history'] as $move_history)
                            <tr>
                                <td> {{ $move_history->from_date }}</td>
                                <td>{{ $move_history->from_location }}</td>
                                <td>{{ $move_history->to_location}}</td>
                                <td>{{ $move_history->from_name }} </td>
                                <td>{{ $move_history->to_name }} </td>
                                <td>{{ $move_history->to_date }} </td>
                                <td>{{  \SiteHelpers::getDateDiff($move_history->from_date,$move_history->to_date) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="7"> Nothing Found </td></tr>
                    @endif

                    </tbody>
                    {!! Form::close() !!}
                </table>
            </div>
        </div>
            </div>
@if($setting['form-method'] =='native')
	</div>
</div>
@endif

<script>
    $(document).ready(function() {
        $(".nogallary a.fancybox").removeAttr("rel");
        $("#status").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_status:id:game_status') }}",
                {selected_value: '{{ $row[0]->status_id }}'});
        $("#location_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=location:id:location_name') }}",
                {selected_value: '{{ $row[0]->location_id }}'});
        $(".hideshow").hide();
    });
   $("#status").on('change',function(){
       var form = $('#mylocationgameFormAjax');
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
            $('#sximo-modal').modal('hide');
        } else {
            notyMessageError(data.message);
            $('.ajaxLoading').hide();
            return false;
        }
    }



      var status=$(this).val();
       if(status==1)
       {
         $("#location_id").attr('disabled','true');
       }
       else{
           $("#location_id").removeAttr('disabled');
       }
       if(status==2)
       {
           $(".hideshow").show();
       }
       else{
           $(".hideshow").hide();
       }
   });
    $("#sold").change(function(){
        showSoldStuff();
    });
    function showSoldStuff() {
        if(document.getElementById("sold"))
        {
            if(document.getElementById("sold").checked == true)
            {
                $('#date_sold, #sold_to').show();
            }
            else
            {
                $('#date_sold, #sold_to').hide();
            }
        }
    }
</script>