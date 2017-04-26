<div class="row c-margin">
	<div class="col-md-9">
        
            @if($access['is_add'] ==1)
	   		<a href="{{ URL::to('core/users/update') }}" class="tips btn btn-sm btn-white"  title="{{ Lang::get('core.btn_create') }}">
			<i class="fa fa-plus-circle "></i>&nbsp;{{ Lang::get('core.btn_create') }}</a>
			@endif
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="SximoDelete();" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_remove') }}">
			<i class="fa fa-minus-circle "></i>&nbsp;{{ Lang::get('core.btn_remove') }}</a>
			@endif
			<a href="{{ URL::to( $pageUrl .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>
                @if(SiteHelpers::isModuleEnabled($pageModule))
                    <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Arrange Columns'); return false;" ><i class="fa fa-bars"></i> Arrange Columns</a>
                    @if(!empty($colconfigs))
                        <select class="form-control" style="width:auto!important;display:inline;" name="col-config"
                                id="col-config">
                            <option value="0">Select Column Arrangement</option>
                            @foreach( $colconfigs as $configs )
                                <option @if($config_id == $configs['config_id']) selected @endif 
                                    value={{ $configs['config_id'] }}> {{ $configs['config_name'] }}   </option>
                            @endforeach
                        </select>
                        @if(\Session::get('uid') ==  \SiteHelpers::getConfigOwner($config_id))
                            <a id="edit-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/edit') }}" class="btn btn-sm btn-white tips"
                               onclick="SximoModal(this.href,'Arrange Columns'); return false;" title="Edit Column Arrangement">  <i class="fa fa-pencil-square-o"></i></a>
                            <button id="delete-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/delete') }}" class="btn btn-sm btn-white tips" title="Delete Column Arrangement">  <i class="fa fa-trash-o"></i></button>
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
                    <a href="{{ URL::to( $pageUrl .'/export/excel?exportID='.uniqid('excel', true).'&return='.$return) }}" class="btn btn-sm btn-white" target="_blank"> Excel</a>
                @endif
                @if($isCSV)
                    <a href="{{ URL::to( $pageUrl .'/export/csv?exportID='.uniqid('csv', true).'&return='.$return) }}" class="btn btn-sm btn-white"  target="_blank"> CSV </a>
                @endif
                @if($isPDF)
                    <a href="{{ URL::to( $pageUrl .'/export/pdf?exportID='.uniqid('pdf', true).'&return='.$return) }}" class="btn btn-sm btn-white" target="_blank"> PDF</a>
                @endif
                @if($isWord)
                    <a href="{{ URL::to( $pageUrl .'/export/word?exportID='.uniqid('word', true).'&return='.$return) }}" class="btn btn-sm btn-white" target="_blank"> Word</a>
                @endif
                @if($isPrint)
                    <a href="{{ URL::to( $pageUrl .'/export/print?exportID='.uniqid('print', true).'&return='.$return) }}" class="btn btn-sm btn-white" onclick="ajaxPopupStatic(this.href); return false;" > Print</a>
                @endif
            </div>
        @endif
    </div>
	</div>
<script>
$(document).ready(function(){
    
    var simpleSearch = $('.simpleSearchContainer'),
        ajaxMode = false,
        config_id=$("#col-config").val();


	$('.tips').tooltip();
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue'
	});
	$('#{{ $pageModule }}Table .checkall').on('ifChecked',function(){
		$('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('check');
	});
	$('#{{ $pageModule }}Table .checkall').on('ifUnchecked',function(){
		$('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('uncheck');
	});
    
    
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageUrl }}/data?config_id='+$("#col-config").val()+ getFooterFilters());
    });
    
    if(config_id ==0 ) {
        $('#edit-cols,#delete-cols').hide();
    }
    else {
        $('#edit-cols,#delete-cols').show();
    }
    $('#delete-cols').click(function(){
        if(confirm('Are You Sure, You want to delete this Columns Arrangement?')) {
            
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
        $('.ajaxLoading').hide();
        if (data.status == 'success') {
            ajaxViewClose('#{{ $pageModule }}');
            ajaxFilter('#{{ $pageModule }}', '{{ $pageUrl }}/data');
            notyMessage(data.message);
            $('#sximo-modal').modal('hide');
        } else {
            notyMessageError(data.message);            
            return false;
        }
    }
    
    if (simpleSearch.length) {
        initiateSearchFormFields(simpleSearch);
        simpleSearch.find('.doSimpleSearch').click(function(event){
            performSimpleSearch.call($(this), {
                moduleID: '#{{ $pageModule }}',
                url: "{{ $pageUrl }}",
                event: event,
                ajaxSearch: ajaxMode,
                container: simpleSearch
            });
        });
    }

    initDataGrid('{{ $pageModule }}', '{{ $pageUrl }}', {useAjax: ajaxMode});
    
    updateNativeUIFieldsBasedOn();  
    makeSimpleSearchFieldsToInitiateSearchOnEnter();

});
</script>
