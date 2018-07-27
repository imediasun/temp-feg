<?php
    $searchoperators = array(
//        "equal" => " = ",
        "bigger_equal" => " >= ",
        "smaller_equal" => " <= ",
        "smaller" => " < ",
        "bigger" => " > ",
        "not_null" => " ! Null ",
        "is_null" => " Null ",
        "like" => "Like",
        "between" => "BETWEEN",
    );
?>
<div >
<form id="{{$pageModule}}Search">
<table class="table search-table table-striped" id="advance-search">
	<tbody>
@foreach ($tableForm as $t)
	@if($t['search'] =='1')
        <?php
            $aso = isset($t['advancedsearchoperator']) ? $t['advancedsearchoperator'] :
                (strpos($t['type'], "date") !== false ? 'between' : 'equal');
        ?>
		<tr id="{{ $t['field'] }}" class="fieldsearch">
			<td width="80">{!! SiteHelpers::activeLang($t['label'], (isset($t['language']) ? $t['language'] : array())) !!} </td>
			
			<td width="100">
			    
			<select id="{{ $t['field']}}_operate" @if($t['type'] == 'select') disabled @endif class="form-control oper" name="operate" onchange="changeSearchOperator(this.value , '{{ $t['field']}}', this,'{{ $t['type'] }}')">
				<option value="equal"> = </option>
                @if($pageModule != "merchandisebudget" )
                    @if($t['type'] != 'select')
                        @foreach($searchoperators as $operatorValue => $operatorLabel )
                            <option value="{!!$operatorValue!!}"
                                    @if($operatorValue == $aso)
                                        selected="selected"
                                    @endif
                                    >{!!$operatorLabel!!}</option>
                        @endforeach
                    @endif
                @endif
			</select>
			
			</td>
			
			<td style="position: relative;" id="field_{{ $t['field']}}" width="60%">{!! SiteHelpers::transForm($t['field'] , $tableForm , false, '', $typeRestricted) !!}</td>

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
	/*if('{{ $pageModule }}'=='order') {
		$("#orderSearch .table #field_status_id select[name='status_id']").each(function () {
			$(this).append('<option  value="removed"> Removed</option>')
		});
	}*/
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
