<div class="row m-b">

    <div class="col-md-4" >

        <select name="type" class="select3" id="request_type" style="display:inline-block">

            <option disabled>Select Graphic Requests Type</option>
            <option value="open" selected> Open Graphics Requests </option>
            <option value="archive">Graphics Request Archive</option>
        </select>

    </div>
        @if($view=="open")
            <div class="abc" id="number_requests" >
                <p style="color:red;  font-weight: bold">{{ $newGraphicsInfo['number_new_requests'] }} New **</p>
            </div>

        @endif




    <div class="col-md-12" style="padding-top: 8px;">
        @if($access['is_remove'] ==1)
            <a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
        @endif
        <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>
        @if(SiteHelpers::isModuleEnabled($pageModule))
            <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Column Selector'); return false;" ><i class="fa fa-bars"></i> Arrange Columns</a>
            @if(!empty($colconfigs))
                <select class="form-control" style="width:15%!important;display:inline;" name="col-config"
                        id="col-config">
                    <option value="0">Select Configuraton</option>
                    @foreach( $colconfigs as $configs )
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
        @endif
    </div>

</div>
<script>
    $('document').ready(function () {
        setType();
        $(".select3").select2({width: "98%"});
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
        var request_type=$("#request_type").val();
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?view='+request_type+'&config_id='+$("#col-config").val()+ getFooterFilters());
    });
    function setType() {
        $('#request_type option').each(function () {
            if ($(this).val() == "{{ $view }}") {
                $(this).attr('selected', "true");
            }
        });
    }
    $("#request_type").on('change', function () {

        var request_type = $(this).val();
        var footer_filters=getFooterFilters();
        if(footer_filters.indexOf('view') != -1)
        {
            footer_filters = footer_filters.replace( /view.*?&/, '' );
        }

        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?view='+ request_type+ footer_filters);
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
</script>