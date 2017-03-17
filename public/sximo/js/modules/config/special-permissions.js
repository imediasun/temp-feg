/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl, siteUrl */ 
(function(){
    "use strict";    
    var modules = App.modules || (App.modules = {}),
        module = modules.specialPermissions || (modules.specialPermissions = {}),
        view = module.grid || (module.grid = {}),
        container,
        moduleName,
        moduleId,
        permissionData,
        grid,
        form;
   
    view.init = function (options, data) {    
        options = options || {};
        data = data || {};
        moduleName = options.moduleName;
        moduleId = options.moduleId;
        container = options.container || $(options.container);
        if (!container.length) {
            container = jQuery;
        }
        form = container.find('.gridForm');
        permissionData = data.permissions;
        grid = data.grid;
    
        if (container && container.length) {
            
            container.find('.tips').tooltip(); 
            renderDropdown(container.find(".sel-inline, .select2, .select3, .select4, .select5"), { width:"100%"});
            container.find('.date').datepicker({format:'mm/dd/yyyy',autoclose:true});
            container.find('.datetime').datetimepicker({format: 'mm/dd/yyyy HH:ii:ss P'});
            
            container.find('input[type="checkbox"],input[type="radio"]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            })
            .on('ifToggled', function(){
                var elm = $(this), 
                    proxyInputName = elm.data('proxy-input'),
                    proxyInput = elm.closest('td')
                            .find('input:hidden[name^='+proxyInputName+']'),
                    isChecked = elm.prop('checked'),
                    checkedValue = 1*isChecked;
                //elm.val(checkedValue);
                if (proxyInput.length) {
                    proxyInput.val(checkedValue);
                }
                if (elm.hasClass('checkAll')) {
                    container.find('input.ids[type=checkbox]').iCheck(isChecked ? 'check' : 'uncheck');
                }
                if (elm.hasClass('ids') && !isChecked) {
                    container.find('input.checkAll[type=checkbox]').iCheck('uncheck');                    
                }
                if (elm.hasClass('ids') && isChecked) {
                    if (!container.find('input.ids[type=checkbox]').not(':checked').length)  {
                        container.find('input.checkAll[type=checkbox]').iCheck('check');                        
                    }                    
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

            }
            return false;

        });   
        
        
        container.find(".addPermission").on('click', function (e){
            e.preventDefault();
            var tableBody = form.find('table.datagrid tbody'),
                newRow = container.find('.newRowTemplateContainer table tbody tr'),
                clone = newRow.clone(true);
                
            clone.prependTo(tableBody);
            clone.find('input[type=text]:first').focus();
            
        });
    };  
    
    function preProcessForm(arr, $form, options) {
        options = options || {};
    } 
    function showProgress () {
        $('.ajaxLoading').show();
    }
    function hideProgress () {
        $('.ajaxLoading').hide();
    }

    function showFormResponse(data)  {

        hideProgress();
        if(data && data.status === 'success'){
            ajaxViewClose('#'+moduleName);
            notyMessage(data.message);            
        } 
        else {
            notyMessageError(data && data.message || 'Error in saving!!');
            return false;
        }
    }


}());
    

