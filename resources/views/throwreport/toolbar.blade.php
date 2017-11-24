<div class="row c-margin">
	<div class="col-md-9">
			@if($access['is_add'] ==1)
{{--			{!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}--}}
			{{--<a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxCopy('#{{ $pageModule }}','{{ $pageUrl }}')"><i class="fa fa-file-o"></i> Copy </a>--}}
			@endif 
			@if($access['is_remove'] ==1)
			{{--<a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>--}}
			@endif
			{{--<a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>--}}
				<label>Week Date Range</label>
				<input type="text" class="weeklyDatePicker"  name ="weeklyDatePicker"  style="padding-bottom:5px" } />
		@if(SiteHelpers::isModuleEnabled($pageModule))
        <a id="edit-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white alignment-left-fixed floatnone" style="    margin-top: 3px !important;" onclick="SximoModal(this.href,'Arrange Columns'); return false;" ><i class="fa fa-bars"></i> Arrange Columns</a>
        @if(!empty($colconfigs))
        <select class="form-control alignment-left-fixed floatnone" style="width:auto!important;display:inline; top:2px !important;     margin-top: 3px !important;" name="col-config"
                id="col-config">
            <option value="0">Select Column Arrangement</option>
            @foreach( $colconfigs as $configs )
            <option @if($config_id == $configs['config_id']) selected
            @endif value={{ $configs['config_id'] }}> {{ $configs['config_name'] }}   </option>
            @endforeach
        </select>
                        @if(\Session::get('uid') ==  \SiteHelpers::getConfigOwner($config_id))
                            <a style="    margin-top: 3px !important;" id="edit-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/edit') }}" class="btn btn-sm btn-white tips alignment-left-fixed floatnone"
                               onclick="SximoModal(this.href,'Arrange Columns'); return false;" title="Edit column arrangement">  <i class="fa fa-pencil-square-o"></i></a>
                            <button style="    margin-top: 3px !important;" id="delete-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/delete') }}" class="btn btn-sm btn-white tips alignment-left-fixed floatnone" title="Delete column arrangement">  <i class="fa fa-trash-o"></i></button>
                        @endif
        @endif
        @endif
				<label>Week Number: {{ $setWeek }}</label>
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
					<a href="{{ URL::to( $pageModule .'/export/excel?exportID='.uniqid('excel', true).'&return='.$return) }}" class="btn btn-sm btn-white alignment-right-fixed"> Excel</a>
				@endif
				@if($isCSV)
					<a href="{{ URL::to( $pageModule .'/export/csv?exportID='.uniqid('csv', true).'&return='.$return) }}" class="btn btn-sm btn-white alignment-right-fixed"> CSV </a>
				@endif
				@if($isPDF)
					<a href="{{ URL::to( $pageModule .'/export/pdf?exportID='.uniqid('pdf', true).'&return='.$return) }}" class="btn btn-sm btn-white alignment-right-fixed"> PDF</a>
				@endif
				@if($isWord)
					<a href="{{ URL::to( $pageModule .'/export/word?exportID='.uniqid('word', true).'&return='.$return) }}" class="btn btn-sm btn-white alignment-right-fixed"> Word</a>
				@endif
				@if($isPrint)
					<a href="{{ URL::to( $pageModule .'/export/print?exportID='.uniqid('print', true).'&return='.$return) }}" class="btn btn-sm btn-white alignment-rightfixed" onclick="ajaxPopupStatic(this.href); return false;" > Print</a>
				@endif
			</div>
		@endif
	</div>
</div>

<style>
	.datepicker tr:hover {
		background-color: #808080;
	}


</style>
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
        if ($("#public").is(":checked")) {
                $('#groups').show();
            }
            else {
                $('#groups').hide();
            }
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
    $("#public,#private").change(function () {
        if ($("#public").is(":checked")) {
            $('#groups').show();
        }
        else {
            $('#groups').hide();
        }
    });
</script>
