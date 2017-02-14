
<div class="row">
    {!! Form::open(array('url'=>'mylocationgame/gamelocation', 'class'=>'form-horizontal','id'=> 'mylocationgameFormAjax')) !!}

    <div class="col-md-1">
    <h4>Export</h4>
        </div>
        <div class="col-md-3">
    <div class="form-group  " >
            <input name='validateDownload' type='hidden' value='1' />
            <select name='game_title_id' id='game_name' class='select4 '></select>
    </div>
        </div>
    <div class="col-md-1">
    <h4>From</h4>
        </div>
    <div class="col-md-3">
        <div class="form-group  " >
            <select name='location_id' id='location_id' class='select4 '></select>
        </div>
    </div>

    <div class="col-md-3">
        <button type="submit" class="btn btn-primary submitButton" id="submit" name="submit">Export to CSV</button>
    </div>
    {!! Form::close() !!}
</div>
<div class="row">
   <form method="post" action="mylocationgame/assettag" class="form-horizontal">
    <div class="col-md-offset-1 col-md-3">
        <div class="form-group  " >
            <input type="text" class="form-control" name="asset_ids" id="asset_ids" placeholder="Enter Asset# -- separate with commas for multiple" required="required"/>
        </div>
    </div>
    <div class="col-md-3">
        <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Generate Asset Tag" >
    </div>
   </form>
</div>
<div class="clearfix m-b">
    <div class="pull-left form-inline">
        <div class="pull-left m-r-f" >
        @if($access['is_add'] ==1)
            {!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}
            <a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxCopy('#{{ $pageModule }}','{{ $pageUrl }}')"><i class="fa fa-file-o"></i> Copy </a>
        @endif
        @if($access['is_remove'] ==1)
            <a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
        @endif
        <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>
        </div>
        @if(SiteHelpers::isModuleEnabled($pageModule))
            <div class="pull-left">
            <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Column Selector'); return false;" ><i class="fa fa-bars"></i> Arrange Columns</a>
            @if(!empty($colconfigs))
                <select class="form-control" name="col-config"
                        id="col-config">
                    <option value="0">Select Configuraton</option>
                    @foreach($colconfigs as $configs )
                        <option @if($config_id == $configs['config_id']) selected
                                                                         @endif value={{ $configs['config_id'] }}> {{ $configs['config_name'] }}   </option>
                    @endforeach
                </select>
                    @if(\Session::get('uid') ==  \SiteHelpers::getConfigOwner($config_id))                    
                        <a id="edit-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/edit') }}" class="btn btn-sm btn-white tips"
                           onclick="SximoModal(this.href,'Column Selector'); return false;" title="Edit Arrange">  <i class="fa fa-pencil-square-o"></i></a>
                        <button id="delete-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/delete') }}" class="btn btn-sm btn-white tips" title="Clear Arrange">  <i class="fa fa-trash-o"></i></button>                    
                    @endif
            @endif
            </div>
        @endif
    </div>
    <div class="pull-right">
        <div class="pull-right">
        <div class="pull-left m-r-xs-f lh-2p5"><strong>Download</strong></div>
        <form method="get" action="{{ URL::to( $pageModule .'/history') }}" 
              class="form-inline pull-left m-l-xxs-f downloadGameMoveHistory downloadForm">
            <input name="filter" value="" type="hidden"/>
            <input name='validateDownload' type='hidden' value='1' />
            <button type="submit" class="btn btn-sm btn-white submitButton" >Game Move History</button>
        </form>
        <form method="get" action="{{ URL::to( $pageModule .'/pending') }}" 
              class="form-inline pull-left m-l-xxs-f downloadGamePendingSales downloadForm">
            <input name="filter" value="" type="hidden"/>
            <input name='validateDownload' type='hidden' value='1' />
            <button type="submit" class="btn btn-sm btn-white submitButton" >Pending Sales List</button>
        </form>
        <form method="get" action="{{ URL::to( $pageModule .'/forsale') }}" 
              class="form-inline pull-left m-l-xxs-f downloadGameForSale downloadForm">
            <input name="filter" value="" type="hidden"/>
            <input name='validateDownload' type='hidden' value='1' />
            <button type="submit" class="btn btn-sm btn-white submitButton" >For-Sale List</button>
        </form>
    </div>
</div>
    </div>

<script>
    $(document).ready(function() {
        $("#game_name").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_title:id:game_title') }}",
                {selected_value: '',initial_text:'--- Select Game Title ---'}
                );
        $("#location_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=location:id:id|location_name') }}"+ "&delimiter=%20|%20",
                {selected_value: '',initial_text:'--- Select Game Location ---'});
        renderDropdown($(".select2, .select3, .select4, .select5"), { width:"98%"});
        var config_id=$("#col-config").val();
        if(config_id ==0 )
        {
            $('#edit-cols,#delete-cols').hide();
        }
        else
        {
            $('#edit-cols,#delete-cols').show();
        }
        if ($("#private").is(":checked")) {
            $('#groups').hide();
        }
        else{
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
            reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?config_id=' + $("#col-config").val()+ getFooterFilters());
        });
    $('#delete-cols').click(function(){
        if(confirm('Are You Sure, You want to delete this Columns Arrangement?')) {
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