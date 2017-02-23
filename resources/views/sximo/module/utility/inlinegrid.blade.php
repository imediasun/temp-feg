<script type="text/javascript">
  var editablerowscount =0;
     if(editablerowscount==0){
	  $('#rcv').hide();
   }

	$(document).ready(function() {
	$('.editable').dblclick(function(){
		editablerowscount++;
        if(editablerowscount==1) {
            var isAccessAllowed="{{ $access['is_add'] }}";
            var isInlineEnable="{{ $setting['inline'] }}"
            if( isAccessAllowed =='1' && isInlineEnable=='true') {
                var newRow = jQuery('<button id="rcv" onclick="saveAll();" class="btn btn-sm btn-white"> Save </button>');
                jQuery('.m-b .pull-right').prepend(newRow);
            }
        }
    $(this).addClass('inline_edit_applied');
        displayEditableSaveButton();

		    var id = $(this).attr("id");
			$('#form-0 td').each(function(){
				var val = $(this).attr("data-form");
				var format = $(this).attr("data-form-type");
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
										return this.text.toLowerCase() == values.toLowerCase();
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
								$('#'+id+' td input[name="'+target+'"]').val(data_format);
								$('#'+id+' td input[name="'+target+'"]').datetimepicker('update');

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
            initiateInlineFormFields($('#'+ id + ' td'),"{{url()}}");
		});
});
function canceled( id )
{
    $("#"+id).removeClass('inline_edit_applied');
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
	editablerowscount--;

	displayEditableSaveButton();

}

  function displayEditableSaveButton() {
	  if(editablerowscount > 0)
	  {
		  $('#rcv').show();
	  }
	  else if(editablerowscount ==0) {
		  $('#rcv').hide();
	  }
  }


function saved( id )
{
    $("#"+id).removeClass('inline_edit_applied');
	var myId = id.split('-');
	var datas = $('#'+id+' td :input').serialize();
	$('#'+id+' .action').show();
	$('.ajaxLoading').show();	
	$.post( '{{$pageModule}}/save/'+myId[1] ,datas, function( data ) {
		if(data.status == 'success')
		{
			ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
			notyMessage(data.message);
			$('#'+id+' .actionopen').empty();
			
		} else {
			$('.ajaxLoading').hide();
			notyMessageError(data.message);	
			return false;
		}
	});
}

          function saveAll(){
              $('.inline_edit_applied').each(function(){
                  saved($(this).attr('id'));
              });
          }

</script>