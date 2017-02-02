/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl */ 
(function(){
    "use strict";    
    var modules = App.modules || (App.modules = {}),
        module = modules.games || (modules.games = {}),
        mainView = module.view || (module.view = {}),
        view = module.detailedView || (module.detailedView = {}),
        container,
        moduleName,
        mainModuleName,
        url,
        mainUrl,
        form,
        gameData;

    mainView.init = function () {
        
    };
    
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
        form = container.find('#mylocationgameFormAjax');
        gameData = data;
    
        if (container && container.length) {
            
            renderDropdown(container.find(".select2, .select3, .select4, select5"), { width:"98%"});
            
            container.find("#status").on('change',gameStatusChanged);
            
            container.find("#status").jCombo(mainUrl+"/comboselect?filter=game_status:id:game_status", 
                        {  selected_value : ''+ gameData.status_id });
            container.find("#location_id").jCombo(mainUrl+"/comboselect?filter=location:id:location_name", 
                        {  selected_value : ''+ gameData.dropdownlocation });
                        
            container.find('.date').datepicker({format:'mm/dd/yyyy',autoClose:true});
            container.find('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
            container.find('input[type="checkbox"],input[type="radio"]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square_green'
            });     
            container.find(".nogallary a.fancybox").removeAttr("rel");

            container.find("#sold").on('ifToggled', decidedToSell);            
            
            form.parsley();            
            form.on('submit', formSubmit);              
            
        }      
    };
    
    function gameStatusChanged(options) {
        options = options || {};
        var statusSelector = $(this), 
            statusSelectorHasSelect2 = statusSelector.data('select2'),
            locationSelector = container.find("#location_id"),
            locationLabelAddon = container.find(".locationLabelModifier"),
            locationSelectorHasSelect2 = locationSelector.data('select2'),
            upFromRepairContainer = container.find(".upFromRepairDetails"),
            downForRepairContainer = container.find(".downforRepairDetails"),
            location = locationSelector.val(),
            initialLocation = locationSelector.data('original-value') || 0,
            initialStatus = statusSelector.data('original-value') || '',
            intendedLocation = container.find("[name=intended_first_location]").val() || 0,
            isSold = container.find("[name=sold]").val() == 1,
            status=statusSelector.val();
        
        locationLabelAddon.hide();
        if(initialStatus == 2) {
            statusSelector.find('option[value=3]').prop('disabled', true);
        }        
        if(initialStatus == 3) {
            statusSelector.find('option[value=2]').prop('disabled', true);
            locationLabelAddon.show();
        }
        form.parsley().destroy();
        container.find('[name=location_id]').prop('required', false);
        form.parsley();        
        // 
        if(initialStatus == 1 && status == 1) {
            if (locationSelectorHasSelect2) {
                if (location != initialLocation) {
                    locationSelector.select2("val", initialLocation);
                }
                locationSelector.select2('enable', false);
            }
            else {
                if (location != initialLocation) {
                    locationSelector.val(initialLocation);
                }                
                locationSelector.prop('disabled', true);
            }                                    
            showUpFromRepair(true);
            showDownForRepair(false);   
            return;
        }
        
        if(initialStatus == 1 && status == 2) {
            if (locationSelectorHasSelect2) {
                if (location != initialLocation) {
                    locationSelector.select2("val", initialLocation);
                }                
                locationSelector.select2('enable', false);
            }
            else {
                if (location != initialLocation) {
                    locationSelector.val(initialLocation);
                }                 
                locationSelector.prop('disabled', true);
            }            
            showUpFromRepair(true);
            showDownForRepair(true);  
            return;
        }
        
        if(initialStatus == 1 && status == 3) {
            if (locationSelectorHasSelect2) {
                locationSelector.select2("val", 0);
                locationSelector.select2('enable', true);
            }
            else {
                locationSelector.val(0);
                locationSelector.prop('disabled', false);
            }
            showUpFromRepair(true);
            showDownForRepair(false);            
            locationLabelAddon.show();
            return;
        }
        
        //
        if(initialStatus == 2 && status == 2) {
            if (locationSelectorHasSelect2) {
                locationSelector.select2('enable', false);
            }
            else {
                locationSelector.prop('disabled', true);
            }              
            showUpFromRepair(true);
            showDownForRepair(false); 
            return;
        }
        
        if(initialStatus == 2 && status == 1) {
            if (locationSelectorHasSelect2) {
                locationSelector.select2('enable', false);
            }
            else {
                locationSelector.prop('disabled', true);
            }         
            showUpFromRepair(true);
            showDownForRepair(false);
            return;
        }
        
        //
        if(initialStatus == 3 && status == 3) {
            if (locationSelectorHasSelect2) {
                locationSelector.select2('enable', !isSold && intendedLocation == 0);
            }
            else {                
                locationSelector.prop('disabled', isSold || intendedLocation != 0);
            }            
            showUpFromRepair(false);
            showDownForRepair(false);
            return;
        }
        
        if(initialStatus == 3 && status == 1) {
            if (locationSelectorHasSelect2) {
//                if (location != intendedLocation) {
//                    locationSelector.select2("val", intendedLocation);
//                }                
                locationSelector.select2('enable', true);
            }
            else {
//                if (location != intendedLocation) {
//                    locationSelector.val(intendedLocation);
//                }                
                locationSelector.prop('disabled', false);
            }            
            showUpFromRepair(false);
            showDownForRepair(false);
            
            form.parsley().destroy();
            container.find('[name=location_id]').prop('required', true);
            form.parsley();
            return;
        }
     
       
    };
    
    function decidedToSell(event){
        var elm = $(this),
            isChecked = elm.prop('checked'),
            initialValue = elm.data('original-value') || 0,
            initiallySold = initialValue == 1,
            locationContainer = container.find('#location_id'),
            statusContainer = container.find('#status'),
            cacheStatus,
            cacheLocation,
            cacheLocationDisabled;
        
        elm.val(1*isChecked);
        
        if (isChecked) {
            cacheStatus = statusContainer.val();
            cacheLocation = locationContainer.val();
            cacheLocationDisabled = locationContainer.prop('disabled');
            elm.data('cache-status', cacheStatus);
            elm.data('cache-location', cacheLocation);
            elm.data('cache-location-disabled', cacheLocationDisabled);
            if (statusContainer.data('select2')) {
                statusContainer.select2('val', 3);
                statusContainer.select2('enable', false);
            }
            else {
                statusContainer.val(3);
                statusContainer.prop('disabled', true);
            }                    
            if (locationContainer.data('select2')) {
                locationContainer.select2('val', 0);
                locationContainer.select2('enable', false);
            }
            else {
                locationContainer.val(0);
                locationContainer.prop('disabled', true);
            }                      

            showDownForRepair(false);
        }
        else {
            cacheStatus = elm.data('cache-status') || 3;
            cacheLocation = elm.data('cache-location');
            cacheLocationDisabled = elm.data('cache-location-disabled');
            if (cacheLocation === null) {
                cacheLocation = 0;
            }
            if (cacheLocationDisabled === null) {
                cacheLocationDisabled = false;
            }
            if (statusContainer.data('select2')) {
                statusContainer.select2('enable', true);
                statusContainer.select2('val', cacheStatus, true);
            }
            else {
                statusContainer.prop('disabled', false);
                statusContainer.val(cacheStatus).trigger('change');
            }
            if (locationContainer.data('select2')) {
                locationContainer.select2('val', cacheLocation);
            }
            else {
                locationContainer.val(cacheLocation);
            }
             if (locationContainer.data('select2')) {
                 locationContainer.select2('enable', !cacheLocationDisabled);
             }
             else {
                 locationContainer.prop('disabled', cacheLocationDisabled);
             }                         
        }
                
        showSoldDetails(isChecked);
        
        
    }
    
    function showUpFromRepair(showIt) {
        var fieldRequired = showIt !== false;
        if (showIt === false) {
            container.find(".upFromRepairDetails").hide();             
        }
        else {
            container.find(".upFromRepairDetails").show();
        }
        
        form.parsley().destroy();
        container.find('[name=date_down]').prop('required', fieldRequired);
        container.find('[name=problem]').prop('required', fieldRequired);           
        form.parsley();
    }
  
    function showDownForRepair(showIt) {
        var fieldRequired = showIt !== false;
        if (showIt === false) {
            container.find(".downforRepairDetails").hide();             
        }
        else {
            container.find(".downforRepairDetails").show();
        }
        
        form.parsley().destroy();
        container.find('[name=date_down]').prop('required', fieldRequired);
        container.find('[name=problem]').prop('required', fieldRequired);           
        form.parsley();
    }
  
    function showSoldDetails(showIt) {
        var fieldRequired = showIt !== false;
        form.parsley().destroy();
        if(showIt === false) {
            container.find('.soldDetails').hide();            
        }
        else {
            container.find('.soldDetails').show();
        }        
        container.find('[name=date_sold]').prop('required', fieldRequired);
        container.find('[name=sold_to]').prop('required', fieldRequired);
        form.parsley();
    }

    function formSubmit() {
        
        if(form.parsley('isValid')) {
            var options = {
                dataType     :  'json',
                beforeSubmit :  preProcessForm,
                success      :  showFormResponse
            };
            showProgress();
            form.ajaxSubmit(options);
            return false;   
        } 
        else {
            return false;
        }        
    }
    function preProcessForm(arr, $form, options) {
        options = options || {};
        var elements = {
                'date_sold': true,
                'date_down': true,
                'date_up': true
            },
            deleteElms = {"date_sold_view": true},
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
            ajaxViewClose('#'+moduleName);
            initDataGrid(moduleName, url);
            notyMessage(data.message);
            $('#sximo-modal').modal('hide');
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
