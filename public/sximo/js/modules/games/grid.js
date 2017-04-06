/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl, siteUrl */ 
(function(){
    "use strict";    
    var modules = App.modules || (App.modules = {}),
        module = modules.games || (modules.games = {}),
        view = module.grid || (module.grid = {}),
        container,
        moduleName,
        url,
        forms,
        downloadForms,
        form;

    $(document).ready(function() {
        
    });

    view.init = function (options, data) {    
        options = options || {};
        data = data || {};
        moduleName = options.moduleName || pageModule;
        url = options.url || pageUrl;
        container = options.container || $(options.container);
        if (!container.length) {
            container = $("#"+moduleName+"Grid");
        }
        if (!container.length) {
            container = jQuery;
        }
        
        var exportForm = container.find('#mylocationgameExportFormAjax, .downloadForm');
        exportForm.on('submit', exportFormSubmit);              
    
    };
    
    function exportFormSubmit() {
        
        var form = $(this),
            allowDownload = form.data('allowDownload') || false,
            validate = form.find('[name=validateDownload]'),
            filtersField = form.find('[name=footerfiters]'),
            footerFilters,
            filters,
            options = {
                dataType     :  'json',
                error        : function () { 
                    hideProgress();
                    notyMessageError('Unable to export. Error processing data!');
                },
                success      :  function(data) {
                    hideProgress();
                    data = data || {};
                    var error = data.error || 'Unable to export. Data not found!';
                    if (data.success) {
                        validate.val(0);
                        form.data('allowDownload', true);
                        setAndProbeExportFormSessionTimeout(form);
                        form.find('.submitButton').click();
                    }
                    else {
                        notyMessageError(error);
                    }
                }
            };
        if (filtersField.length) {
            filters = getFooterFiltersWith({'search':true, 'sort': true, 'order': true});
            filtersField.val(filters);
        }
        if (!allowDownload) {
            showProgress();
            validate.val(1);
            form.ajaxSubmit(options);
            return false;   
        }        
        form.data('allowDownload', false);
    }
    function preProcessForm(arr, $form, options) {
        options = options || {};
        var elements = {
                'date_down': true,
                'date_up': true
            },
            deleteElms = {},
            idx,
            value, 
            newValue;

        for (idx = 0; idx < arr.length; idx++) {
            if (elements[arr[idx].name]) {
                value = new Date(arr[idx].value);
                if (value.valueOf()) {
                    newValue = $.datepicker.formatDate('yy-mm-dd', value) || '';
                    arr[idx].value = newValue;
                }                
            }
//            if (deleteElms[arr[idx].name]) {
//                arr.splice(idx, 1);
//            }             
        }

        //return false;
    }      
    function showFormResponse(data)  {

        hideProgress();
        if(data.status === 'success'){
            notyMessage(data.message);
            ajaxViewClose('#'+moduleName);
            $('#sximo-modal').modal('hide');
            $(".reloadDataButton").click();
        } 
        else {
            notyMessageError(data.message);
            return false;
        }
    }    
    function showProgress () {
        $('.ajaxLoading').show();
    }
    function hideProgress () {
        $('.ajaxLoading').hide();
    }    
    
}());
