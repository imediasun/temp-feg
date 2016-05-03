<div class="row m-b">
    <div class="col-md-5">

        <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white"
           onclick="SximoModal(this.href,'Advance Search'); return false;"><i class="fa fa-search"></i> Search</a>
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
    <div class="col-md-4"><h1  style="vertical-align:baseline; padding-bottom:0;">
            <img src="./sximo/images/store.png"/>
            FEG Store - <b style="font-size:.7em;">Shopping for</b></h1>
    </div>
    <div class="col-md-3" style="margin-top:10px">
        <select class="select3" id="locations"></select>
        <a class="pull-right" href="https://online.care1st.com/medicare_popupblocker_instructions.php"
           target="_blank" style="font-size:12px;color:red; font-weight:normal;"
           title="You must disable popup-blocking in order for this popup shopping cart to work properly. Contact support@fegllc.com for help">Not
            Working?</a>
    </div>
    <br/>


    <div class="col-md-4">
     <h3>Narrow Your Search</h3>
        <?php $opts = array("active" => "Active", 'inactive' => "IN Active", 'all' => "All"); ?>
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
    <div class="col-md-8">
        <h3>Recently Added Products <small><a  href="{{ URL::to('./shopfegrequeststore/new-graphic-request') }}" style="font-size:1.2em;color:red" target="_blank">*Graphic Not Below? Request New Graphic*</a></small></h3>
        <?php if(isset($cart['new_products'])) { ?>
        <?php foreach($cart['new_products'] as $row): ?>
        <div class="col-md-3" style=" height:100px; overflow: hidden;border: 1px solid lightgray; vertical-align:top;font-size:1em; padding:4px; background:#FFF;box-shadow: 1px 1px 2px 2px lightgrey">
            <a href="{{ URL::to('./shopfegrequeststore/recentlyadded') }}" style="color:black;" target="_blank">
                <?php echo $row['item'];?>
            </a>
        </div>
        <?php endforeach; ?>
        <?php } ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#order_type").jCombo("{{ URL::to('shopfegrequeststore/comboselect?filter=order_type:id:order_type:can_request:1') }}",
                {selected_value: '{{ $order_type }}', initial_text: 'Select Order Type'});
        $("#locations").jCombo("{{ URL::to('shopfegrequeststore/comboselect?filter=location:id:id|location_name') }}",
                {selected_value: '{{ \Session::get('selected_location') }}', initial_text: 'Select Order Type'});

        $("#product_type").jCombo("{{ URL::to('shopfegrequeststore/comboselect?filter=product_type:id:product_type') }}",
                {selected_value: '{{ $product_type }}', initial_text: 'Select Product Type'});
        $(".select3").select2({width: "98%"});
    });
    $("#col-config").on('change', function () {
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?type=store&active_inactive=' + $("#active_inactive").val() + '&config_id=' + $("#col-config").val());
    });
    $("#active_inactive,#order_type,#product_type").on('click', function () {
        var type, order_type, product_type = "";
        type = $("#active_inactive").val();
        order_type = $("#order_type").val();
        product_type = $("#product_type").val();
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?type=store&active_inactive=' + type + '&order_type=' + order_type + '&product_type=' + product_type + '&config_id=' + $("#col-config").val());
    });
    $('#locations').on('click', function () {
  
        window.location = "<?php echo url();?>//shopfegrequeststore/changelocation/" + $('#locations').val();
    });

</script>