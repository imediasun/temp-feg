<div class="row c-margin">
	<div class="col-md-9">
        @if($access['is_remove'] ==1)
            <a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
        @endif
        <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>
        @if(SiteHelpers::isModuleEnabled($pageModule))
        <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Arrange Columns'); return false;" ><i class="fa fa-bars"></i> Arrange Columns</a>
        @if(!empty($colconfigs))
        <select class="form-control" style="width:auto!important;display:inline;" name="col-config"
                id="col-config">
            <option value="0">Select Column Arrangement</option>
            @foreach( $colconfigs as $configs )
            <option @if($config_id == $configs['config_id']) selected
            @endif value={{ $configs['config_id'] }}> {{ $configs['config_name'] }}   </option>
            @endforeach
        </select>
                @if(\Session::get('uid') ==  \SiteHelpers::getConfigOwner($config_id))
                    <a id="edit-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/edit') }}" class="btn btn-sm btn-white tips"
                       onclick="SximoModal(this.href,'Arrange Columns'); return false;" title="Edit column arrangement">  <i class="fa fa-pencil-square-o"></i></a>
                    <button id="delete-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/delete') }}" class="btn btn-sm btn-white tips" title="Delete column arrangement">  <i class="fa fa-trash-o"></i></button>
                @endif
            @endif
        @endif
    </div>
    <div class="col-md-3">
        <div class="pull-right">
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
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
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val()+ getFooterFilters());
    });
    $('#delete-cols').click(function(){
        if(confirm('Are you sure, You want to delete this columns arrangement?')) {
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
