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
        newInputIndex = 0,
        gridData,
        grid,
        form,
        deleteForm;
   
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
        deleteForm = container.find('#specialPermissionsGridDeleteForm');
        grid = container.find('.datagrid');
        permissionData = data.permissions;
        gridData = data.grid;
    
        if (grid && grid.length) {
            initControls(grid, form);
            trackChanges(grid);
        }
        
        form.on('submit', function(){

            if(form.parsley('isValid')){
                var options = {
                    dataType:      'json',
                    beforeSubmit :  preProcessForm,
                    success:       showFormResponse
                };

                showProgress();
                form.ajaxSubmit(options);

            }
            return false;

        });   
        
        
        container.find(".addPermission").on('click', function (e){
            e.preventDefault();
            var tableBody = form.find('table.datagrid tbody'),
                newRow = container.find('.newRowTemplateContainer table tbody tr'),
                clone = newRow.clone(true);
                
            clone.prependTo(tableBody);
            initNewInputs(clone);
            clone.find('input[type=text]:first').focus();
            initControls(clone, form);
        });
        container.find(".deletePermission").on('click', function (e){
            e.preventDefault();
            var tableBody = form.find('table.datagrid tbody'),
                rows = tableBody.find('tr :input.ids:checked'),
                newRows = tableBody.find('tr.newPermission :input.ids:checked').closest('tr'),
                ids = [];
                        
            if (!rows.closest('tr').length && !newRows.length) {
                notyMessageError("Nothing to delete");
                return;
            }
            
            rows.each(function(){
                var val = $(this).val();
                if (val) {
                    ids.push(val);
                }                
            });
            deleteForm.find('[name=deletedIds]').val(ids.join(','));
            
            showProgress();
            newRows.remove();
            deleteForm.ajaxSubmit({
                dataType: 'json',
                success :  function(data) {
                    hideProgress();
                    if(data && data.status === 'success'){
                        notyMessage(data.message);
                        rows.closest('tr').remove();
                    }
                    else {
                        notyMessageError(data && data.message || 'Error in saving!!');
                        return false;
                    }                    
                }
            });            

        });        
    };  
    
    function initNewInputs(row) {
        var inputs = row.find(':input');
        inputs.each(function(){
            var elm = $(this),
                isMultiple = elm.attr('multiple') != UNDEFINED,
                nameAttr  = elm.attr('name');
            if (!nameAttr) {
                return;
            }
            if (isMultiple) {
                nameAttr = nameAttr.replace(/\[\]$/, '') + ['[', newInputIndex, '][]'].join('');
            }
            else {
                nameAttr +=  ['[', newInputIndex, ']'].join('');
            }
            elm.attr('name', nameAttr);
        });
        newInputIndex++;
        
    }

    function trackChanges(control) {
        var inputs = control.find(':input'),
            originalValue;
            
        inputs.each(function(){
            var input = $(this),
                originalValue = input.val();
                
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
    }
    
    function initControls(container, form) {
            
        container.find('.tips').tooltip(); 
        renderDropdown(form.find(".sel-inline, .select2, .select3, .select4, .select5"), { width:"100%"});
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
                //container.find('input.checkAll[type=checkbox]').iCheck('uncheck');                    
            }
            if (elm.hasClass('ids') && isChecked) {
                if (!container.find('input.ids[type=checkbox]').not(':checked').length)  {
                   // container.find('input.checkAll[type=checkbox]').iCheck('check');                        
                }                    
            }
        });          
        
        form.parsley().destroy();
        form.parsley();
        
    }
    
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

        if(data && data.status === 'success'){
            notyMessage(data.message);
            location.reload(true);
        } 
        else {
            hideProgress();
            notyMessageError(data && data.message || 'Error in saving!!');
            return false;
        }
    }


}());
    

