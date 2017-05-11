<div class="simpleBoxContainer gameExportContainer clearfix">

    {!! Form::open(array('url'=>'mylocationgame/export/csv/games',
        'class'=>'form-horizontal',
        'target'=>'_self',
        'id'=> 'mylocationgameExportFormAjax')) !!}
        
    <div class="clearfix">
        
        <div class="col-md-4 col-sm-6 m-b-xs m-t-xs clearfix">
            <div class="col-sm-3" style="padding: 0px;">
                <h4>Export</h4>
            </div>
            <div class="col-sm-9" style="padding: 0px;">
                <input name="exportID" value="{{ uniqid('gamesdata', true) }}" type="hidden"/>
                <input name='validateDownload' type='hidden' value='1'/>
                <input name='footerfiters' type='hidden' value=''/>
                <select name='game_title_id' id='game_name' class='select4'></select>
            </div>
        </div>
        
         <div class="col-md-4 col-sm-6 m-b-xs m-t-xs clearfix">
            <div class="col-sm-3" style="padding: 0px;">
                <h4>From</h4>
            </div>
            <div class="col-sm-9" style="padding: 0px;">
                <select name='location_id' id='location_id' class='select4'></select>
            </div>
        </div>
        
        <dov class="col-md-2 col-sm-12 m-b-xs m-t-xs clearfix">
            <div class="col-xs-12 clearfix" >
                <button type="submit" class="btn btn-primary submitButton" id="submit" name="submit">Export to CSV</button>
            </div>
        </dov>
        
    </div>
    
    {!! Form::close() !!}
</div>
<div class="simpleBoxContainer assetTagExportContainer clearfix">
    <form method="post" action="mylocationgame/assettag" class="form-horizontal">
        <div class="col-md-6 m-t-xs m-b-xs">
            <input type="text" class="form-control" name="asset_ids" id="asset_ids"
                   placeholder="Enter Asset# -- separate with commas for multiple" required="required"/>
        </div>
        <div class="col-md-3 m-t-xs m-b-xs">
            <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Generate Asset Tag">
        </div>
    </form>
</div>

<div class="row">
    <div class="c-margin clearfix">

    <div class="col-md-7 form-inline">
        @if($access['is_add'] ==1)
            {!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}
        @endif
        @if($setting['disableactioncheckbox']=='false')
            @if($access['is_add'] ==1)
                <a href="javascript://ajax" class="btn btn-sm btn-white"
                   onclick="ajaxCopy('#{{ $pageModule }}','{{ $pageUrl }}')"><i class="fa fa-file-o"></i> Copy </a>
            @endif
            @if($access['is_remove'] ==1)
                <a href="javascript://ajax" class="btn btn-sm btn-white"
                   onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i
                            class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
            @endif
            @endif
            <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white"
               onclick="SximoModal(this.href,'Advanced Search'); return false;"><i class="fa fa-search"></i>Advanced
                Search</a>
       
        
        @if(SiteHelpers::isModuleEnabled($pageModule))
            
                <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white"
                   onclick="SximoModal(this.href,'Arrange Columns'); return false;"><i class="fa fa-bars"></i> Arrange
                    Columns</a>
                @if(!empty($colconfigs))
                    <select class="form-control" name="col-config"
                            id="col-config">
                        <option value="0">Select Column Arrangement</option>
                        @foreach($colconfigs as $configs )
                            <option @if($config_id == $configs['config_id']) selected
                                    @endif value={{ $configs['config_id'] }}> {{ $configs['config_name'] }}   </option>
                        @endforeach
                    </select>
                    @if(\Session::get('uid') ==  \SiteHelpers::getConfigOwner($config_id))
                        <a id="edit-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/edit') }}"
                           class="btn btn-sm btn-white tips"
                           onclick="SximoModal(this.href,'Arrange Columns'); return false;" title="Edit column arrangement"> <i
                                    class="fa fa-pencil-square-o"></i></a>
                        <button id="delete-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/delete') }}"
                                class="btn btn-sm btn-white tips" title="Delete column arrangement"><i class="fa fa-trash-o"></i>
                        </button>
                    @endif
                @endif
            
        @endif
    </div>
    <div class="col-md-5">
        <div class="pull-right">
            <div class="pull-left m-r-xs-f lh-2p5"><strong>Download</strong></div>

            {!! Form::open(array('url'=> URL::to( $pageModule .'/export/csv/history'),
                'class'=>'form-inline pull-left m-l-xxs-f downloadGameMoveHistory downloadForm',
                'target'=>'_self')) !!}
                <input name="exportID" value="{{ uniqid('gamehistory', true) }}" type="hidden"/>
                <input name="footerfiters" value="" type="hidden"/>
                <input name='validateDownload' type='hidden' value='1'/>
                <button type="submit" class="btn btn-sm btn-white submitButton">Game Move History</button>
            {!! Form::close() !!}
            {!! Form::open(array('url'=> URL::to( $pageModule .'/export/csv/pending'),
                'class'=>'form-inline pull-left m-l-xxs-f downloadGamePendingSales downloadForm',
                'target'=>'_self')) !!}
                <input name="exportID" value="{{ uniqid('gamependingsale', true) }}" type="hidden"/>
                <input name="footerfiters" value="" type="hidden"/>
                <input name='validateDownload' type='hidden' value='1'/>
                <button type="submit" class="btn btn-sm btn-white submitButton">Pending Sales List</button>
            {!! Form::close() !!}
            {!! Form::open(array('url'=> URL::to( $pageModule .'/export/csv/forsale'),
                'class'=>'form-inline pull-left m-l-xxs-f downloadGameForSale downloadForm',
                'target'=>'_self')) !!}
                <input name="exportID" value="{{ uniqid('gameforsale', true) }}" type="hidden"/>
                <input name="footerfiters" value="" type="hidden"/>
                <input name='validateDownload' type='hidden' value='1'/>
                <button type="submit" class="btn btn-sm btn-white submitButton" style="margin-right: 0px;">For-Sale List</button>
            {!! Form::close() !!}
    </div>
    </div>
    
</div>
</div>

<script>
    $(document).ready(function () {
        $("#game_name").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_title:id:game_title') }}",
                {selected_value: '', initial_text: '--- Select Game Title ---'}
        );
        $("#location_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=location:id:id|location_name') }}" + "&delimiter=%20|%20",
                {selected_value: '', initial_text: '--- Select Game Location ---'});
        renderDropdown($(".select2, .select3, .select4, .select5"), {width: "100%"});
        var config_id = $("#col-config").val();
        if (config_id == 0) {
            $('#edit-cols,#delete-cols').hide();
        }
        else {
            $('#edit-cols,#delete-cols').show();
        }
        if ($("#private").is(":checked")) {
            $('#groups').hide();
        }
        else {
            $('#groups').show();
        }
    });
    $("#public,#private").change(function () {
        if ($("#public").is(":checked")) {
            $('#groups').show();
        }
        else {
            $('#groups').hide();
        }
    });
    $("#col-config").on('change', function () {
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?config_id=' + $("#col-config").val() + getFooterFilters());
    });
    $('#delete-cols').click(function () {
        if (confirm('Are you sure, You want to delete this columns arrangement?')) {
            showRequest();
            var module = "{{ $pageModule }}";
            var config_id = $("#col-config").val();
            $.ajax(
                    {
                        method: 'get',
                        data: {module: module, config_id: config_id},
                        url: '{{ url() }}/tablecols/delete-config',
                        success: function (data) {
                            showResponse(data);
                        }
                    }
            );
        }
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
