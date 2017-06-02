<div class="row c-margin">
    <div class="col-md-8">
        	@if($access['is_add'] ==1)
			{!! AjaxHelpers::buttonActionCreate($pageModule,$setting,"Get Freight Quote") !!}
			@endif
            @if($access['is_remove'] ==1)
                <a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
            @endif
			<a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>
                <select class="form-control" id="status" style="display:inline;width:auto;position:relative;top:1px;">
                    <option @if($selected_status == 'requested') selected @endif value="requested" selected>Requested Freight Quotes</option>
                    <option @if($selected_status == 'booked') selected @endif value="booked" >Booked Freight Quotes</option>
                    <option @if($selected_status == 'archive') selected @endif value="archive">Freight Order Archive</option>
                </select>
    </div>
</div>

<script>
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val()+ getFooterFilters());
    });
    $("#status").on('change',function(){
        var freight_type=$(this).val();
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?status='+freight_type+'&config_id='+$("#col-config").val()+ getFooterFilters());

    });
</script>