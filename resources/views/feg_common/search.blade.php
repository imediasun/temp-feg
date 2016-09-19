<div >
<form id="{{$pageModule}}Search">
<table class="table search-table table-striped" id="advance-search">
	<tbody>
@foreach ($tableForm as $t)
	@if($t['search'] =='1')
		<tr id="{{ $t['field'] }}" class="fieldsearch">
			<td>{!! SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) !!} </td>
			<td id="field_{{ $t['field']}}">{!! SiteHelpers::transForm($t['field'] , $tableForm) !!}</td>
			<input id="{{ $t['field']}}_operate" type="hidden" name="operate" value="equal" />
		</tr>
	@endif
@endforeach
		<tr>
			<td colspan="2"><button type="button" name="search" class="doSearch btn btn-sm btn-primary"> Search </button></td>
		</tr>
	</tbody>
	</table>
</form>	
</div>
<script>

jQuery(function(){
    
    initiateSearchFormFields($('#{{$pageModule}}Search'));

	$('.doSearch').click(function(event){
        var ajaxSerachMode = <?php echo $searchMode =='ajax' ?'true':'false';?>;
        $('#sximo-modal').modal('hide');
        performAdvancedSearch.call($(this), {
            moduleID: '#{{ $pageModule }}', 
            url: "{{ $pageUrl }}", 
            event: event,
            ajaxSearch: ajaxSerachMode,
            container: $("#advance-search")
        });
	});
});

</script>
