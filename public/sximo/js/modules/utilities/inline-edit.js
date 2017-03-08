/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl, siteUrl */ 
/* App.modules.utilities.inlineEdit */
(function(){
    "use strict";    
    var $ = jQuery,
        modules = App.modules || (App.modules = {}),
        utilities = modules.utilities || (modules.utilities = {}),
        inlineEdit = utilities.inlineEdit || (utilities.inlineEdit = {}),
        container,
        moduleName,
        pageUrl,
        siteUrl,
        isInlineEnable,
        isAccessAllowed,
        today,
        todayDateTime,
        editableRows,
        editingRowsCount,
        configInlineEdit,
        cancelInlineEdit,
        displayInlineEditButtons,
        initiateInlineFormFields,
        saveInlineForm,
        saveAllInlineForm;
   
    inlineEdit.init = function (options, data) {    
        options = options || {};
        data = data || {};
        today = options.today;
        todayDateTime = options.todayDateTime;
        isInlineEnable = options.isInlineEnable;
        isAccessAllowed = options.isAccessAllowed;
        moduleName = options.moduleName;
        pageUrl = options.pageUrl;
        siteUrl = options.siteUrl;
        container = options.container || $(options.container);
        if (!container.length) {
            container = $("#"+moduleName+"Grid");
        }
        if (!container.length) {
            container = jQuery;
        }
    
        if (container && container.length) {
            configInlineEdit();
        }          
    };  
    
   configInlineEdit = function () {
    editableRows = container.find('.editable');
    editingRowsCount = 0;
    
	editableRows.dblclick(function() {
        
        var row = $(this),
            rowDomId = row.attr("id"),
            editActionButtons = $('#'+rowDomId+' td .actionopen'),
            generalActionButtons = $('#'+rowDomId+' td .actionopen'),
            rowHookParams = {'row': row, count: editingRowsCount};
            
        if (row.hasClass('inline_edit_applied')) {
            return;
        }
        
        App.autoCallbacks.runCallback('inline.row.config.before', rowHookParams);        
        row.addClass('inline_edit_applied');
        rowHookParams.count = ++editingRowsCount;

        container.find('#form-0 td[data-form]').each(function(){
            var cellHookParams = $.extend({}, rowHookParams, { config: {}}),
                config = cellHookParams.config,
                inputTemplateCell = config.inputTemplateCell = $(this),
                inputTemplateElement = config.inputTemplateElement = inputTemplateCell.find(":input"),
                inputHTML = config.inputHTML = inputTemplateCell.html(),
                fieldName = config.fieldName = inputTemplateCell.data('form'),
                inputType = config.inputType = inputTemplateCell.data("form-type"),
                dateFormats = {'text_date': 'MM/DD/YYYY', 'text_datetime': 'MM/DD/YYYY hh:mm:ss A'},
                cell,
                input,
                value,
                cellOriginalHTML,
                cellOriginalDomElements,
                originalValue,
                formattedValue;
                
                        
            if (!fieldName || fieldName == 'action' || !inputTemplateElement.length || !inputType) {
                return;
            }                
            cell = cellHookParams.cell = container.find('#'+rowDomId+' td[data-field="'+fieldName+'"]');
            if (!cell.length) {
                return;
            }
            originalValue = config.originalValue = cell.data('values');
            formattedValue = config.formattedValue = cell.data('format');
            cellOriginalHTML = config.originalHtmlValue = cell.html();
            App.autoCallbacks.runCallback('inline.cell.config.before', cellHookParams);
            
            cell.data('original-value-html', cellOriginalHTML);
            cell.data('original-value-elments', cellOriginalDomElements);
            input = config.input  || (config.input = $(config.inputHTML));
            cell.html("");
            cell.append(input);            
            cell.attr('data-edit', 1);
            
            switch (inputType) {
                case 'text_date':
                case 'text_datetime':
                    value = moment(originalValue);
                    formattedValue = value.isValid() ? value.format(dateFormats[inputType]) : '';
                    input.val(formattedValue);                    
                    input.val(formattedValue);
                    input.attr('data-today', today);
                    input.attr('data-today-datetime', todayDateTime);
                    break;
                case 'select':
                    if ($.isNumeric(originalValue)) {
                        input.val(originalValue);
                    }
                    else if ((/^[0-9]+-.*?$/).test(originalValue)) {
                        input.val((''+originalValue).split('-')[0]);
                    }
                    else {
                        input.val(originalValue);
                        if (input.val() == '') {
                            input.find('option').filter(function(){
                                    return this.text.toLowerCase() == 
                                            originalValue.toLowerCase();
                            }).prop('selected', true);
                        }                        
                    }
                    break;
                default: 
                    input.val(originalValue);
            }
        /*
            if(inputType =='select'){
                if($.isNumeric(values))
                {
                    $('#'+rowDomId+' td select[name="'+fieldName+'"] option[value="'+values+'"]').attr('selected','selected');
                }
                else if((/^[0-9]+-.*?$/).test(values))
                {
                    var myval = values.split('-');
                    $('#'+rowDomId+' td option[value="'+myval[0]+'"]').attr('selected','selected');
                }
                else
                {
                    $('#'+rowDomId+' td select[name="'+fieldName+'"] option').filter(function(){
                            return this.text.toLowerCase() == values.toLowerCase();
                    }).prop('selected', true);
                }

            }

            else if(inputType == 'text_date')
            {
                $('#'+rowDomId+' td input[name="'+fieldName+'"]').val(data_format);
                //$('#'+rowDomId+' td input[name="'+fieldName+'"]').datepicker('update');
            }
            else if(inputType == 'text_datetime')
            {
                $('#'+rowDomId+' td input[name="'+fieldName+'"]').val(data_format);
                //$('#'+rowDomId+' td input[name="'+fieldName+'"]').datetimepicker('update');

            }
            else if(inputType =='textarea')
            {
                $('#'+rowDomId+' td textarea[name="'+fieldName+'"]').val(values);
            }
            else (inputType =='text')
            {
                $('#'+rowDomId+' td input[name="'+fieldName+'"]').val(values);
            } 
          */              
            App.autoCallbacks.runCallback('inline.cell.config.after', cellHookParams);

        });
        displayInlineEditButtons(rowDomId);

        App.autoCallbacks.runCallback('inline.row.config.after', rowHookParams);
        
        initiateInlineFormFields($('#'+ rowDomId + ' td[data-edit=1]'), siteUrl, rowHookParams);
    });       
   };
   
    window.cancelInlineEdit = cancelInlineEdit = function (rowDomId, event, element) {
        
        event.preventDefault();
        var row = container.find("#"+rowDomId),
            cells = row.find('td[data-edit=1]'), 
            rowHookParams = {'row': row, count: editingRowsCount, cells: cells};
    
        App.autoCallbacks.runCallback('inline.row.cancel.before', rowHookParams);

        cells.each(function() {   
            var cellHookParams = $.extend({}, rowHookParams, {cells: null, config: {}}),
                config = cellHookParams.config,
                cell = cellHookParams.cell = $(this),
                cellOriginalValue = config.originalValue = cell.data('values'),
                cellFormattedValue = config.formattedValue = cell.data('format'),
                cellOriginalHTML = config.originalHtmlValue = cell.data('original-value-html'),
                cellOriginalDomElements = config.cellOriginalValue = cell.data('original-value-elments');
                
            if(cellOriginalValue !== undefined && cellOriginalValue !== 'action' )
            {
                App.autoCallbacks.runCallback('inline.cell.cancel.before', cellHookParams);
                $(this).html(config.originalHtmlValue);
                App.autoCallbacks.runCallback('inline.cell.cancel.after', cellHookParams);
            }
            cell.attr('data-edit', null);
            
        });
        
        row.removeClass('inline_edit_applied');

        rowHookParams.count = --editingRowsCount;
        displayInlineEditButtons(rowDomId, true);

        App.autoCallbacks.runCallback('inline.row.cancel.after', rowHookParams);       
        
        return false;

   };
    window.saveInlineForm = saveInlineForm = function (rowDomId, event, element) {
        event.preventDefault();
        
        var row = container.find('#'+rowDomId),
            splitID = rowDomId.split('-'),
            dataID = splitID && splitID[1],
            saveConfig = {
                xhr: null,
                data: row.find('td :input').serialize(),
                url: moduleName + '/save/'+ dataID,
                callback: function() {}
            },
            hookParams = {'dataID': dataID, 'row': row, 'config': saveConfig};

        App.autoCallbacks.runCallback('inline.row.save.before', hookParams);
        if (saveConfig.error) {
            notyMessageError(saveConfig.error);
            return false;
        }

        showProgress();
        saveConfig.xhr = $.post(saveConfig.url, saveConfig.data, function( data ) {
            hideProgress();
            hookParams.data = data;
            if (saveConfig.callback && typeof saveConfig.callback == 'function') {
                saveConfig.callback(data, dataID, row);
            }
            if(data.status == 'success')
            {
                displayInlineEditButtons(rowDomId, true);
                notyMessage(data.message);
                App.autoCallbacks.runCallback('inline.row.save.after', hookParams);
                row.removeClass('inline_edit_applied');
                ajaxFilter('#' + moduleName, pageUrl + '/data');
            } 
            else {
                App.autoCallbacks.runCallback('inline.row.save.error', hookParams);
                notyMessageError(data.message);	
            }
        }); 
        
        return false;
   };
    window.saveAllInlineForm = saveAllInlineForm = function () {
        container('.inline_edit_applied').each(function(){
            saveInlineForm($(this).attr('id'));
        });       
   };
    window.displayInlineEditButtons = displayInlineEditButtons = function (rowDomId, isHide) {
        var globalSaveButton = container.find('#rcv');
        if (!globalSaveButton.length) {
            globalSaveButton = $('<button id="rcv" onclick="saveAll();" class="btn btn-sm btn-white"> Save </button>');
            container.find('.m-b .pull-right').prepend(globalSaveButton);
        }        
        if(editingRowsCount) {
            globalSaveButton.show();
        }
        else {
            globalSaveButton.hide();
        }
        if (isHide) {
            container.find('#'+rowDomId+' td .action').show();
            container.find('#'+rowDomId+' td .actionopen').hide();            
        }
        else {
            container.find('#'+rowDomId+' td .action').hide();
            container.find('#'+rowDomId+' td .actionopen').show();
        }
   };
   
    window.initiateInlineFormFields = initiateInlineFormFields = function (container, url, rowHookParams) {
        var cellsHookParams = $.extend({}, rowHookParams, {'cells': container});
        App.autoCallbacks.runCallback('inline.cells.config.before', cellsHookParams);

        container.find('.date').datepicker({format:'mm/dd/yyyy', autoclose: true});
        container.find('.datetime').datetimepicker({format: 'mm/dd/yyyy HH:ii:ss P', autoclose: true});

        renderDropdown(container.find('.sel-inline'),{width:"98%"});
        container.find('.prod_type_id').change(function(){
            var elm = $(this),
                value = elm.val(),
                productSubTypeComboDataUrl = url + "/product/comboselect?filter=product_type:id:type_description:request_type_id:" + value,
                productSubTypeInput = cellsHookParams.row.find('select.prod_sub_type_id:first');
            
            productSubTypeInput.jCombo(productSubTypeComboDataUrl);
            productSubTypeInput.select2('destroy');
            renderDropdown(productSubTypeInput,{width:"98%"});
        });

        App.autoCallbacks.runCallback('inline.cells.config.after', cellsHookParams);
    };   

    function showProgress () {
        $('.ajaxLoading').show();
    }
    function hideProgress () {
        $('.ajaxLoading').hide();
    }     
}());

App.autoCallbacks.registerCallback('inline.cell.config.before', function (params) {
    //'row': row, 'cell':cell, config: { 'html': h, 'template': inputTemplateElement, 'field': null}, count: editingRowsCount
//    var cell = params.cell;
//    cell.data('original-value-html', cell.html());
});
App.autoCallbacks.registerCallback('inline.cell.cancel.after', function (params) {
//    var cell = params.cell,
//            originalValue = cell.data('original-value-html');
//    cell.html(originalValue);
});

App.autoCallbacks.registerCallback('inline.cell.config.after', function (params) {
//
//    var  cell = params.cell,
//            config = params.config,
//            inputTemplateCell = config.inputTemplateCell,
//            dateFormats = {'text_date': 'MM/DD/YYYY', 'text_datetime': 'MM/DD/YYYY hh:mm:ss A'},
//            fieldType = inputTemplateCell.data('form-type'),
//            originalValue = cell.data('values'),
//            formattedValue = cell.data('format'),
//            value,
//            input = config.input;
//
//    if(/date/.test(fieldType) && originalValue){
//        value = moment(originalValue);
//        formattedValue = value.isValid() ? value.format(dateFormats[fieldType]) : '';
//        input.val(formattedValue);
//    }

});
