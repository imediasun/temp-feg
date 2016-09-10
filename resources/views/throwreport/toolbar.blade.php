<div class="row m-b">
	<div class="col-md-8">
			@if($access['is_add'] ==1)
			{!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}
			<a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxCopy('#{{ $pageModule }}','{{ $pageUrl }}')"><i class="fa fa-file-o"></i> Copy </a>
			@endif 
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
			@endif 	
			<a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i> Search</a>
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




				<input type="text" id="weeklyDatePicker"  name ="weeklydate"  style="padding-bottom:5px" />






    </div>


	<div class="col-md-4 ">
		@if($access['is_excel'] ==1)
		<div class="pull-right">
			<a href="{{ URL::to( $pageModule .'/export/excel?return='.$return) }}" class="btn btn-sm btn-white"> Excel</a>
			<a href="{{ URL::to( $pageModule .'/export/word?return='.$return) }}" class="btn btn-sm btn-white"> Word </a>
			<a href="{{ URL::to( $pageModule .'/export/csv?return='.$return) }}" class="btn btn-sm btn-white"> CSV </a>
			<a href="{{ URL::to( $pageModule .'/export/print?return='.$return) }}" class="btn btn-sm btn-white" onclick="ajaxPopupStatic(this.href); return false;" > Print</a>
		</div>	
		@endif
	</div>
</div>



{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>--}}
<script src="https://cdn.jsdelivr.net/momentjs/2.10.6/moment.min.js"></script>

<script>

	$(document).ready(function(){

		//Initialize the datePicker(I have taken format as mm-dd-yyyy, you can     //have your owh)
		$("#weeklyDatePicker").datetimepicker({
			format: 'MM/DD/YYYY'
		});

		//Get the value of Start and End of Week
		$('#weeklyDatePicker').on('dp.change', function (e) {
			alert('oki');
			var value = $("#weeklyDatePicker").val();
			alert(value);
			var firstDate = moment(value, "MM/DD/YYYY").day(0).format("MM/DD/YYYY");
			var lastDate =  moment(value, "MM/DD/YYYY").day(6).format("MM/DD/YYYY");
			$("#weeklyDatePicker").val(firstDate + " - " + lastDate);
		});

    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val());
    });

	});


</script>