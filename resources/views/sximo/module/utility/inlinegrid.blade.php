<script type="text/javascript">
  var editablerowscount =0;
     if(editablerowscount==0){
	  $('#rcv').hide();
   }

$(document).ready(function() {
	$('.editable').dblclick(function(){
        
        var hookParams = {'row': $(this), count: editablerowscount};
        if ($(this).hasClass('inline_edit_applied')) {
            return;
        }
        App.autoCallbacks.runCallback('inline.row.config.before', hookParams);
        
        editablerowscount++;
        hookParams.count = editablerowscount;
        
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
            var inputTemplateElement = $(this);
            var val = $(this).attr("data-form");
            var format = $(this).attr("data-form-type");
            if( val !== undefined && val !== 'action' ) 
            {
                
                var h = $(this).html();	

                $('#'+id+' td').each(function() {
                    var target = $(this).attr('data-field');
                    var values = $(this).attr('data-values');
                    var data_format = $(this).attr('data-format');
                    hookParams.config = { 'html': h, 'template': inputTemplateElement, 'field': null };                    
                    hookParams.cell = $(this);
                    if( target !== undefined && target !== 'action' && target == val)
                    {                        
                        App.autoCallbacks.runCallback('inline.cell.config.before', hookParams);
                        $(this).html(h);
                        hookParams.config.field = $(this).find('[name="'+target+'"]');                        
                        
                        if(format =='select'){
                            if($.isNumeric(values))
                            {
                                $('#'+id+' td select[name="'+target+'"] option[value="'+values+'"]').attr('selected','selected');
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
                        else if(format =='textarea' || format =='textarea')
                        {
                            $('#'+id+' td textarea[name="'+target+'"]').val(values);
                        }
                        else (format =='text')
                        {
                            $('#'+id+' td input[name="'+target+'"]').val(values);
                        } 
                        
                        App.autoCallbacks.runCallback('inline.cell.config.after', hookParams);
                    }

                });			

            } 
        });
        $('#'+id+' td .action').hide();
        $('#'+id+' td .actionopen').show();
        
        App.autoCallbacks.runCallback('inline.row.config.after', hookParams);
        
        initiateInlineFormFields($('#'+ id + ' td[data-field]'),"{{url()}}");
    });
});


function canceled( id ) 
{
    var hookParams = {'id': id, 'row': $("#"+id), count: editablerowscount};
    App.autoCallbacks.runCallback('inline.row.cancel.before', hookParams);

    $("#"+id).removeClass('inline_edit_applied');
    $('#'+id+' td .actionopen').hide();
    $('#'+id+' td').each(function() {           
        var val = $(this).attr("data-values");
        var value = $(this).attr("data-format");

        if( val !== undefined && val !== 'action' )
        {
            hookParams.cell = $(this);
            App.autoCallbacks.runCallback('inline.cell.cancel.before', hookParams);
            $(this).html(value);
            App.autoCallbacks.runCallback('inline.cell.cancel.after', hookParams);
        }		
    });


    $('#'+id+' td .action').show();
    editablerowscount--;
    hookParams.count = editablerowscount;
    displayEditableSaveButton();

    App.autoCallbacks.runCallback('inline.row.cancel.after', hookParams);
}

function displayEditableSaveButton() {
    if(editablerowscount > 0)
    {
        $('#rcv').show();
    }
    else if(editablerowscount == 0) {
        $('#rcv').hide();
    }
}


function saved( id )
{

    var row = $('#'+id),
        myId = id.split('-'),
        recordId = myId && myId[1],
        saveConfig = {
            xhr: null,
            data: $('#'+id+' td :input').serialize(),
            url: '{{$pageModule}}/save/'+ recordId,
            callback: function() {}
        },
        hookParams = {'id': recordId, 'row': row, 'config': saveConfig};

    App.autoCallbacks.runCallback('inline.row.save.before', hookParams);
    if (saveConfig.error) {
        notyMessageError(saveConfig.error);
        return false;
    }
    
    $('.ajaxLoading').show();	
    saveConfig.xhr = $.post(saveConfig.url, saveConfig.data, function( data ) {
        hookParams.data = data;
        if (saveConfig.callback && typeof saveConfig.callback == 'function') {
            saveConfig.callback(data, recordId, row);
        }
        if(data.status == 'success')
        {
            App.autoCallbacks.runCallback('inline.row.save.after', hookParams);
            $("#"+id).removeClass('inline_edit_applied');
            ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
            notyMessage(data.message);
            $('#'+id+' td .action').show();
            $('#'+id+' td .actionopen').hide();

        } else {
            App.autoCallbacks.runCallback('inline.row.save.error', hookParams);
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

  App.autoCallbacks.registerCallback('inline.cell.config.before', function (params) {
      //'row': row, 'cell':cell, config: { 'html': h, 'template': inputTemplateElement, 'field': null}, count: editablerowscount
      var cell = params.cell;
      cell.data('original-value-html', cell.html());
  });
  App.autoCallbacks.registerCallback('inline.cell.cancel.after', function (params) {
      var cell = params.cell,
              originalValue = cell.data('original-value-html');
      cell.html(originalValue);
  });
  App.autoCallbacks.registerCallback('inline.cell.config.after', function (params) {

      var  cell = params.cell,
              row = params.row,
              config = params.config,
              template = config.template,
              fieldName = cell.data('field'),
              fieldType = template.data('form-type'),
              originalValue = cell.data('values'),
              formattedValue = cell.data('format'),
              input = config.field;
      
      if(/datetime/.test(fieldType) && originalValue){
          formattedValue = $.datepicker.formatDate('mm/dd/yy hh:ii:ss', new Date(originalValue));
          input.val(formattedValue);
      }else if (/date/.test(fieldType) && originalValue) {
          formattedValue = $.datepicker.formatDate('mm/dd/yy', new Date(originalValue));
          input.val(formattedValue);
      }

  });
</script>