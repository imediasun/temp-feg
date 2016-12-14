<div class="row m-b">


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


<div class="row " >

    <div class="col-md-6">

        <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white"
           onclick="SximoModal(this.href,'Advanced Search'); return false;"><i class="fa fa-search"></i>Advanced Search</a>
        @if(SiteHelpers::isModuleEnabled($pageModule))
            <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white"
               onclick="SximoModal(this.href,'Column Selector'); return false;"><i class="fa fa-bars"></i> Arrange
                Columns</a>
            @if(!empty($colconfigs))
                <select class="form-control" style="width:40%!important;display:inline;" name="col-config"
                        id="col-config">
                    <option value="0">Select Configuration</option>
                    @foreach( $colconfigs as $configs )
                        <option @if($config_id == $configs['config_id']) selected
                                                                         @endif value={{ $configs['config_id'] }}> {{ $configs['config_name'] }}   </option>
                    @endforeach
                </select>
            @endif
        @endif
    </div>
    <div class="col-md-6 style=float:right;">
        <h3 class="pull-right"> <small><a  href="{{ URL::to('./shopfegrequeststore/new-graphic-request') }}" target="_blank" class="btn btn-primary">Request Custom Graphic</a></small></h3>

    </div>

</div>


<script>
    $(document).ready(function () {

        setTimeout(function(){

        $("#locations").jCombo("{{ URL::to('shopfegrequeststore/comboselect?filter=location:id:id|location_name') }}",
                {selected_value: '{{ \Session::get('selected_location') }}', initial_text: 'Select Location'});

        $("#order_type").jCombo("{{ URL::to('shopfegrequeststore/comboselect?filter=order_type:id:order_type:can_request:1') }}",
                {selected_value: '{{ $order_type }}', initial_text: 'Select Order Type'});



        $("#product_type").jCombo("{{ URL::to('shopfegrequeststore/comboselect?filter=product_type:id:product_type') }}&parent=request_type_id:",
                {  parent: '#order_type' ,selected_value : '{{ $product_type }}', initial_text: 'Select Product Type'});


        }, 5000);



        $(".select3").select2({width: "98%"});
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
    $('#locations').on('click', function () {
        if($('#locations').val() != '')
        window.location = "<?php echo url();?>//shopfegrequeststore/changelocation/" + $('#locations').val();
    });

</script>