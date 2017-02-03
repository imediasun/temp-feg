<?php

    $game = $row[0];
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
    $lastEditedDetails = $lastEditedBy . (!empty($lastEditedOn) ? ' on '. DateHelpers::formatDate($lastEditedOn) : '');
    
    $hasManual = $game->has_manual === 1;
    $manualDetails = $hasManual ? "<a href='uploads/games/manuals/{$gameTitleId}.pdf' target='_blank'>Click to View</a>" : '';
    $hasServiceBulletin = $game->has_servicebulletin === 1;
    $serviceBulletinDetails = $hasServiceBulletin ? "<a href='uploads/games/bulletins/{$gameTitleId}.pdf' target='_blank'>Click to View</a>" : '';

    $containerClass = 'game-status-'. ($isSold ? 'sold' : 
            preg_replace('/\W/', '', strtolower($statusName)));
    
    $headingStatus = $isSold ? "Disposed" : ($statusId === 1 ? '' : $statusName);
    $headingMessage = empty($locationId) && empty($prevLocationId) && !$isSold ?
        "Confirm <em>SERIAL #</em> is accurate before saving this game for the first time." : "";

?>
@if($setting['view-method'] =='native')
<div class="sbox">
	<div class="sbox-title">
		<h4> 
            <i class="fa fa-gamepad"></i>
            {{ $gameTitle }} ({{ $assetID }})
            @if (!empty($locationId))
            <small>at {{ $locationIdName }} </small>
            @endif
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
            <label for="sold" class=" control-label col-md-1">
                {!! SiteHelpers::activeLang('Sold', (isset($fields['sold']['language'])? $fields['sold']['language'] : array())) !!}
            </label>
            <div class="col-md-1" style="padding: 5px 0 15px;">               
                <input type="checkbox" name="sold" id="sold" 
                       value="{{ $soldValue }}"     
                       data-original-value='{{ $soldValue }}' 
                       @if($isSold) checked @endif                       
                       />
            </div>
            <div class="col-md-10 soldDetails" 
                @if(!$isSold) style="display: none;" @endif>

                <div class="col-md-4">
                    <div class="input-group" style="width:100%;">
                        {!! Form::text('date_sold', $soldDateFormatted, array(
                            'class'=>'form-control date', 
                            'parsley-errors-container' => '.dateSoldError',
                            'parsley-nofocus' => 'true'
                        )) !!}
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>                    
                    <div class='dateSoldError'></div>
                </div>
                <div class="col-md-8">
                    <input type="text" name="sold_to" class="form-control" 
                           id="sold_to" placeholder="Sold To" value="{{ $soldTo }}"/>
                </div>                                                        
            </div>
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
                    Date Game Down</label>
                <div class="col-md-8">
                    <div class="input-group" style="width:150px !important;">
                        {!! Form::text('date_down', "", array(
                            'class'=>'form-control date',
                            'parsley-errors-container' => '.dateDownError',
                            'parsley-nofocus' => 'true'
                        )) !!}
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
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
        <input type="hidden" name="game_service_id" value="{{ $serviceId }}">
        <div class="upFromRepairDetails" style="display: none;" >
            <div class="form-group">
                <label for="date_up" class=" control-label col-md-4">
                    Date Game Up</label>
                <div class="col-md-8">
                    <div class="input-group" style="width:150px !important;">
                        {!! Form::text('date_up', "",array(
                            'class'=>'form-control date',
                            'parsley-errors-container' => '.dateUpError',
                            'parsley-nofocus' => 'true'                            
                        )) !!}
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
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
            <label for="location_id" class=" control-label col-md-4">
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
        
        <!-- Submit Button -->
        <div class="clearfix submitButtonContainer">
            <hr class="clearfix saveButtonFrameBorder m-t-lg-f" />
            <div class="clearfix">
                <input type="submit" 
                       class="btn btn-large btn-success col-md-offset-4 col-md-4" 
                       name="submit" id="submit" value="Save"/>
            </div>
            <hr class="clearfix saveButtonFrameBorder" />
        </div>
        
        <!-- Serial -->
        <div class="form-group  clearfix" >
            <label for="serial" class=" control-label col-md-4 text-left">
                {!! SiteHelpers::activeLang('Serial Number', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
            </label>
            <div class="col-md-8">
                <input type="text" name="serial" value="{{ $serialNumber }}" 
                       class="form-control" placeholder="Serial #" required/>
            </div>
        </div>
        <!-- version -->
        <div class="form-group  clearfix" >
            <label for="version" class=" control-label col-md-4 text-left">
                {!! SiteHelpers::activeLang('Alt. Version/Signage', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
            </label>
            <div class="col-md-8">
                <input type="text" name="version"  class="form-control gray-bg" 
                       value="{{ $version }}" id="version"/>

            </div>

        </div>
        <!-- Previous Game Name -->
        <div class="form-group clearfix " >
            <label for="prev_game_name" class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Game Converted from:', (isset($fields['prev_game_name']['language'])? $fields['prev_game_name']['language'] : array())) !!}
            </label>
            <div class="col-md-8">
                <input type="text" name="prev_game_name" id="prev_game_name"  
                       class="form-control gray-bg" value="{{ $prevGameName }}" />
            </div>
        </div>
        </div>
        
        <div class="form-group  clearfix" >
            <label class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Game Title', (isset($fields['game_title']['language'])? $fields['game_title']['language'] : array())) !!}
            </label>
            <div class="col-md-8">{{ $gameTitle }}</div>
        </div>
        <div class="form-group clearfix" >
            <label class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Manufacturer', (isset($fields['vendor_name']['language'])? $fields['vendor_name']['language'] : array())) !!}
            </label>
            <div class="col-md-8">{{ $manufacturer }}</div>
        </div>
        <div class="form-group  clearfix" >
            <label class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Game Type', (isset($fields['game_type']['language'])? $fields['game_type']['language'] : array())) !!}
            </label>
            <div class="col-md-8">{{ $gameType }}</div>
        </div>        
        <div class="form-group clearfix " >
            <label class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Asset ID', (isset($fields['asset_number']['language'])? $fields['asset_number']['language'] : array())) !!}
            </label>
            <div class="col-md-8">{{ $assetID }}</div>
        </div>
        <div class="form-group  clearfix" >
            <label class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Serial #', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
            </label>
            <div class="col-md-8">{{ $serialNumber }}</div>
        </div>
        <div class="form-group clearfix" >
            <label class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Current Location', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
            </label>
            <div class="col-md-8">{{ $locationIdName }}</div>
        </div>
        <div class="form-group clearfix" >
            <label class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Previous Location', (isset($fields['']['language'])? $fields['serial']['language'] : array())) !!}
            </label>
            <div class="col-md-8">{{ $prevLocationIdName }}</div>
        </div>
        <div class="form-group clearfix" >
            <label class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Last Edited By', (isset($fields['last_edited_by']['language'])? $fields['last_edited_by']['language'] : array())) !!}
            </label>
            <div class="col-md-8">{{ $lastEditedDetails }}</div>
        </div>
        <div class="form-group clearfix" >
            <label class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Current Product', (isset($fields['']['language'])? $fields['serial']['language'] : array())) !!}
            </label>
            <div class="col-md-8"></div>
        </div>
        <div class="form-group clearfix" >
            <label class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Game Manual', (isset($fields['has_manual']['language'])? $fields['has_manual']['language'] : array())) !!}
            </label>
            <div class="col-md-8">{!! $manualDetails !!}</div>
        </div>        
        <div class="form-group clearfix" >
            <label class=" control-label col-md-4">
                {!! SiteHelpers::activeLang('Game Bulletin', (isset($fields['has_bulletin']['language'])? $fields['has_bulletin']['language'] : array())) !!}
            </label>
            <div class="col-md-8">{!! $serviceBulletinDetails !!}</div>
        </div>
    </div>

    <div class="col-md-6 text-center nogallary gameImageContainer">
        {!! SiteHelpers::showUploadedFile(SiteHelpers::getGameImage($gameTitleId),'/uploads/games/images/',400,false) !!}
        <div class="col-md-offset-2 col-md-6" style="background: #fff;padding:10px;text-align: center">
            <h3>{{ $gameTitle }}</h3>
        </div>
    </div>
</div>

<div class="row" style="background: #FFF;padding:20px auto;box-shadow: 1px 1px 5px gray;margin:30px auto">
    <div class="col-md-12">
        <h2 class="m-b-f m-t-f">Game Service History</h2>
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
                <td>{{ DateHelpers::formatDate($service_history->date_down) }}</td>
                <td>{{ $service_history->down_first_name}} {{ $service_history->down_last_name }}</td>
                <td>{{ $service_history->problem }}</td>
                <td>{{ $service_history->solution }}</td>
                <td>{{ $service_history->up_first_name }} {{ $service_history->up_last_name }}</td>
                <td>{{ DateHelpers::formatDate($service_history->date_up) }}</td>

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
        <h2 class="m-b-f m-t-f">Game Move History</h2>
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
                            <td> {{ DateHelpers::formatDate($move_history->from_date) }}</td>
                            <td>{{ $move_history->from_location }}</td>
                            <td>{{ $move_history->to_location}}</td>
                            <td>{{ $move_history->from_name }} </td>
                            <td>{{ $move_history->to_name }} </td>
                            <td>{{ DateHelpers::formatDate($move_history->to_date) }} </td>
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

<script type="text/javascript">
    
    var mainUrl = '{{ $pageUrl }}',
        mainModule = '{{ $pageModule }}';
    
    $(document).ready(function() {
        App.modules.games.detailedView.init({
                'container': $('#'+pageModule+'View'),
                'moduleName': pageModule,
                'mainModule': mainModule,
                'url': pageUrl,
                'mainUrl': mainUrl,
            },
            {!! json_encode($game) !!}
        );
        
    });
</script>