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
    $('document').ready(function () {
        setType();
        $(".select3").select2({width: "98%"});
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
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?view='+ request_type+ getFooterFilters());
    });
</script>