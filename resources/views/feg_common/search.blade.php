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
function changeOperate( val , field )
{
	if(val =='is_null') {
		$('input[name='+field+']').attr('readonly','1');
		$('input[name='+field+']').val('is_null');
	} else if(val =='not_null') {
		$('input[name='+field+']').attr('readonly','1');
		$('input[name='+field+']').val('not_null');		

	} else if(val =='between') {
	
		html = '<input name="'+field+'" class="date form-control" placeholder="Start Date" style="width:100px;"  /> -  <input name="'+field+'_end" class="date form-control"  placeholder="End Date" style="width:100px;"    />';
		$('#field_'+field+'').html(html);
	} else {
		$('input[name='+field+']').removeAttr('readonly');
		$('input[name='+field+']').val('');	
	}
}
jQuery(function(){
		$('.date').datepicker({format:'mm/dd/yyyy',autoClose:true})
		$('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
		//$(".sel-search").select2({ width:"98%"});	


	$('.doSearch').click(function(){
		//alert('test');
		var attr = '';
		$('#advance-search tr.fieldsearch').each(function(i){
			var field = $(this).attr('id');
			var operate = $(this).find('#'+field+'_operate').val();
			var value_select  = $(this).find("select[name="+field+"] option:selected").val();
			if( typeof value_select !=='undefined' )
			{
				value  = value_select;
			} else {
				value  = $(this).find("input[name="+field+"]").val();
				if(value != '')
					value  = $.datepicker.formatDate('mm/dd/yyyy', new Date(value));
			}

			if(value !=='' && typeof value !=='undefined' && this.name !='_token')
			{

				if(operate =='between')
				{
					var value  = $(this).find("input[name="+field+"]").val();
					var value2  = $(this).find("input[name="+field+"_end]").val();
					attr += field+':'+operate+':'+value+':'+value2+'|';
				} else {
					attr += field+':'+operate+':'+value+'|';
				}	
					
			}
			
		});
		<?php if($searchMode =='ajax') { ?> 
			reloadData( '#{{ $pageModule }}',"{{ $pageUrl }}/data?search="+attr);	
			$('#sximo-modal').modal('hide');
		<?php } else { ?>
			window.location.href = '{{ $pageUrl }}?search='+attr;
		<?php } ?>
	});
});

</script>
