/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl */ 
(function(){
    "use strict";    
    var modules = App.modules || (App.modules = {}),
        module = modules.games || (modules.games = {}),
        view = module.grid || (module.grid = {}),
        container,
        moduleName,
        url,
        forms,
        form;

    $(document).ready(function() {
        App.modules.games.grid.init({
                'container': $('#'+pageModule+'Grid'),
                'moduleName': pageModule,
                'url': pageUrl
            }
        );
        
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
        
        var exportForm = container.find('#mylocationgameFormAjax');
            //downloadForms = container.find('#mylocationgameFormAjax')
        exportForm.on('submit', exportFormSubmit);              
    
    };
    
    function exportFormSubmit() {
        
        var form = $(this),
            validate = form.find('[name=validateDownload]'),
            needsValidation = validate.val() == 1,
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
                        form.find('#submit').click();
                    }
                    else {
                        notyMessageError(error);
                    }
                }
            };
        if (needsValidation) {
            showProgress();                
            form.ajaxSubmit(options);
            return false;   
        }
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
