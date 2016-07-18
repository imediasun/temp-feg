<div class="row m-b">
    <div class="col-md-8">
        @if($access['is_add'] ==1)
            {!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}
            <a href="javascript://ajax" class="btn btn-sm btn-white"
               onclick="ajaxCopy('#{{ $pageModule }}','{{ $pageUrl }}')"><i class="fa fa-file-o"></i> Copy </a>
        @endif
        @if($access['is_remove'] ==1)
            <a href="javascript://ajax" class="btn btn-sm btn-white"
               onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i
                        class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
        @endif
        <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white"
           onclick="SximoModal(this.href,'Advance Search'); return false;"><i class="fa fa-search"></i> Search</a>
        @if(SiteHelpers::isModuleEnabled($pageModule))
            <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white"
               onclick="SximoModal(this.href,'Column Selector'); return false;"><i class="fa fa-bars"></i> Arrange
                Columns</a>
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
<div class="row">
    <div class="col-md-4">
        <select name='product_list_type' rows='5'  id='product_list_type' class="select3" style="height: auto; font-size: 13px; font-family: 'Lato', sans-serif;
width: 75%">
            <option selected selected>------------ Select Type --------------</option>
            <option value="redemption" data-active="0">Redemption Prizes</option>
            <option value="redemption" data-active="1">Redemption Prizes - INACTIVE</option>
            <option value="instant" data-active="0">Instant Win Prizes</option>
            <option value="instant" data-active="1">Instant Win Prizes - INACTIVE</option>
            <option value="graphics" data-active="0">Graphics</option>
            <option value="ticketokens" data-active="0">Tickets,Tokens,Uniforms,Photo ,Paper-Debit, Cards</option>
            <option value="party" data-active="0">Party Supplies</option>
            <option value="officesupplies" data-active="0">Office Supplies - Products List</option>
            <option value="parts" data-active="0">Parts - Products List</option>
            <option value="productsindevelopment" data-active="0"></option>
        </select></div>
    <div class="col-md-6">
        {!! Form::open(array('url'=>'product/listcsv', 'class'=>'form-horizontal','files' => true ,
        'parsley-validate'=>'','novalidate'=>' ')) !!}
        <div class="col-md-2"><h3> Export </h3></div>
        <div class="col-md-6">
            <select name='vendor_id' rows='5' id='vendor_id' class='select3'></select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Export To CSV</button>
        </div>


        {!! Form::close() !!}
    </div>

    <div class="col-md-5 " style="width: auto; padding-bottom: 6px;">
        @if($access['is_excel'] ==1)
            <div class="pull-right">
                <a href="{{ URL::to( $pageModule .'/export/excel?return='.$return) }}" class="btn btn-sm btn-white">
                    Excel</a>
                <a href="{{ URL::to( $pageModule .'/export/word?return='.$return) }}" class="btn btn-sm btn-white">
                    Word </a>
                <a href="{{ URL::to( $pageModule .'/export/csv?return='.$return) }}" class="btn btn-sm btn-white">
                    CSV </a>
                <a href="{{ URL::to( $pageModule .'/export/print?return='.$return) }}" class="btn btn-sm btn-white"
                   onclick="ajaxPopupStatic(this.href); return false;"> Print</a>
            </div>
        @endif
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#product_list_type option").each(function(){
            if($(this).val()=="{{ $prod_list_type }}" && $(this).attr('data-active')=="{{ $active }}")
            {
                $(this).attr('selected',true);
            }
        });
        $("#vendor_id").jCombo("{{ URL::to('product/comboselect?filter=vendor:id:vendor_name') }}",
                {selected_value: '', initial_text: '--- Select Vendor ---'});
        $(".select3").select2({width: "98%"});
    });
    $("#col-config").on('change', function () {
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?config_id=' + $("#col-config").val());
    });
    $("#product_list_type").change(function () {

        var val = $("#product_list_type").val();
        var active = $(this).find('option:selected').attr('data-active');
        if (val) {
            reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?prod_list_type=' + val + '&active=' + active);
        }
    });
</script>
