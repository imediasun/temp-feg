<div class="row m-b">
	<div class="col-md-8">
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
</div>
<script>
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val());
    });
</script>