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

	$('.doSearch').click(function(){
		
		var attr = '', cache = {};
		$('#advance-search tr.fieldsearch').each(function(i){
			var UNDEFINED,                 
                container = this,
                jQcontainer = $(container),                
                field = jQcontainer.attr('id'),
                name = jQcontainer.attr('name'),                
                operatorField = jQcontainer.find('#'+field+'_operate'),
                operate = operatorField.val(),
                valueField = jQcontainer.find("[name="+field+"]"),
                value = valueField.val(),
                value2Field = jQcontainer.find("[name="+field+"_end]"),
                value2 = value2Field.val(),
                isValueDate = valueField.hasClass('date'),
                isValue2Date = value2Field.hasClass('date'),
                isValueDateTime = valueField.hasClass('datetime'),
                isValue2DateTime = value2Field.hasClass('datetime');
                
                if (value === null || value === UNDEFINED ) {
                    value = '';
                }
                if (value2 === null || value2 === UNDEFINED ) {
                    value2 = '';
                }
                if (field && name !='_token') {
                    cache[field] = {value:value, value2: value2, operator: operate};;            
                }                
				if(value && isValueDate) {
                    value  = $.datepicker.formatDate('yy-mm-dd', new Date(value));
                }                    
				if(value2 && isValue2Date) {
                    value2  = $.datepicker.formatDate('yy-mm-dd', new Date(value2));
                }                    
				if(value && isValueDateTime) {
                    //value  = $.datepicker.formatDate('mm/dd/yy hh:ii:ss', new Date(value));
                }                    
				if(value && isValue2DateTime) {
                    //value2  = $.datepicker.formatDate('mm/dd/yy hh:ii:ss', new Date(value2));
                }                    
					            
			if(value !=='' && typeof value !=='undefined' && name !='_token')
			{
				if(operate =='between')
				{
					attr += field+':'+operate+':'+value+':'+value2+'|';
				} else {
					attr += field+':'+operate+':'+value+'|';
				}	
					
			}
			
		});
        
        attr += getFooterFilters(true, true);
    
		<?php if($searchMode =='ajax') { ?>
            App.search.cache = cache;
			reloadData( '#{{ $pageModule }}',"{{ $pageUrl }}/data?search="+attr);	
			$('#sximo-modal').modal('hide');
		<?php } else { ?>
			window.location.href = '{{ $pageUrl }}?search='+attr;
		<?php } ?>
	});
});

</script>
