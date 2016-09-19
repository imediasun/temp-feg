<div class="col-md-12 export-messsage-contaier">
    <div class="bg-success export-message">Your excel file is being prepared. This may take a few minutes depending on the number of records being downloaded. Please do not leave the current page or the file will not download.</div>
</div>
<div class="row">
    <div class="col-md-2">
        <h2>Orders</h2>
    </div>
    <div class="col-md-3">
        <?php
        $orders=array('All'=>'0','Open'=>'OPEN','Fixed Asset Orders'=>'FIXED_ASSET','Products In Development Orders'=>'PRO_IN_DEV');
        ?>
        <select name="order_type" id="order_type" class="form-control">
            <option disabled>         ----- Select Orders ----- </option>
            @foreach($orders as $type=>$value)
                <option @if($value==$order_selected) selected @endif value="{{ $value }}">{{ $type }}</option>
            @endforeach

        </select>
    </div>
</div><br/>

<div class="row m-b" style="margin-bottom: 7px;">
	<div class="col-md-8">
			@if($access['is_add'] ==1)
			{!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}
			<a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxCopy('#{{ $pageModule }}','{{ $pageUrl }}')"><i class="fa fa-file-o"></i> Copy </a>
			@endif
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
	<div class="col-md-4 ">
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
</div>

<script>
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val()+ getFooterFilters());
    });
    $(".export_data_in_excel_or_csv").on('click', function(){
        $(".export-messsage-contaier").fadeIn();
        $(".export-messsage-contaier").fadeOut(30*1000);
    });
    $("#order_type").on('change',function(){

        var val=$(this).val();
        if(val) {
            if (val != 0) {
                reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?order_type=' + val + getFooterFilters());
            }
            else{
                reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data');
            }
        }
    });
</script>