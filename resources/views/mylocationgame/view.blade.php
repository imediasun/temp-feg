<?php
    $game = $row[0];
    $gameNotes = $game->notes;
    $statusId = $game->status_id;
    $statusName = $game->game_status;
    
    $soldValue = $game->sold;
    $isSold = $soldValue === 1;
    $soldTo = $game->sold_to;
    $soldDate = $game->date_sold;
    $soldDateFormatted = DateHelpers::formatDate($game->date_sold);

    $assetID = $game->asset_number;
    $locationId = $game->location_id;
    $locationIdName = $game->location_name;
    $indendedLocation = $game->intended_first_location;
    $prevLocationId = $game->prev_location_id;
    $dropdownLocation = $locationId;
    if ($statusId == 3) {
        $dropdownLocation = $indendedLocation;
    }
    if (empty($dropdownLocation)) {
        $dropdownLocation = 0;
    }
    $game->dropdownlocation = $dropdownLocation;
    
    $serviceId = $game->game_service_id;
    
    if (!empty($serviceId)) {
        $serviceHistoryIndex = array_search($serviceId, array_column($row['service_history'], 'id'));
        $lastServiceData = $row['service_history'][$serviceHistoryIndex];
    }
    
    $moveId = $game->game_move_id;
    
    $serialNumber = $game->serial;
    $version = $game->version;
    $prevGameName = $game->prev_game_name;
    
    $gameTitle = $game->game_title;
    $gameTitleId = $game->game_title_id;
    $manufacturer = $game->vendor_name;
    $gameType = $game->game_type;
    $prevLocationIdName = $game->previous_location;
    $lastEditedBy = $game->last_edited_by;
    $lastEditedOn = $game->last_edited_on;
    $lastEditedDetailsDate = [];

    if (empty(trim($lastEditedBy))) {
        $lastEditedBy = " - ";
    }
    if (!empty(trim($lastEditedOn))) {
        $lastEditedDetailsDate[] = $lastEditedBy;
        $lastEditedDetailsDate[] = DateHelpers::formatDateCustom($lastEditedOn);
    }
    else {
        $lastEditedDetails[] = $game->last_edited_by;
    }
    $lastEditedDetails = implode(' on ', $lastEditedDetailsDate);
    
    $hasManual = $game->has_manual === 1;
    $manualDetails = $hasManual ? "<a href='uploads/games/manuals/{$gameTitleId}.pdf' target='_blank'>Click to View</a>" : '';
    $hasServiceBulletin = $game->has_servicebulletin === 1;
    $serviceBulletinDetails = $hasServiceBulletin ? "<a href='uploads/games/bulletins/{$gameTitleId}.pdf' target='_blank'>Click to View</a>" : '';

    $containerClass = 'game-status-'. ($isSold ? 'sold' : 
            preg_replace('/\W/', '', strtolower($statusName)));
    $isNewlyAddedGame = empty($locationId) && empty($prevLocationId) && !$isSold;
    $headingStatus = $isSold ? "Disposed" : ($statusId === 1 ? '' : $statusName);
    $headingMessage = $isNewlyAddedGame ?
        "Confirm <em>SERIAL #</em> is accurate before saving this game for the first time." : "";

?>
@if($setting['view-method'] =='native')
<div class="sbox">
	<div class="sbox-title">
		<h4> 
            <i class="fa fa-eye"></i>
            @if($headingStatus == '') Move My Location's Game @else Move Game {{$headingStatus}}@endif
            {{--{{ $gameTitle }} ({{ $assetID }})
            @if (!empty($locationId))
            <small>at {{ $locationIdName }} </small>
            @endif--}}
			<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}', this)">
			<i class="fa fa fa-times"></i></a>
		</h4>
	 </div>

	<div class="sbox-content">
@endif
<div class="row">
    {!! Form::open(array('url'=>'mylocationgame/update/'. $assetID, 'class'=>'form-horizontal', 'novalidate'=>'', 'id'=> 'mylocationgameFormAjax')) !!}
    <div class="col-md-offset-1 col-md-5 gameDetailsContainer {{ $containerClass }}">
        <div class="titleSection">
            <h1 class="m-b-f clearfix">Game Details</h1>
            <input type="hidden" name="from_detailed_page" value="1">
            @if($headingStatus!=='') <h2 class="headStatus clearfix">{{ $headingStatus }}</h2>@endif
            @if($headingMessage!=='') <h2 class="headMessage clearfix">{!! $headingMessage !!}</h2>@endif
        </div>
        
        <div class="inputSection">
        @if($statusId!=2)
        <!-- Sold Inputs -->
        <div class="form-group soldInputs clearfix" >
            <input type="hidden" name="sold" value="{{ $soldValue  }}"/>
        </div>
        @endif
        <!-- Status inputs -->
        <div class="form-group statusInputs">
            <input type="hidden" name="old_status_id" value="{{ $statusId }}">
            <label for="status_id" class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Status', (isset($fields['status']['language'])? $fields['status']['language'] : array())) !!}
            </label>
            <div class="statusSoldErrorMessage"></div>
            <div class="col-md-8">
                <select name='status_id'  class='select2' id="status"
                        data-original-value='{{ $statusId }}' required
                        parsley-required='true'
                        @if($isSold) disabled @endif
                        ></select>
            </div>

        </div>
        @if($statusId != 2)
        <!-- Down for repair inputs -->
        <div class="downforRepairDetails" style="display: none;" >
            <div class="form-group">
                <label for="date_down" class=" control-label col-md-4">
                    Date Game Down:</label>
                <div class="col-md-8">

                    <span class="input-group-addon" style="width: 32px;padding-left: 10px;padding-top: 8px;padding-bottom: 8px;float: left;">
                        <i class="fa fa-calendar" id="icon"></i>
                    </span>

                    {!! Form::text('date_down', "", array(
                        'class'=>'form-control date',
                        'parsley-errors-container' => '.dateDownError',
                        'parsley-nofocus' => 'true',
                        'style' => 'width:150px !important;'
                    )) !!}

                    <div class='dateDownError'></div>
                </div>
            </div>
            <div class="form-group">
                <label for="problem" class=" control-label col-md-4">
                    Explain Problem <br/>**USE AS MUCH DETAIL AS POSSIBLE**
                </label>
                <div class="col-md-8">
                    <textarea rows="5" class="form-control" name="problem"></textarea>
                </div>
            </div>
        </div>
        @endif
        
        @if($statusId == 2)
        <!-- up from repair inputs -->
        @if (isset($lastServiceData))        
        <div class="downforRepairDetailsText" > 
            <div class="form-group clearfix">
                <label class="control-label col-md-4">Date Down:</label>
                <div class="col-md-8">
                    {!! DateHelpers::formatDate($lastServiceData->date_down) !!}
                </div>
            </div>
            <div class="form-group clearfix">
                <label class=" control-label col-md-4">Problem:</label>
                <div class="col-md-8">
                    {!! $lastServiceData->problem !!}
                </div>
            </div>
        </div>
        @endif
        <input type="hidden" name="game_service_id" value="{{ $serviceId }}">
        <div class="upFromRepairDetails" style="display: none;" >
            <div class="form-group">
                <label for="date_up" class=" control-label col-md-4">
                    Date Game Up</label>
                <div class="col-md-8">

                    <span class="input-group-addon" style="width: 32px;padding-left: 10px;padding-top: 8px;padding-bottom: 8px;float: left;">
                        <i class="fa fa-calendar" id="icon"></i>
                    </span>

                    <div class="input-group" style="width:150px !important;">
                        {!! Form::text('date_up', "",array(
                            'class'=>'form-control date',
                            'parsley-errors-container' => '.dateUpError',
                            'parsley-nofocus' => 'true',
                            'style' => 'width:150px !important;'
                        )) !!}
                    </div>
                    <div class='dateUpError'></div>
                </div>
            </div>
            <div class="form-group">
                <label for="solution" class=" control-label col-md-4">
                    Explain Solution <br/>**USE AS MUCH DETAIL AS POSSIBLE**
                </label>
                <div class="col-md-8">
                    <textarea rows="5" class="form-control" name="solution"></textarea>
                </div>
            </div>
        </div>
        @endif
        <!-- Location inputs -->
        <div class="form-group locationInputs">
            <input type="hidden" name="intended_first_location" value="{{ $indendedLocation }}">
            <input type="hidden" name="prev_location_id" value="{{ $prevLocationId }}">
            <input type="hidden" name="old_location_id" value="{{ $locationId }}">
            <input type="hidden" name="game_move_id" value="{{ $moveId }}">
            <label for="location_id" class="control-label col-md-4" style="padding-right: 0px">
                {!! SiteHelpers::activeLang('Location', (isset($fields['location']['language'])? $fields['location']['language'] : array())) !!}
                <span class="locationLabelModifier" 
                       @if($statusId != 3) style="display: none;" @endif                      
                      >
                    (Destination)
                </span>
            </label>
            <div class="col-md-8">
                <select  name='location_id' rows='5' id='location_id' class='select2'
                 data-original-value='{{ $locationId }}'
                 @if($isSold) disabled @endif
                 ></select>
            </div>

        </div>
        
        @if ($isNewlyAddedGame)
        <!-- Serial -->
        <div class="form-group  clearfix" >
            <label for="serial" class=" control-label col-md-4 text-left">
                {!! SiteHelpers::activeLang('Serial Number', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
            </label>
            <div class="col-md-8">
                <input type="hidden" name="oldserial" value="{{ $serialNumber }}" />
                <input type="text" name="serial" value="{{ $serialNumber }}"
                       style="width: 100%"
                       class="form-control" placeholder="Serial #" />

            </div>
        </div>
        @endif
        
        @if (!$isSold)
        <!-- Submit Button -->
        <div class="clearfix submitButtonContainer">
            <hr class="clearfix saveButtonFrameBorder m-t-lg-f" />
            <div class="clearfix">
                <input type="submit" 
                       class="btn btn-large btn-success col-md-offset-4 col-md-4" 
                       name="submit" id="submit" value="Save" disabled />
            </div>
            <hr class="clearfix saveButtonFrameBorder" />
        </div>
        @endif       
        </div>
        <div class="form-group  clearfix" >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Game Title', (isset($fields['game_title']['language'])? $fields['game_title']['language'] : array())) !!}:
            </label>
            <div class="col-md-8">{{ \DateHelpers::formatStringValue($gameTitle) }}</div>
        </div>
        <div class="form-group clearfix" >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Manufacturer', (isset($fields['vendor_name']['language'])? $fields['vendor_name']['language'] : array())) !!}:
            </label>
            <div class="col-md-8">{{ \DateHelpers::formatStringValue($manufacturer) }}</div>
        </div>
        <div class="form-group  clearfix" >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Game Type', (isset($fields['game_type']['language'])? $fields['game_type']['language'] : array())) !!}:
            </label>
            <div class="col-md-8">{{ \DateHelpers::formatStringValue($gameType) }}</div>
        </div>        
        <div class="form-group clearfix " >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Asset ID', (isset($fields['asset_number']['language'])? $fields['asset_number']['language'] : array())) !!}:
            </label>
            <div class="col-md-8">{{ \DateHelpers::formatZeroValue($assetID) }}</div>
        </div>
        <div class="form-group  clearfix" >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Serial #', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}:
            </label>
            <div class="col-md-8">{{ \DateHelpers::formatStringValue($serialNumber) }}</div>
        </div>
        <div class="form-group  clearfix" >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Alt. Version/Signage', (isset($fields['version']['language'])? $fields['version']['language'] : array())) !!}:
            </label>
            <div class="col-md-8">{{ \DateHelpers::formatStringValue($version) }}</div>
        </div>
        <div class="form-group  clearfix" >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Game Converted from', (isset($fields['prev_game_name']['language'])? $fields['prev_game_name']['language'] : array())) !!}:
            </label>
            <div class="col-md-8">{{ \DateHelpers::formatStringValue($prevGameName) }}</div>
        </div>
        @if (!$isNewlyAddedGame)
        <div class="form-group clearfix" >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Current Location', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}:
            </label>
            <div class="col-md-8">{{ \DateHelpers::formatStringValue($locationIdName) }}</div>
        </div>
        @endif
        <div class="form-group clearfix" >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Previous Location', (isset($fields['prev_location_id']['language'])? $fields['prev_location_id']['language'] : array())) !!}:
            </label>
            <div class="col-md-8">{{ \DateHelpers::formatStringValue($prevLocationIdName) }}</div>
        </div>
        <div class="form-group clearfix" >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Notes', (isset($fields['note']['language'])? $fields['note']['language'] : array())) !!}:
            </label>
            <div class="col-md-7"><span id="notes_text">{{ $gameNotes }}</span><span id="notes_input" style="display: none;"><textarea name='notes' rows='5' id='notes' class='form-control' required >{{$gameNotes}}</textarea></span></div>
            <div class="col-md-1">
                <a style="margin-top: 8px;" href="javascript:void(0)" id="editNotes" class="collapse-close pull-right btn btn-xs btn-primary">
                    <i class="fa fa fa-pencil"></i>
                </a>
                <a style="margin-top: 8px; display: none" href="javascript:void(0)" id="saveNotes" class="collapse-close pull-right btn btn-xs btn-primary">
                    <i class="fa fa fa-save"></i>
                </a>
                <a style="margin-top: 2px; display: none;width: 22px" href="javascript:void(0)" id="cancelNotes" class="collapse-close pull-right btn btn-xs btn-danger">
                    <i class="fa fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="form-group clearfix" >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Last Edited by', (isset($fields['last_edited_by']['language'])? $fields['last_edited_by']['language'] : array())) !!}:
            </label>
            <div class="col-md-8">{{ \DateHelpers::formatStringValue($lastEditedDetails) }}</div>
        </div>
        <div class="form-group clearfix" >
            <label class="col-md-4">
                Current Product:
            </label>
            <div class="col-md-8">
                @if (count($products) > 0)
                <ul class='productList'>
                @foreach($products as $product) 
                    <li>{!! \DateHelpers::formatStringValue($product->vendor_description) !!}</li>
                @endforeach
                </ul>
                    @else
                    {{ "No Data" }}
                @endif 
            </div>
        </div>
        <div class="form-group clearfix" >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Game Manual', (isset($fields['has_manual']['language'])? $fields['has_manual']['language'] : array())) !!}:
            </label>
            <div class="col-md-8">{!! \DateHelpers::formatStringValue($manualDetails) !!}</div>
        </div>        
        <div class="form-group clearfix" >
            <label class="col-md-4">
                {!! SiteHelpers::activeLang('Game Bulletin', (isset($fields['has_bulletin']['language'])? $fields['has_bulletin']['language'] : array())) !!}:
            </label>
            <div class="col-md-8">{!! \DateHelpers::formatStringValue($serviceBulletinDetails) !!}</div>
        </div>
    </div>

    <div class="col-md-6 text-center nogallary gameImageContainer">
        {!! SiteHelpers::showUploadedFile(SiteHelpers::getGameImage($gameTitleId),'/uploads/games/images/',400,false) !!}
        <div class="center-block" style="background: #fff;padding:10px;text-align: center; width: 400px;">
            <h3>{{ $gameTitle }}</h3>
        </div>
    </div>
</div>

<div class="row" style="background: #FFF;padding:20px auto;box-shadow: 1px 1px 5px gray;margin:30px auto">
    <div class="col-md-12 gameServiceHistoryContainer">
        <h2 class="m-b-f m-t-f">Game Service History</h2>
        <div class="table-responsive">
        <table class="table table-striped gameServiceHistoryTable">
            <thead>
            <tr>
                <th>Game</th>
                <th>Asset ID</th>
                <th>Location</th>
                <th>Down Date</th>
                <th>Down By User</th>
                <th>Problem</th>
                <th>Solution</th>
                <th>Up By User </th>
                <th>Up Date</th>
            </tr>
            </thead>
            <tbody>

            @if($row['service_history'])
            @foreach($row['service_history'] as $service_history)
            <tr>
                {{--*/ $thisGame = $service_history->game_id == $assetID /*--}}
                {{--*/ $gameTitle = $thisGame ? "<b>This Exact Machine</b>" : 
                    ("<em>Another <b>" . $service_history->game_title . "</b></em>") /*--}}
                <td @if($thisGame) class="text-danger" @endif> {!! $gameTitle !!}</td>
                <td> {{ \DateHelpers::formatZeroValue($service_history->game_id) }}</td>
                <td> {{ \DateHelpers::formatMultiValues($service_history->location_id,$service_history->location_name) }} </td>
                <td>{{ DateHelpers::formatDate($service_history->date_down) }}</td>
                <td>{{ \DateHelpers::formatMultiValues($service_history->down_first_name,$service_history->down_last_name)}} </td>
                <td>{{ \DateHelpers::formatStringValue($service_history->problem) }}</td>
                <td>{{ \DateHelpers::formatStringValue($service_history->solution) }}</td>
                <td>{{ \DateHelpers::formatMultiValues($service_history->up_first_name,$service_history->up_last_name) }} </td>
                <td>{{ DateHelpers::formatDate($service_history->date_up) }}</td>

            </tr>
                @endforeach
                @else
                <tr><td colspan="9" style="text-align: center"> No Data </td></tr>
                @endif
            </tbody>

        </table>
    </div>
    </div>
    <div class="col-md-12 gameServiceHistoryContainer" style="margin-bottom:50px">
        <h2 class="m-b-f m-t-f">Game Move History</h2>
        <div class="table-responsive">
            <table class="table table-striped gameMoveHistoryTable">
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
                    @foreach($row['move_history'] as $move_history)
                        <tr>
                            <td> {{ \DateHelpers::formatDate($move_history->from_date) }}</td>
                            <td>{{ \DateHelpers::formatMultiValues($move_history->from_location_id,$move_history->from_location) }} </td>
                            <td>{{ \DateHelpers::formatMultiValues($move_history->to_location_id,$move_history->to_location)}} </td>
                            <td>{{ \DateHelpers::formatMultiValues($move_history->from_first_name,$move_history->from_last_name) }}</td>
                            <td>{{ \DateHelpers::formatMultiValues($move_history->to_first_name,$move_history->to_last_name) }} </td>
                            <td>{{ \DateHelpers::formatDate($move_history->to_date) }} </td>
                            <td>{{ \DateHelpers::formatZeroValue(\SiteHelpers::getDateDiff($move_history->from_date,$move_history->to_date)) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="7" style="text-align: center"> No Data </td></tr>
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

<script type="text/javascript">
    
    var mainUrl = '{{ $pageUrl }}',
        mainModule = '{{ $pageModule }}';
    
    $(document).ready(function() {
        function toggleInput()
        {
            $('#notes_text').toggle();
            $('#saveNotes').toggle();
            $('#notes_input').toggle();
            $('#cancelNotes').toggle();
            $('#editNotes').toggle();
        }
        $('#editNotes').click(function () {
            $('#notes_text').hide();
            $('#saveNotes').show();
            $('#notes_input').show();
            $('#cancelNotes').show();
            $('#editNotes').hide();
        });
        $('#saveNotes').click(function () {
            $.ajax({
                type:'POST',
                url:"{{url('mylocationgame/notes')}}",
                data:{
                    _token:"{{csrf_token()}}",
                    notes:$('#notes_input textarea').val(),
                    id:"{{$game->asset_number}}"
                },
                success:function (data) {
                    $('#notes_text').html($('#notes_input textarea').val()).show();
                    $('#saveNotes').hide();
                    $('#notes_input').hide();
                    $('#cancelNotes').hide();
                    $('#editNotes').show();
                    console.log('notes saved!');
                    console.log(data);

                },
                error:function (data) {
                    console.log('notes save Error!');
                    console.log(data);
                }

            });

        });
        $('#cancelNotes').click(function () {
            $('#notes_input textarea').val($('#notes_text').text());
            $('#notes_text').show();
            $('#saveNotes').hide();
            $('#notes_input').hide();
            $('#cancelNotes').hide();
            $('#editNotes').show();
        });

        App.modules.games.detailedView.init({
                'container': $('#'+pageModule+'View'),
                'moduleName': pageModule,
                'mainModule': mainModule,
                'url': pageUrl,
                'mainUrl': mainUrl
            },
            {!! json_encode($game) !!}
        );
        
    });
</script>
