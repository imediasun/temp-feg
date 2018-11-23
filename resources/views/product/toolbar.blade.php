
<div class="row">
    
    <div class="row c-margin">
        
        <div class="col-md-3 sm13" style="display: none;">
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

        <div class="col-md-3" style="display: none;">
           <select name='prod_sub_type_id' rows='5' id='prod_sub_type_id' class='select3'>  </select>
        </div>

        <!-- Import Vendor products list -->
        <div class="col-md-6">
            {{--{!! Form::open(array('url'=> url().'/listImportVendors', 'class'=>'form-horizontal','files' => true, 'method' => 'post')) !!}--}}

            <div class="col-md-2 col-sm-1 col-xs-3"><h3> Import </h3></div>

            <div class="col-md-6 sm13  col-sm-6 col-xs-9">
                <input name="exportID" value="{{ uniqid('vendorFromProducts', true) }}" type="hidden"/>
                <select name='vendor_id' id="vendor_import_list" rows='5' class='select3'>
                    <option value="" selected >--- Select Vendor ---</option>
                    @foreach($vendorsList as $vendor)
                        <option value="{{ $vendor->id }}" >{{ $vendor->vendor_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
                <button disabled id="get_vendor_import_list" type="submit" class="btn btn-primary" onclick="getImportedProductsVendorList($('#vendor_import_list').val())">Review Products</button>
            </div>

            {{--{!! Form::close() !!}--}}
        </div>
        <!-- End -->
    
        
        <div class="col-md-6">
            {!! Form::open(array('url'=> url().'/product/listcsv', 'class'=>'form-horizontal','files' => true)) !!}
            
            <div class="col-md-2 col-sm-1 col-xs-3"><h3> Export </h3></div>
            
            <div class="col-md-6 sm13  col-sm-6 col-xs-9">
                <input name="exportID" value="{{ uniqid('vendorFromProducts', true) }}" type="hidden"/>
                <select name='vendor_id' rows='5' id='vendor_id' class='select3'></select>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
                <button disabled id="submit-btn" type="submit" class="btn btn-primary">Export To CSV</button>
            </div>
    
    
            {!! Form::close() !!}
        </div>
        
 </div>   
 
    <div class="row c-margin" style="margin-left:0px; margin-right:0px;">
        
        <div class="col-md-9">

            @if($access['is_add'] ==1)
                <div class="float-margin">
                    {!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}
                </div>
            @endif

            @if($setting['disableactioncheckbox']=='false')
            @if($access['is_add'] ==1)
                <a href="javascript://ajax" class="btn btn-sm btn-white float-margin"
                   onclick="ajaxCopy('#{{ $pageModule }}','{{ $pageUrl }}')"><i class="fa fa-file-o"></i> Copy </a>
            @endif
            @if($access['is_remove'] ==1)
                <a href="javascript://ajax" class="btn btn-sm btn-white float-margin"
                   onclick="ajaxRemoveProduct('#{{ $pageModule }}','{{ $pageUrl }}');// ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i
                            class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
            @endif
            @endif
            <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white float-margin"
               onclick="SximoModal(this.href,'Advanced Search'); return false;"><i class="fa fa-search"></i>Advanced Search</a>
            @if(SiteHelpers::isModuleEnabled($pageModule))
                <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white float-margin"
                   onclick="SximoModal(this.href,'Arrange Columns'); return false;"><i class="fa fa-bars"></i> Arrange
                    Columns</a>
                @if(!empty($colconfigs))
                    <select class="form-control float-margin height-set" style="width:auto!important;display:inline-block;box-sizing: border-box" name="col-config"
                            id="col-config">
                        <option value="0">Select Column Arrangement</option>
                        @foreach( $colconfigs as $configs )
                            <option @if($config_id == $configs['config_id']) selected
                                    @endif value={{ $configs['config_id'] }}> {{ $configs['config_name'] }}   </option>
                        @endforeach
                    </select>
                        @if(\Session::get('uid') ==  \SiteHelpers::getConfigOwner($config_id))
                            <a id="edit-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/edit') }}" class="btn btn-sm btn-white tips float-margin"
                               onclick="SximoModal(this.href,'Arrange Columns'); return false;" title="Edit column arrangement">  <i class="fa fa-pencil-square-o"></i></a>
                            <button id="delete-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/delete') }}" class="btn btn-sm btn-white tips float-margin" title="Delete column arrangement">  <i class="fa fa-trash-o"></i></button>
                        @endif
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
                    @if(($access['is_add'] =='1' || $access['is_edit']=='1' ) && $setting['inline']=='true' )

                    @endif
                    @if($isExcel)
                        <a href="{{ URL::to( $pageModule .'/export/excel?exportID='.uniqid('excel', true).'&return='.$return) }}" class="btn btn-sm btn-white"> Excel</a>
                    @endif
                    @if($isCSV)
                        <a href="{{ URL::to( $pageModule .'/export/csv?exportID='.uniqid('csv', true).'&return='.$return) }}" class="btn btn-sm btn-white"> CSV </a>
                    @endif
                    @if($isPDF)
                        <a href="{{ URL::to( $pageModule .'/export/pdf?exportID='.uniqid('pdf', true).'&return='.$return) }}" class="btn btn-sm btn-white"> PDF</a>
                    @endif
                    @if($isWord)
                        <a href="{{ URL::to( $pageModule .'/export/word?exportID='.uniqid('word', true).'&return='.$return) }}" class="btn btn-sm btn-white"> Word</a>
                    @endif
                    @if($isPrint)
                        <a href="{{ URL::to( $pageModule .'/export/print?exportID='.uniqid('print', true).'&return='.$return) }}" class="btn btn-sm btn-white" onclick="ajaxPopupStatic(this.href); return false;" > Print</a>
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
          // console.log(url_for_prod_sub_type);
            var type="{{ $product_list_type  }}";

            if(type != 0 && type != "select")
            {
                url_for_prod_sub_type = url_for_prod_sub_type+":request_type_id:"+"{{\Session::get('product_type_id')}}";

            }

            if($('#product_list_type').val()=='productsindevelopment'){
                $("#prod_sub_type_id").hide();
            }else{
                $("#prod_sub_type_id").show();
            }

            $("#prod_sub_type_id").jCombo(url_for_prod_sub_type,
                    {selected_value: '{{ \Session::get('sub_type') }}', initial_text: '--- Select  Subtype ---'  });

            $("#vendor_id").jCombo("{{ URL::to('product/comboselect?filter=vendor:id:vendor_name') }}",
                    {selected_value: '', initial_text: '--- Select Vendor ---'});
            renderDropdown($(".select2, .select3, .select4, .select5"), { width:"100%"});
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
        $('#vendor_id').change(function(){
            if($(this).val())
            {
                $('#submit-btn').enable();
            }
        });

        $('#vendor_import_list').change(function(){
            if($(this).val() != '')
            {
                $('#get_vendor_import_list').enable();
            }
            else{
                $('#get_vendor_import_list').prop('disabled', true);;
            }
        });



        $('#submit-btn').on('click', function (){
            setAndProbeExportFormSessionTimeout($(this).closest('form'));
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

        function getImportedProductsVendorList(vendorId) {
            if($('#vendor_import_list').val() == '0'){
                notyMessageError('Please select a vendor.');
            }else {
               // window.location = '/get-vendor-import-list/'+vendorId;
                reloadData('#product', 'reviewvendorimportlist/data?product_import_vendor_id=' + vendorId+"&search=import_vendor_id:equal:0|is_omitted:equal:0")
            }
        }

    </script>
    
</div>
 