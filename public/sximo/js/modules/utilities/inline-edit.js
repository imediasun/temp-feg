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
        showFloatingCancelSave,
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
        if (event && event.preventDefault && typeof event.preventDefault == 'function') {
            event.preventDefault();
        }
        
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
            cell.css('height','auto');

            
        });
       var actionBtns= $(row).children('td[data-values="action"]').children('.action');
           actionBtns.css('padding-bottom',"0px");
           if(actionBtns.siblings('a').length > 0)
           {
               actionBtns.siblings('a').last().css('margin-bottom',"0px");
           }
           actionBtns.css('margin-bottom',"0px");
           row.removeClass('inline_edit_applied');
           row.nextAll('.inline_edit_applied').each(function(){
           var id=$(this).data('id');
           var height=$(this).offset().top+29;
               console.log($(this).children('td[data-values="action"]').children('.action').siblings('a'));
               if($(this).children('td[data-values="action"]').children('.action').siblings('a').length > 0)
               {
                   height=$(this).children('td[data-values="action"]').children('.action').siblings('a').last().offset().top+29;
                   console.log(height);
               }
           $('#divOverlay_'+id).css('top',height +"px");
        });
        rowHookParams.count = --editingRowsCount;
        displayInlineEditButtons(rowDomId, true);

        App.autoCallbacks.runCallback('inline.row.cancel.after', rowHookParams);       
        
        return false;

   };
    window.showFloatingCancelSave=showFloatingCancelSave=function(ele){
        var bottomWidth = $(ele).css('width');
        var bottomHeight = $(ele).css('height');
        var rowPos = $(ele).position();
        var id=$(ele).data('id');
        var $divOverlay = $('#divOverlay_'+id);

        if(id) {
            $("#divOverlay").attr('id', 'divOverlay_' + id);
        }
        var bottomTop;
        var rightSpace;
        var actionBtns= $(ele).children('td[data-values="action"]').children('.action');
        console.log(actionBtns.siblings('a').length);
        if(actionBtns.siblings('a').length == 0)
        {
            actionBtns.css('padding-bottom',"50px");
            bottomTop=actionBtns.offset().top+29;
            $divOverlay.css({
                position: 'absolute',
                visibility:'visible',
                top: bottomTop-4,
                right: window.location.pathname == '/shopfegrequeststore'?'162px':"47px",
                width: 'auto',
                height: '28px'
            });
        }
        else if(actionBtns.siblings('a').length == 3)
        {
            actionBtns.siblings('a').last().css('margin-bottom',"29px");
            bottomTop=actionBtns.siblings('a').last().offset().top+29;
            $divOverlay.css({
                position: 'absolute',
                visibility:'visible',
                top: bottomTop-4,
                right: "53px",
                width: 'auto',
                height: '28px'
            });
        }
        else
        {
            actionBtns.siblings('a').last().css('margin-bottom',"29px");
            bottomTop=actionBtns.siblings('a').last().offset().top+29;
            $divOverlay.css({
                position: 'absolute',
                visibility:'visible',
                top: bottomTop-4,
                right: "47px",
                width: 'auto',
                height: '28px'
            });
        }
        $divOverlay.delay(100).slideDown('fast');

    };
    window.saveInlineForm = saveInlineForm = function (rowDomId, event, element, options) {
        if (event && event.preventDefault && typeof event.preventDefault == 'function') {
            event.preventDefault();
        }
        options = options || {};
        var elm = element && $(element),
            row = container.find('#'+rowDomId),
            splitID = rowDomId.split('-'),
            dataID = splitID && splitID[1],
            callback = options.callback || function() {},
            saveConfig = {
                xhr: null,
                data: row.find('td :input').serialize(),
                url: moduleName + '/save/'+ dataID,
                callback: callback
            },
            hookParams = {'dataID': dataID, 'row': row, 'config': saveConfig};
    
        App.autoCallbacks.runCallback('inline.row.save.before', hookParams);
        if (saveConfig.error) {
            if (!options.isBulk) {
                notyMessageError(saveConfig.error);
            }            
            return false;
        }
        if (elm && elm.length) {
            elm.prop('disabled', true);
        }
        
        showProgress();
        saveConfig.xhr = $.post(saveConfig.url, saveConfig.data, function( data ) {
            if (!options.isBulk) {
                hideProgress();
            }            
            hookParams.data = data;
            if (callback && typeof callback == 'function') {
                callback(hookParams);
            }
            if(data.status == 'success')
            {
                displayInlineEditButtons(rowDomId, true);
                App.autoCallbacks.runCallback('inline.row.save.after', hookParams);
                row.removeClass('inline_edit_applied');
                if (!options.isBulk) {
                    notyMessage(data.message);
                    ajaxFilter('#' + moduleName, pageUrl + '/data');
                }                
            } 
            else {
                App.autoCallbacks.runCallback('inline.row.save.error', hookParams);
                if (!options.isBulk) {
                    notyMessageError(data.message);	
                }
                if (elm && elm.length) {
                    elm.prop('disabled', false);
                }                
            }
        }); 
        
        return saveConfig.xhr;
   };
    window.saveAllInlineForm = saveAllInlineForm = function (event, element) {
        if (event && event.preventDefault && typeof event.preventDefault == 'function') {
            event.preventDefault();
        }
        var elm = $(element),
            editedRows = container.find('.inline_edit_applied'),
            editedRowsCount = editedRows.length,
            saveRemaining = editedRowsCount,
            errorCount = 0,
            successCount = 0,
            nothingToSaveCount = 0,
            allSuccessMessage = "All data saved successfully",
            allErrorMessage = "Error in saving data!",
            finalMessage,
            hookParams = {'rows': editedRows, 
                            success: [], 
                            error: [], 
                            callbacks: [], 
                            nothingToSaveCount: 0,
                            saveRemaining: 0
                        },
            finalCallback = function () {
                --saveRemaining;
                hookParams.saveRemaining = saveRemaining;
                if (saveRemaining <= 0) {
                    
                    if (nothingToSaveCount >= editedRowsCount) {
                        hideProgress();
                        notyMessageError("Nothing to save!");
                        elm.prop('disabled', false);
                        return;
                    }          
                    
                    finalMessage = !errorCount ? allSuccessMessage : 
                            (!successCount ? allErrorMessage : 
                                ("Data saved successfully with some errors!"));
                    if (errorCount) {
                        App.autoCallbacks.runCallback('inline.rows.save.error', hookParams);
                        notyMessageError(finalMessage);
                        if (successCount) {
                            App.autoCallbacks.runCallback('inline.rows.save.after', hookParams);
                        }
                    }
                    else {
                        App.autoCallbacks.runCallback('inline.rows.save.after', hookParams);
                        notyMessage(finalMessage);
                    }
                    
                    hideProgress();
                    ajaxFilter('#' + moduleName, pageUrl + '/data');
                }                
            },
            callback = function (params) {
                var response = params.data, subCallback, subCallbackIndex;
                if (response.status === 'success') {
                    ++successCount;
                    hookParams.success.push(params);
                }
                else {
                    ++errorCount;
                    hookParams.error.push(params);
                }      
                if (hookParams.callbacks && hookParams.callbacks.length) {
                    for(subCallbackIndex in hookParams.callbacks) {
                        subCallback = hookParams.callbacks[subCallbackIndex];
                        if (subCallback && typeof subCallback === 'function') {
                            params = params || {};
                            params.hookParams = hookParams;
                            subCallback(params);
                        }
                    }
                }
                finalCallback();
            };
        
        if (editedRows.length) {
            App.autoCallbacks.runCallback('inline.rows.save.before', hookParams);

            elm.prop('disabled', true);
            editedRows.each(function(){
                var xhr = saveInlineForm($(this).attr('id'), null, null, {'callback': callback, 'isBulk': true});
                if (xhr === false) {
                    ++nothingToSaveCount;
                    hookParams.nothingToSaveCount = nothingToSaveCount;
                    finalCallback();
                }
            });             
        }
        else {
            notyMessageError("Nothing to save!");
        }

   };
    window.displayInlineEditButtons = displayInlineEditButtons = function (rowDomId, isHide) {
        var globalSaveButton = container.find('#rcv');
        if (!globalSaveButton.length) {
            globalSaveButton = $('<button id="rcv" onclick="saveAllInlineForm(event, this);" class="btn btn-sm btn-white" type="button"> Save </button>');
            container.find('.m-b .pull-right').prepend(globalSaveButton);
        }        
        if(editingRowsCount) {
            globalSaveButton.show();
        }
        else {
            globalSaveButton.hide();
        }
        if (isHide) {
            var rowid=$('#'+rowDomId).data('id');
            var actionInlineBtn="divOverlay_"+rowid;

            container.find('#'+actionInlineBtn).show();
           container.find('#'+actionInlineBtn).hide();
        }
        else {
           // container.find('#'+rowDomId+' td .action').hide();
            container.find('#'+actionInlineBtn).show();
        }
   };
   
    window.initiateInlineFormFields = initiateInlineFormFields = function (container, url, rowHookParams) {
        var cellsHookParams = $.extend({}, rowHookParams, {'cells': container});
        App.autoCallbacks.runCallback('inline.cells.config.before', cellsHookParams);
        $(container).css('height',$(container).height()+"px");
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

    $(window).resize(function() {
        resizeContent();
    });
    function resizeContent() {
        $(".editable").each(function(){
            var elm = $(this), id = elm.data('id');
            var height = elm.offset().top+28;
            $('#divOverlay_'+id).css({ top:height});
        });
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
