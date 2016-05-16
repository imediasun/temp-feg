<div class="row m-b">
	<div class="col-md-8">
			@if($access['is_add'] ==1)
			{!! AjaxHelpers::buttonActionCreate($pageModule,$setting,"Upload Image") !!}
			@endif
    </div>
</div>
<script>
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val());
    });
</script>