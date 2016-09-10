<div class="row m-b">

<br/>
<<<<<<< HEAD
    <div class="col-md-6">
<div style="float:left">
        @if($access['is_add'] ==1)
=======
    <div class="col-md-4" style=" width:22% !important">
        	@if($access['is_add'] ==1)
>>>>>>> add6916751c291caba07399e19193de2f411720f
			{!! AjaxHelpers::buttonActionCreate($pageModule,$setting,"Get Freight Quote") !!}
			@endif
			<a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i> Search</a>
</div>
            <div style="display:inline-block;margin-left:3px;float:left;width:45%">
                <select class="form-control" id="status" style="display:inline">
                    <option disabled >Select Freight Type</option>
                    <option @if($selected_status == 'requested') selected @endif value="requested" selected>Requested Freight Quotes</option>
                    <option @if($selected_status == 'booked') selected @endif value="booked" >Booked Freight Quotes</option>
                    <option @if($selected_status == 'archive') selected @endif value="archive">Freight Order Archive</option>
                </select>
</div>
    </div>

</div>
<script>
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val());
    });
    $("#status").on('change',function(){
        var freight_type=$(this).val();
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?status='+freight_type+'&config_id='+$("#col-config").val());

    });
</script>