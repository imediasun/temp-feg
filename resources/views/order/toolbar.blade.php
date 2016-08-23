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
			<a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>
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
        @if($access['is_excel'] ==1)
            <div class="pull-right">
        <a href="{{ URL::to( $pageModule .'/export/excel?return='.$return) }}" class="export_data_in_excel_or_csv btn btn-sm btn-white">
            Excel</a>
        <a href="{{ URL::to( $pageModule .'/export/csv?return='.$return) }}" class="export_data_in_excel_or_csv btn btn-sm btn-white">
            CSV </a>
    </div>
    @endif
	</div>
</div>

<script>
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val());
    });
    $(".export_data_in_excel_or_csv").on('click', function(){
        notyMessage('Your excel file is being prepared. This may take a few minutes depending on the number of records being downloaded. Please do not leave the current page or the file will not download.');
    });
    $("#order_type").on('change',function(){

        var val=$(this).val();
        if(val) {
            if (val != 0) {
                reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?order_type=' + val);
            }
            else{
                reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data');
            }
        }
    });
</script>