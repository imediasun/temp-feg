<div class="row m-b">
	<div class="col-md-8">
			@if($access['is_add'] ==1)
			{!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}
			<a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxCopy('#{{ $pageModule }}','{{ $pageUrl }}')"><i class="fa fa-file-o"></i> Copy </a>
			@endif 
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
			@endif 	
			<a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>
        @if(SiteHelpers::isModuleEnabled($pageModule))
        <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Column Selector'); return false;" ><i class="fa fa-bars"></i> Arrange Columns</a>
        @if(!empty($colconfigs))
        <select class="form-control" style="width:25%!important;display:inline;" name="col-config"
                id="col-config">
            <option value="0">Select Configuraton</option>
            @foreach( $colconfigs as $configs )
            <option @if($config_id == $configs['config_id']) selected
            @endif value={{ $configs['config_id'] }}> {{ $configs['config_name'] }}   </option>
            @endforeach
        </select>
        @endif
        @endif
    </div>
	<div class="col-md-4 ">
		@if($access['is_excel'] ==1)
		<div class="pull-right">
			<a href="{{ URL::to( $pageModule .'/export/excel?return='.$return) }}" class="btn btn-sm btn-white"> Excel</a>
			<a href="{{ URL::to( $pageModule .'/export/word?return='.$return) }}" class="btn btn-sm btn-white"> Word </a>
			<a href="{{ URL::to( $pageModule .'/export/csv?return='.$return) }}" class="btn btn-sm btn-white"> CSV </a>
			<a href="{{ URL::to( $pageModule .'/export/print?return='.$return) }}" class="btn btn-sm btn-white" onclick="ajaxPopupStatic(this.href); return false;" > Print</a>
		</div>	
		@endif
	</div>
</div>
<div class="row">
    {!! Form::open(array('url'=>'mylocationgame/test', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'mylocationgameFormAjax')) !!}

    <div class="col-md-1">
    <h4>Export</h4>
        </div>
        <div class="col-md-4">
    <div class="form-group  " >
            <select name='game_title_id' id='game_name' class='select2 '></select>
    </div>
        </div>
    <div class="col-md-1">
    <h4>From</h4>
        </div>
    <div class="col-md-4">
        <div class="form-group  " >
            <select name='location_id' id='location_id' class='select2 '></select>
        </div>
    </div>

    <div class="col-md-2">
        <button type="submit" class="btn btn-primary" id="submit" name="submit">Export to CSV</button>
    </div>
    {!! Form::close() !!}
</div>
<script>
    $(document).ready(function() {
        $("#game_name").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_title:id:game_title') }}",
                {selected_value: ''});
        $("#location_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=location:id:location_name') }}",
                {selected_value: ''});
        $(".select2").select2({ width:"98%"});
    });
        $("#col-config").on('change', function () {
            reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?config_id=' + $("#col-config").val());
        });


</script>