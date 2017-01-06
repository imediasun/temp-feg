<script type="text/javascript">
$(document).ready(function() {
	//$('.date').datepicker({format:'mm/dd/yyyy',autoClose:true});
	initiateSearchFormFields($('#form-0 td'));
	$('.editable').dblclick(function(){
		//alert('called');
			var id = $(this).attr("id");
			$('#form-0 td').each(function(){
				var val = $(this).attr("data-form");
				var format = $(this).attr("data-form-type");
				console.log('<---start--->');
				console.log(format);
				console.log('<---end--->');
				if( val !== undefined && val !== 'action' )
				{

					var h = $(this).html();	

					$('#'+id+' td').each(function() {
						var target = $(this).attr('data-field');
						var values = $(this).attr('data-values');
						var data_format = $(this).attr('data-format');
						if( target !== undefined && target !== 'action' && target == val)
						{
							$(this).html(h);
							if(format =='select'){
								if($.isNumeric(values))
								{
									$('#'+id+' td option[value="'+values+'"]').attr('selected','selected');
								}
								else if((/^[0-9]+-.*?$/).test(values))
								{
									var myval = values.split('-');
									$('#'+id+' td option[value="'+myval[0]+'"]').attr('selected','selected');
								}
								else
								{
									$('#'+id+' td select[name="'+target+'"] option').filter(function(){
										return this.text == values;
									}).prop('selected', true);
								}

							}
							else if(format == 'text_date')
							{
								$('#'+id+' td input[name="'+target+'"]').val(data_format);
								$('#'+id+' td input[name="'+target+'"]').datepicker('update');
							}
							else if(format == 'text_datetime')
							{
								//$('#'+id+' td input[name="'+target+'"]').remove();
								$('#'+id+' td input[name="'+target+'"]').val('01/13/2017 14:20:50');
								$('#'+id+' td input[name="'+target+'"]').datetimepicker('update').trigger('click');

							}
							else if(format =='textarea' || format =='textarea')
							{
								$('#'+id+' td textarea[name="'+target+'"]').val(values);
							}
							else (format =='text')
							{
								$('#'+id+' td input[name="'+target+'"]').val(values);
							} 
							
							
						}

					})			
									
				} 
			})
			$('#'+id+' .action').hide();
			$('#'+id+' .actionopen').show();

		})
});
function canceled( id )
{
	$('#'+id+' .actionopen').hide();
	$('#'+id+' td').each(function() {
		var val = $(this).attr("data-values");
		var value = $(this).attr("data-format");
		if( val !== undefined && val !== 'action' )
		{

			$(this).html(value);
		}		
	});
	$('#'+id+' .action').show();
}	
function saved( id )
{

	var datas = $('#'+id+' td :input').serialize();
	$('#'+id+' .action').show();
	$('.ajaxLoading').show();	
	$.post( '{{$pageModule}}/save' ,datas, function( data ) {
		if(data.status == 'success')
		{
			ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
			notyMessage(data.message);	
			
		} else {
			$('.ajaxLoading').hide();
			notyMessageError(data.message);	
			return false;
		}
	});	

	$('#'+id+' .actionopen').empty();
}	

</script>