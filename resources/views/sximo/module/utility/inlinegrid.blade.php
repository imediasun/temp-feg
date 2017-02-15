<script type="text/javascript">
  var editablerowscount =0;
     if(editablerowscount==0){
	  $('#rcv').hide();
   }

	$(document).ready(function() {


//$('.date').datepicker({format:'mm/dd/yyyy',autoClose:true});
	//initiateInlineFormFields($('#form-0 td'));
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
		Displayeditablesavebutton();
			var id = $(this).attr("id");
			//console.log("======"+id+"======");
			$('#form-0 td').each(function(){
				var val = $(this).attr("data-form");
				var format = $(this).attr("data-form-type");
				//console.log(format+"===="+val);
				if( val !== undefined && val !== 'action' )
				{

					var h = $(this).html();	

					$('#'+id+' td').each(function() {
						//console.log($(this));

						var target = $(this).attr('data-field');
						var values = $(this).attr('data-values');
						var data_format = $(this).attr('data-format');
						//console.log(target+"===="+values+"===="+data_format);
						//console.log("=====");
						if( target !== undefined && target !== 'action' && target == val)
						{
							$(this).html(h);

							if(format =='select'){
								//console.log($('#'+id+' td select[name="'+target+'"]'));
								//var data = $('#'+id+' td select[name="'+target+'"]').select2('data');
								//console.log('#'+id+' td select[name="'+target+'"]');
								//var data = $('#'+id+' td select[name="'+target+'"]').select2();
								//console.log(data);
								//$('#'+id+' td select[name="'+target+'"]').empty().select2($.extend(data, { data: data }));
								//$('#'+id+' td select[name="'+target+'"]').html( data );
								//$('#'+id+' td select[name="'+target+'"]').select2({});
								//$('#'+id+' td select[name="'+target+'"]').html();
								//$('#'+id+' td select[name="'+target+'"]').select({data: data});
								//console.log(JSON.stringify(data));
								//$('#'+id+' td select[name="'+target+'"]').select2('destroy').empty().select2({data: data});
								//$('#'+id+' td select[name="'+target+'"]').select2('destroy').empty().select2({data: [data]});;

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
			//console.log(id);
		//$('#'+ id + ' td').find('.sel-inline').select2('destroy');
		//var data = $('#'+ id + ' td').find('.sel-inline').data('select2');
		//$('#'+ id + ' td').find('.sel-inline').select2('destroy').empty().select2(data);
		//$('#'+ id + ' td').find('.sel-inline').select2('destroy').empty().select2({data: [{id: 1, text: 'new text'}]})
		//var data = $('#'+ id + ' td').find('.sel-inline').data('select2');
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

	 RemoveButton();

}
  function RemoveButton() {
	  editablerowscount--;
	  if(editablerowscount==0){
		  $('#rcv').hide();
	  }
  }
  function Displayeditablesavebutton() {
	  if(editablerowscount > 0)
	  {
		  $('#rcv').show();
	  }
	  else if(editablerowscount < 0) {
		  $('#rcv').hide();
	  }
  }


function saved( id )
{
    $("#"+id).removeClass('inline_edit_applied');
	var myId = id.split('-');
	var datas = $('#'+id+' td :input').serialize();
	console.log(JSON.stringify(datas));
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