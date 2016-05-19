<div class="row m-b">
      <div class="col-md-12">
        <br/>
        <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white"
           onclick="SximoModal(this.href,'Advance Search'); return false;"><i class="fa fa-search"></i> Search</a>

    </div>
    {!! Form::open(array('url'=>'managefegrequeststore/multirequestorderfill/', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'managefegrequeststoreFormAjax')) !!}
    <div >
        <div class="col-md-3">
        <br/>
        <select name="type" class="select3" id="request_type">
            <option disabled>Select Requests Type</option>
            <option value="manage" selected> Open Requests</option>
            <option value="archive">FEG Store Requests Archives</option>
        </select>

    </div>
    <div class="col-md-3">
<br/>
        <input  name="order_type" @if($TID )value="{{ $TID }}" @endif id="order_type" type="hidden" onchange="pageRefresh('T');" style="width:98%">
    </div>
    <div class="col-md-2">
        <br/>
        <select id="location_id" class="form-control" name="location_id" onchange="pageRefresh('L');">
            @foreach($manageRequestInfo['loc_options'] as $k => $locations)
                <option @if($LID == $k) selected @endif value="{{ $k }}">{{ $locations}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <br/>
        <select id="vendor_id" class="form-control" name="vendor_id" onchange="pageRefresh('V');">
            @foreach($manageRequestInfo['vendor_options'] as $k => $vendor)
                <option @if($VID== $k) selected @endif value="{{ $k }}">{{ $vendor }}</option>
            @endforeach
        </select>
    </div>
        <div class="col-md-2">
            <br/>
            @if(!empty($VID))
                <button type="submit" name="submit" class="btn btn-primary btn-sm" id="multi-btn"><i class="fa  fa-save" ></i>  Add Items to Order Form </button>
        @endif
        </div>
        {!! Form::close() !!}
    </div>

    <div class="clearfix"></div>
    @if($view == "manage")
        <div class="col-md-12" id="number_requests">
            <br/>

            <p style="color:red;font-weight: bold"><?php echo $manageRequestInfo['number_requests']; ?></p>
        </div>
    @endif
  

</div>
<script>
    $('document').ready(function () {
        setType();

        $(".select3").select2({width: "98%"});
        $("#order_type").select2({
            dataType: 'json',
            placeholder: "Select an Order Type",
            data: <?php  echo json_encode($manageRequestInfo['order_dropdown-data']) ?>

        });

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
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?config_id=' + $("#col-config").val()+'&view=' + request_type);
    });

    function pageRefresh(type) {

        var url = document.URL;
        var request_type = "manage"
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
        //alert(get);
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data' + get);

    }
</script>