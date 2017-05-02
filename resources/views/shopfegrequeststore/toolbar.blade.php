<div class="row c-margin">


    <div class="col-md-4">
        <h3>Narrow Your Search</h3>

        <?php $opts = array("active" => "Active", 'inactive' => "Inactive", 'all' => "All"); ?>
        <select name="activ_inactive_all" class=" select3" id="active_inactive">
            @foreach($opts as $opt => $title)
                <option @if($opt == \Session::get('active_inactive')) selected
                        @endif value="{{ $opt }}">{{ $title }}</option>
            @endforeach
        </select>

        <select name="order_type" id="order_type" class="select3" style="margin-top:5px;">
        </select>

        <select name="product_type" id="product_type" class="select3" style="margin-top:5px;"></select>
    </div>
</div>


<div class="row  c-margin" >

    <div class="col-md-8">
        @if($setting['disableactioncheckbox']=='false')
        @if($access['is_remove'] ==1)
            <a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
        @endif
        @endif
        <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white"
           onclick="SximoModal(this.href,'Advanced Search'); return false;"><i class="fa fa-search"></i>Advanced Search</a>
        @if(SiteHelpers::isModuleEnabled($pageModule))
            <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white"
               onclick="SximoModal(this.href,'Arrange Columns'); return false;"><i class="fa fa-bars"></i> Arrange
                Columns</a>
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
    <div class="col-md-4">
        <div class="pull-right">
            <a href="{{ URL::to('./shopfegrequeststore/new-graphic-request') }}" target="_blank" class="btn btn-sm btn-primary"> Request Custom Graphic</a>
        </div>
    </div>

</div>


<script>
    $(document).ready(function () {



        $("#locations").jCombo("{{ URL::to('shopfegrequeststore/comboselect?filter=location:id:id|location_name') }}",
                {selected_value: '{{ \Session::get('selected_location') }}', initial_text: 'Select Location'});

        $("#order_type").jCombo("{{ URL::to('shopfegrequeststore/comboselect?filter=order_type:id:order_type:can_request:1') }}",
                {selected_value: '{{ $order_type }}', initial_text: 'Select Order Type'});



            $("#order_type").change(function(){
                var order_type = $("#order_type").val();
//                if(order_type != "") {
                    $("#product_type").jCombo("{{ URL::to('shopfegrequeststore/comboselect?filter=product_type:id:product_type') }}&parent=request_type_id:" +order_type,
                            {
//
                                selected_value: '{{ $product_type }}',
                                initial_text: 'Select Product Type'
                            });
//                }
            });
            renderDropdown($(".select3"), { width:"100%"});

            var config_id=$("#col-config").val();
            if(config_id ==0 )
            {
                $('#edit-cols,#delete-cols').hide();
            }
            else
            {
                $('#edit-cols,#delete-cols').show();
            }
            if ($("#public").is(":checked")) {
                $('#groups').show();
            }
            else {
                $('#groups').hide();
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
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?type=store&'+ getFooterFilters()+'&active_inactive=' + $("#active_inactive").val() + '&config_id=' + $("#col-config").val());
    });
    $("#active_inactive,#order_type,#product_type").on('click', function () {
        var type, order_type, product_type = "";
        type = $("#active_inactive").val();
        order_type = $("#order_type").val();
        product_type = $("#product_type").val();
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?&type=store'+ getFooterFilters()+'&active_inactive=' + type + '&order_type=' + order_type + '&product_type=' + product_type + '&config_id=' + $("#col-config").val());
    });

    /* todo refactor code
    $('#locations').on('click', function () {
        if($('#locations').val() != '')
        window.location = "<?php echo url();?>//shopfegrequeststore/changelocation/" + $('#locations').val();
    });*/
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
