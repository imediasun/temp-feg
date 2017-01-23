<div class="row m-b">

    {!! Form::open(array('url'=>'managefegrequeststore/multirequestorderfill/', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'managefegrequeststoreFormAjax')) !!}
    <div >
        <div class="col-md-3 m-b">

        <select name="type" class="select3" id="request_type">
            <option value="archive" @if($view == 'archive'): selected @endif>FEG Store Requests Archives</option>
            <option value="manage" @if($view == 'manage'): selected @endif> Open Requests</option>
        </select>

    </div>
    @if($view == "manage")
        <div class="col-md-3">

        <input  name="order_type" @if($TID )value="{{ $TID }}" @endif id="order_type" type="hidden" onchange="pageRefresh('T');" style="width:98%">
    </div>
    <div class="col-md-2">

        <select id="location_id" class="form-control" name="location_id" onchange="pageRefresh('L');">
            @foreach($manageRequestInfo['loc_options'] as $k => $locations)
                <option @if($LID == $k) selected @endif value="{{ $k }}">{{ $locations}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">

        <select id="vendor_id" class="form-control" name="vendor_id" onchange="pageRefresh('V');">
            @foreach($manageRequestInfo['vendor_options'] as $k => $vendor)
                <option @if($VID== $k) selected @endif value="{{ $k }}">{{ $vendor }}</option>
            @endforeach
        </select>
    </div>
        <div class="col-md-2">

            @if(!empty($VID))
                <button type="submit" name="submit" class="btn btn-primary btn-sm" id="multi-btn"><i class="fa  fa-save" ></i>  Add Items to Order Form </button>
        @endif
        </div>
        @endif
        {!! Form::close() !!}
    </div>

    <div class="clearfix"></div>
    @if($view == "manage")
        <div class="col-md-12" id="number_requests">
            <p style="color:red;font-weight: bold"><?php echo $manageRequestInfo['number_requests']; ?></p>
        </div>
    @endif
    <div class="col-md-12">

        <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white"
           onclick="SximoModal(this.href,'Advanced Search'); return false;"><i class="fa fa-search"></i>Advanced Search</a>
        @if(SiteHelpers::isModuleEnabled($pageModule))
            <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Column Selector'); return false;" ><i class="fa fa-bars"></i> Arrange Columns</a>
            @if(!empty($colconfigs))
                <select class="form-control" style="width:15%!important;display:inline-block;box-sizing: border-box" name="col-config"
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
        <div class="pull-right">
            <a href="{{ URL::to( $pageModule .'/export/excel?return='.$return) }}" class="btn btn-sm btn-white">
                Excel</a>
            <a href="{{ URL::to( $pageModule .'/export/csv?return='.$return) }}" class="btn btn-sm btn-white">
                CSV </a>
        </div>
    </div>




</div>
<script>




    $('document').ready(function () {
        setType();
            var config_id=$("#col-config").val();
            if(config_id ==0 )
            {
                $('#edit-cols,#delete-cols').hide();
            }
            else
            {
                $('#edit-cols,#delete-cols').show();
            }
        $(".select3").select2({width: "98%"});
        $("#order_type").select2({
            dataType: 'json',
            placeholder: "Select an Order Type",
            data: <?php  echo json_encode($manageRequestInfo['order_dropdown-data']) ?>

        });

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
    $("#request_type").on('change', function () {

        var request_type = $(this).val();
        if (request_type == "manage") {
            $('number_requests').show();
        }
        else {
            $('number_requests').hide();
        }
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?view=' + request_type);
    });
    function setType() {
        $('#request_type option').each(function () {
            if ($(this).val() == "{{ $view }}") {
                $(this).attr('selected', "true");
            }
        });
    }
    $("#col-config").on('change', function () {
        var request_type = $("#request_type").val();
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?config_id=' + $("#col-config").val()+'&view=' + request_type + getFooterFilters());
    });

    function pageRefresh(type) {

        var url = document.URL;
        var request_type = $("#request_type").val();
        var urlLength = url.length;
        var pos = url.search(type);
        var get = "?view="+request_type;

        if (pos > 0) {
            url = url.substr(0, pos - 1);
        }

        if (type == 'T') {
            var TID = $('#order_type').val();
            get += "&v1=T" + TID;
            if($('#location').val())
            {
                get+="&v2=L"+$('#location_id').val();

            }
            if($('#venodr').val())
            {
                get+="&v3=V"+$('#venodr_id').val();
            }


        }
        else if (type == 'L') {
            var LID = $('#location_id').val();
            get += "&v2=L" + LID;
            if($('#order_type').val())
            {
                get+="&v1=T"+$('#order_type').val();

            }
            if($('#venodr_id').val())
            {
                get+="&v3=V"+$('#venodr_id').val();
            }
        }
        else if (type == 'V') {
            var VID = $('#vendor_id').val();
            get += "&v3=V" + VID;
            if($('#location_id').val())
            {
                get+="&v2=L"+$('#location_id').val();

            }
            if($('#order_type').val())
            {
                get+="&v1=T"+$('#order_type').val();
            }
        }

        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data' + get );

    }
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