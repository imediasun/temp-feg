<div >
<form id="{{$pageModule}}Search">
<table class="table search-table table-striped" id="advance-search">
	<tbody>
@foreach ($tableForm as $t)
	@if($t['search'] =='1')
		<tr id="{{ $t['field'] }}" class="fieldsearch">
			<td>{!! SiteHelpers::activeLang($t['label'], (isset($t['language']) ? $t['language'] : array())) !!} </td>
			<td width="120">
			<select id="{{ $t['field']}}_operate" @if($t['type'] == 'select') disabled @endif class="form-control oper" name="operate" onchange="changeSearchOperator(this.value , '{{ $t['field']}}', this,'{{ $t['type'] }}')">
				<option value="equal"> = </option>
                @if($pageModule != "merchandisebudget" )
                    @if($t['type'] != 'select')
				<option value="bigger_equal"> >= </option>
				<option value="smaller_equal"> <= </option>
				<option value="smaller"> < </option>
				<option value="bigger"> > </option>
				<option value="not_null"> ! Null  </option>
				<option value="is_null"> Null </option>
				<option value="between"> Between </option>
				<option value="like"> Like </option>
                    @endif
                    @endif

			</select>
			</td>
			<td id="field_{{ $t['field']}}" width="50%">{!! SiteHelpers::transForm($t['field'] , $tableForm) !!}</td>

		</tr>

	@endif
@endforeach
		<tr>
			<td colspan="3"><button type="button" name="search" class="doSearch btn btn-sm btn-primary pull-right"> Search </button></td>
		</tr>
	</tbody>
	</table>
</form>
</div>
<script>

jQuery(function(){

    initiateSearchFormFields($('#{{$pageModule}}Search'));
    renderDropdown($("select.select3 "), { width:"100%"});
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
