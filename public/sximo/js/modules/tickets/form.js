/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl */ 
(function(){
    "use strict";    
    var modules = App.modules || (App.modules = {}),
        module = modules.tickets || (modules.tickets = {}),
        view = module.formView || (module.formView = {}),
        container,
        moduleName,
        mainModuleName,
        url,
        mainUrl,
        form;
   
    view.init = function (options, data) {    
        options = options || {};
        data = data || {};
        moduleName = options.moduleName || pageModule;
        mainModuleName = options.mainModuleName || pageModule;
        url = options.url || pageUrl;
        mainUrl = options.mainUrl || pageUrl;
        container = options.container || $(options.container);
        if (!container.length) {
            container = $("#"+moduleName+"View");
        }
        if (!container.length) {
            container = jQuery;
        }
        form = container.find('#servicerequestsFormAjax');
            
        if (container && container.length) {
            
            container.find('.editor').summernote();
            container.find('.previewImage').fancybox();
            container.find('.tips').tooltip(); 
            container.find('.removeCurrentFiles').on('click',function(){
                var removeUrl = $(this).attr('href');
                $.get(removeUrl,function(response){});
                $(this).parent('div').empty();
                return false;
            });
            
            renderDropdown(container.find(".select2, .select3, .select4, .select5"), { width:"100%"});
            container.find('.date').datepicker({format:'mm/dd/yyyy',autoClose:true});
            container.find('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
            
            container.find('input[type="checkbox"],input[type="radio"]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            })
            .on('ifToggled', function(){
                var elm = $(this), 
                    proxyInputName = elm.data('proxy-input'),
                    proxyInput = elm.closest('.form-group')
                            .find('input:hidden[name^='+proxyInputName+']'),
                    isChecked = elm.prop('checked'),
                    checkedValue = 1*isChecked;
                elm.val(checkedValue);
                if (proxyInput.length) {
                    proxyInput.val(checkedValue);
                }
            });   
            
            
        }
        
        form.parsley();
        form.on('submit', function(){

            if(form.parsley('isValid')){
                var options = {
                    dataType:      'json',
                    beforeSubmit :  preProcessForm,
                    success:       showFormResponse
                }
                showProgress();
                $(this).ajaxSubmit(options);
                return false;

            } else {
                return false;
            }

        });            
    };  
    
    function preProcessForm(arr, $form, options) {
        options = options || {};
        var dateElements = {
                'date_sold': true
            },
            deleteElms = {
                "_test_piece": true,
                "_for_sale": true,
                "_sale_pending": true,
                "_not_debit": true,
                "_sold": true,
            },
            idx,
            value, 
            newValue;

        for (idx = 0; idx < arr.length; idx++) {
            if (dateElements[arr[idx].name]) {
                value = new Date(arr[idx].value);
                if (value.valueOf()) {
                    newValue = $.datepicker.formatDate('yy-mm-dd', value) || '';
                    arr[idx].value = newValue;
                }                
            }
            if (deleteElms[arr[idx].name]) {
                arr.splice(idx, 1);
            }             
        }

        //return false;
    } 
    function showProgress () {
        $('.ajaxLoading').show();
    }
    function hideProgress () {
        $('.ajaxLoading').hide();
    }

    function showFormResponse(data)  {

        hideProgress();
        if(data.status === 'success'){
            ajaxViewClose('#'+moduleName);
            notyMessage(data.message);
            $('#sximo-modal').modal('hide');
            $(".reloadDataButton").click();
        } 
        else {
            notyMessageError(data.message);
            return false;
        }
    }
    
}());
