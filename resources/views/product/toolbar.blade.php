
<div class="row">
    <div class="col-md-3">
        <select name='product_list_type' rows='5'  id='product_list_type' class="select3" style="height: auto; font-size: 13px; font-family: 'Lato', sans-serif;
width: 75%">
            <option value="select" data-active="0" selected>------------ Select Type --------------</option>
            <option value="graphics" data-active="0">Graphics</option>
            <option value="instant" data-active="0">Instant Win Prizes</option>
            <option value="officesupplies" data-active="0">Office Supplies - Products List</option>
            <option value="parts" data-active="0">Parts - Products List</option>
            <option value="party" data-active="0">Party Supplies</option>
            <option value="redemption" data-active="0">Redemption Prizes</option>
            <option value="ticketokens" data-active="0">Tickets,Tokens,Uniforms,Photo ,Paper-Debit, Cards</option>
            <option value="productsindevelopment" data-active="0">Products In Development</option>
        </select>


    </div>
    <div class="col-md-3">

       <select name='prod_sub_type_id' rows='5' id='prod_sub_type_id' class='select3 '   >  </select>

    <!--    <option selected selected>------------ Select Product --------------</option>
        <option value="basic" data-active="0">basic</option>
        <option value="marker" data-active="0">marker</option>
        <option value="pen_pencil" data-active="0">penpencil</option>
        <option value="tape" data-active="0">tape</option>
        <option value="fastener" data-active="0">fastener</option>
        <option value="officesupplies" data-active="0">Office Supplies - Products List</option>
        <option value="parts" data-active="0">Parts - Products List</option>
        <option value="productsindevelopment" data-active="0"></option>
        </select>
  -->
</div>

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
    <div class="row m-b" style=" margin-bottom: 10px !important; margin-left:0px; margin-right:0px;  margin-top: 35px !important;">
        <div class="col-md-9">
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
               onclick="SximoModal(this.href,'Advanced Search'); return false;"><i class="fa fa-search"></i>Advanced Search</a>
            @if(SiteHelpers::isModuleEnabled($pageModule))
                <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white"
                   onclick="SximoModal(this.href,'Column Selector'); return false;"><i class="fa fa-bars"></i> Arrange
                    Columns</a>
                @if(!empty($colconfigs))
                    <select class="form-control" style="width:25%!important;display:inline-block;box-sizing: border-box" name="col-config"
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



        <div class="col-md-3 ">
            <?php
            $isExcel = isset($access['is_excel']) && $access['is_excel'] == 1;
            $isCSV = isset($access['is_csv'])  ? ($access['is_csv'] == 1) : $isExcel;
            $isPDF = isset($access['is_pdf'])  && $access['is_pdf'] == 1;
            $isWord = isset($access['is_word'])  && $access['is_word'] == 1;
            $isPrint = isset($access['is_print'])  ? ($access['is_print'] == 1) : $isExcel;
            $isExport = $isExcel || $isCSV || $isPDF || $isWord || $isPrint;
            ?>
            @if($isExport)
                <div class="pull-right">
                    @if($isExcel)
                        <a href="{{ URL::to( $pageModule .'/export/excel?return='.$return) }}" class="btn btn-sm btn-white"> Excel</a>
                    @endif
                    @if($isCSV)
                        <a href="{{ URL::to( $pageModule .'/export/csv?return='.$return) }}" class="btn btn-sm btn-white"> CSV </a>
                    @endif
                    @if($isPDF)
                        <a href="{{ URL::to( $pageModule .'/export/pdf?return='.$return) }}" class="btn btn-sm btn-white"> PDF</a>
                    @endif
                    @if($isWord)
                        <a href="{{ URL::to( $pageModule .'/export/word?return='.$return) }}" class="btn btn-sm btn-white"> Word</a>
                    @endif
                    @if($isPrint)
                        <a href="{{ URL::to( $pageModule .'/export/print?return='.$return) }}" class="btn btn-sm btn-white" onclick="ajaxPopupStatic(this.href); return false;" > Print</a>
                    @endif
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
           var url_for_prod_sub_type="{{ URL::to('product/comboselect?filter=product_type:id:type_description') }}";
            var type="{{ $product_list_type  }}";

            if(type != 0 && type != "select")
            {
                url_for_prod_sub_type = url_for_prod_sub_type+":request_type_id:"+"{{\Session::get('product_type_id')}}";

            }
            $("#prod_sub_type_id").jCombo(url_for_prod_sub_type,
                    {selected_value: '{{ \Session::get('sub_type') }}', initial_text: '--- Select  Subtype ---'  });
            $("#vendor_id").jCombo("{{ URL::to('product/comboselect?filter=vendor:id:vendor_name') }}",
                    {selected_value: '', initial_text: '--- Select Vendor ---'});
            $(".select3").select2({width: "98%"});
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
        $("#col-config").on('change', function () {
            reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?config_id=' + $("#col-config").val()+ getFooterFilters());
        });
        $("#product_list_type").change(function () {
            var sub_type = $("#prod_sub_type_id").val();
            var val = $("#product_list_type").val();
            var active = $(this).find('option:selected').attr('data-active');
            if (val) {
                 var footer_filters=getFooterFilters();
                if(footer_filters.indexOf('sub_type') != -1)
                {
                    footer_filters = footer_filters.replace( /sub_type.*?&/, '' );
                }
                if(footer_filters.indexOf('prod_list_type') != -1)
                {
                    footer_filters = footer_filters.replace( /prod_list_type.*?&/, '' );
                }

                reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?active=' + active + footer_filters+'&prod_list_type=' + val );
            }
        });
        $("#prod_sub_type_id").click(function () {

            var sub_type = $("#prod_sub_type_id").val();
            var type="{{\Session::get('product_type')}}";
            var url="{{ $pageModule }}/data?";
            var active="0";
            url +='&active=' + active + getFooterFilters();
            if(type != "")
            {
                url += "&prod_list_type="+type;
            }
            else
            {
                url += "&prod_list_type=select";
            }
            if(sub_type)
            {
                url +="&sub_type="+sub_type;
            }

            //alert(url);
            reloadData('#{{ $pageModule }}', url);
        });
    </script>
</div>