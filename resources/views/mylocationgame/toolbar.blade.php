
<div class="row">
    {!! Form::open(array('url'=>'mylocationgame/gamelocation', 'class'=>'form-horizontal','id'=> 'mylocationgameFormAjax')) !!}

    <div class="col-md-1">
    <h4>Export</h4>
        </div>
        <div class="col-md-3">
    <div class="form-group  " >
            <select name='game_title_id' id='game_name' class='select3 '></select>
    </div>
        </div>
    <div class="col-md-1">
    <h4>From</h4>
        </div>
    <div class="col-md-3">
        <div class="form-group  " >
            <select name='location_id' id='location_id' class='select3 '></select>
        </div>
    </div>

    <div class="col-md-3">
        <button type="submit" class="btn btn-primary" id="submit" name="submit">Export to CSV</button>
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
<div class="row m-b">
    <div class="col-md-7">
        @if($access['is_add'] ==1)
            {!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}
            <a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxCopy('#{{ $pageModule }}','{{ $pageUrl }}')"><i class="fa fa-file-o"></i> Copy </a>
        @endif
        @if($access['is_remove'] ==1)
            <a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
        @endif
        <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>
        @if(SiteHelpers::isModuleEnabled($pageModule))
            <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Column Selector'); return false;" ><i class="fa fa-bars"></i> Arrange Columns</a>
            @if(!empty($colconfigs))
                <select class="form-control" style=" width:25%!important;display:inline;" name="col-config"
                        id="col-config">
                    <option value="0">Select Configuraton</option>
                    @foreach($colconfigs as $configs )
                        <option @if($config_id == $configs['config_id']) selected
                                                                         @endif value={{ $configs['config_id'] }}> {{ $configs['config_name'] }}   </option>
                    @endforeach
                </select>
                        <a id="edit-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/edit') }}" class="btn btn-sm btn-white"
                           onclick="SximoModal(this.href,'Column Selector'); return false;"><i class="fa fa-bars"></i> Edit Columns Arrangement</a>
                @endif
        @endif
    </div>
    <div class="col-md-5">
        <div class="row  pull-right" style="margin-right: 1%;">
        <span><b>Download</b>&nbsp;</span>
        <a href="{{ URL::to( $pageModule .'/history') }}" class="btn btn-sm btn-white"> Game Move History</a>
        <a href="{{ URL::to( $pageModule .'/pending') }}" class="btn btn-sm btn-white"> Pending Sales List</a>
        <a href="{{ URL::to( $pageModule .'/forsale') }}" class="btn btn-sm btn-white">For-Sale List</a>
    </div>
</div>
    </div>

<script>
    $(document).ready(function() {
        $("#game_name").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_title:id:game_title') }}",
                {selected_value: '',initial_text:'--- Select Game Title ---'}
                );
        $("#location_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=location:id:location_name') }}",
                {selected_value: '',initial_text:'--- Select Game Location ---'});
        $(".select3").select2({ width:"98%"});
        var config_id=$("#col-config").val();
        if(config_id ==0 )
        {
            $('#edit-cols').hide();
        }
        else
        {
            $('#edit-cols').show();
        }
    });
        $("#col-config").on('change', function () {
            reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?config_id=' + $("#col-config").val()+ getFooterFilters());
        });
</script>