<div class="row c-margin">
	<div class="col-md-9">
        <div class="float-margin">
        @if($access['is_add'] ==1)
            {!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}
        @endif
        </div>
        @if($setting['disableactioncheckbox']=='false')
        @if($access['is_add'] ==1)
        <a href="javascript://ajax" class="btn btn-sm btn-white float-margin" onclick="ajaxCopy('#{{ $pageModule }}','{{ $pageUrl }}')"><i class="fa fa-file-o"></i> Copy </a>
        @endif
        @if($access['is_remove'] ==1)
        <a href="javascript://ajax" class="btn btn-sm btn-white float-margin" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
        @endif
        @endif
        <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white float-margin" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>
        @if(SiteHelpers::isModuleEnabled($pageModule))
        <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white float-margin" onclick="SximoModal(this.href,'Arrange Columns'); return false;" ><i class="fa fa-bars"></i> Arrange Columns</a>
        @if(!empty($colconfigs))
        <select class="form-control height-set float-margin" style="width:auto!important;display:inline;" name="col-config"
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
            <button id="delete-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/delete') }}" class="btn btn-sm btn-white tips" title="Delete column arrangement">  <i class="fa fa-trash-o"></i></button>
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
    $(document).ready(function(){
        var config_id=$("#col-config").val();
        if(config_id ==0 )
        {
            $('#edit-cols,#delete-cols').hide();
        }
        else
        {
            $('#edit-cols,#delete-cols').show();
        }
    });
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val() + getFooterFilters());
    });
</script>
