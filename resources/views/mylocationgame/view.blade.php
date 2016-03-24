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
                        <div class="col-md-6">
                            <h1>Game Details</h1>

                            <div class="form-group" >
                                <label for="status" class=" control-label col-md-6">
                                    {!! SiteHelpers::activeLang('Sold', (isset($fields['sold']['language'])? $fields['sold']['language'] : array())) !!}
                                </label>
                                <div class="col-md-6">
                                    <input type="hidden" name="sold" value="0"/>
                                    <input type="checkbox"  name="sold" value="1" id="sold"/>
                                </div>

                            </div>
                            <div class="clearfix"></div><br/>
                            <div class="form-group">
                                <label for="status" class=" control-label col-md-6">
                                    {!! SiteHelpers::activeLang('Status', (isset($fields['status']['language'])? $fields['status']['language'] : array())) !!}
                                </label>
                                <div class="col-md-6">
                                    <select name='status' rows='5' id='status' class='select2 form-control'></select>
                                </div>

                            </div><br/><br/>
                            <div class="form-group">
                                <label for="status" class=" control-label col-md-6">
                                    {!! SiteHelpers::activeLang('Location', (isset($fields['location']['language'])? $fields['location']['language'] : array())) !!}
                                </label>
                                <div class="col-md-6">
                                    <select disabled name='location' rows='5' id='location' class='select2 form-control'></select>
                                </div>

                            </div>
                            <div class="clearfix" style="border-bottom: 1px solid #a9a9a9;padding: 30px"></div><br/>

                            <button type="submit" class="btn btn-large btn-success col-md-offset-4 col-md-4" name="submit" id="submit">Save</button>

                            <div class="clearfix" style="border-bottom: 1px solid #a9a9a9;padding: 30px"></div><br/>
                            <div class="form-group  " >
                                <label for="Serial Number" class=" control-label col-md-4 text-left">
                                    {!! SiteHelpers::activeLang('Serial Number', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    {!! Form::text('Serial Number', $row[0]->serial,array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                                </div>

                            </div><br/><br/>
                            <div class="form-group  " >
                                <label for="Alt. Version Signage" class=" control-label col-md-4 text-left">
                                    {!! SiteHelpers::activeLang('Alt. Version Signage', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    {!! Form::text('Alt. Version Signage',"",array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                                </div>

                            </div> <br/><br/>
                            <div class="form-group  " >
                                <label for="Serial Number" class=" control-label col-md-4">
                                    {!! SiteHelpers::activeLang('Game Converted From', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
                                </label>
                                <div class="col-md-8">
                                    {!! Form::text('Game Converted From',"",array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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

                        <div class="col-md-6 text-center">
                            {!! SiteHelpers::showUploadedFile(SiteHelpers::getGameImage($row[0]->game_title_id),'/uploads/games/images/',400,false) !!}
                            <div class="col-md-offset-2 col-md-6" style="background: #fff;padding:10px;text-align: center">
                                <h3>{{ $row[0]->game_name }} </h3>
                            </div>
                        </div>
                    </div>
        <div class="row">
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
                    @if($row['move_history'])
                        @foreach($row['move_history'] as $service_history)
                            <tr>

                                <td> {{ $move_history->from_date }}</td>
                                <td>{{ $move_history->from_location }}</td>
                                <td>{{ $move_history->to_location}}</td>
                                <td>{{ $move_history->from_name }} </td>
                                <td>{{ $move_history->to_name }} </td>
                                <td>{{ $move_history->to_date }} </td>
                                <td></td>


                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="7"> Nothing Found </td></tr>
                    @endif

                    </tbody>

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

        $("#status").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_status:id:game_status') }}",
                {selected_value: '{{ $row[0]->status_id }}'});
        $("#location").jCombo("{{ URL::to('mylocationgame/comboselect?filter=location:id:location_name') }}",
                {selected_value: '{{ $row[0]->location_id }}'});
    });
</script>