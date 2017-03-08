/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl */ 
(function(){
    "use strict";    
   
   App.autoCallbacks.registerCallback('inline.row.config.before', function (params) {
       
   });
   App.autoCallbacks.registerCallback('inline.cell.config.before', function (params) {
       
   });
   App.autoCallbacks.registerCallback('inline.cell.config.after', function (params) {
       //'row': row, 'cell':cell, config: { 'html': h, 'template': inputTemplateElement, 'field': null}, count: editablerowscount 
       var  cell = params.cell,
            row = params.row,
            config = params.config,            
            inputTemplateCell = config.inputTemplateCell,
            fieldName = cell.data('field'),            
            fieldType = inputTemplateCell.data('form-type'),            
            originalValue = cell.data('values'),
            formattedValue = cell.data('format'),
            input = config.input;

//        if (!input.val()) {
//            input.val(originalValue);
//        }
//        if (/date/.test(fieldType) && originalValue) {
//            originalValue = formattedValue;
//            input.val(originalValue);
//        }
                
        input.on('change', function (e){            
            var val = input.val();            
            if (val != originalValue) {
                input.attr('data-dirty', 1);
                input.attr('data-touched', 1);
            }
            else {
                input.attr('data-dirty', 0);
                input.attr('data-touched', 1);                
            }                        
        });       
   });    
   App.autoCallbacks.registerCallback('inline.row.config.after', function (params) {
     
   });

   App.autoCallbacks.registerCallback('inline.cells.config.before', function (params) {
       var  allCells = params.cells,
            statusCell = allCells.filter('[data-field=Status]'), 
            statusInput = statusCell.find(':input'), 
            closedDateCell = allCells.filter('[data-field=closed]'), 
            closedDateInput = closedDateCell.find(':input'), 
            bothInputs = statusInput.add(closedDateInput),
            statusOValue = statusCell.data('values'),
            statusCValue = statusOValue,
            closedDateOValue = closedDateCell.data('format'),
            closedDateCValue = closedDateOValue || closedDateInput.data('today'),
            row = statusCell.parent();
        
        bothInputs.on('change', function (e){
            var elm = $(this),
                fieldName = elm.closest('td').data('field'),
                sVal = statusInput.val(), 
                dVal = fieldName=='closed' ? closedDateInput.val() : 
                    (statusOValue=='closed' ? (closedDateInput.val() || closedDateCValue) : '');
            if (sVal != statusOValue || dVal != closedDateOValue) {
                bothInputs.attr('data-dirty', 1).attr('data-touched', 1);
            }
            else {
                bothInputs.attr('data-dirty', 0).attr('data-touched', 1);
            }
            if (fieldName == 'closed') {
                closedDateCValue = dVal;
            }
            if (fieldName == 'Status') {
                if (sVal != 'closed') {
                    closedDateInput.val('');
                }
                else {
                    closedDateInput.val(closedDateCValue);
                }
                closedDateInput.prop('disabled', sVal != 'closed');
            }
        });
        
        closedDateInput.prop('disabled', statusOValue != 'closed');
            
   });   
   App.autoCallbacks.registerCallback('inline.cells.config.after', function (params) {
       
   });
   
   App.autoCallbacks.registerCallback('inline.row.cancel.before', function (params) {
       
   });
   App.autoCallbacks.registerCallback('inline.cell.cancel.before', function (params) {
       
   });
   App.autoCallbacks.registerCallback('inline.cell.cancel.after', function (params) {
       
   });
   App.autoCallbacks.registerCallback('inline.row.cancel.after', function (params) {
       
   });
   
   
   App.autoCallbacks.registerCallback('inline.row.save.before', function (params) {
       var config = params.config,
           formElements = params.row.find(':input[data-dirty=1]'),
           formData = formElements.serialize();
        params.config.data = formData;
        params.config.url = params.config.url.replace('/save/', '/save-inline/');
        
        if (!formData) {
            params.config.error = "Nothing to save!";
        }
   });
   App.autoCallbacks.registerCallback('inline.row.save.after', function (params) {
       
   });
   App.autoCallbacks.registerCallback('inline.row.save.error', function (params) {
       
   });

}());
