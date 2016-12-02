<div class="row m-b">
	<div class="col-md-8">
			<a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>
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
                <a id="edit-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/edit') }}" class="btn btn-sm btn-white"
                   onclick="SximoModal(this.href,'Column Selector'); return false;"><i class="fa fa-bars"></i> Edit Columns Arrangement</a>

            @endif
        @endif
    </div>
	<div class="col-md-4 ">
		<div class="pull-right">
			<a href="{{ URL::to( $pageModule .'/export/csv?return='.$return) }}" class="btn btn-sm btn-white">
                Download Disposed Games List </a>
		</div>
	</div>
</div>
<script>
    $(document).ready(function() {
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
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val()+ getFooterFilters());
    });
</script>