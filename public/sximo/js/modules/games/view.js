/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl, siteUrl */ 
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
            
            renderDropdown(container.find(".select2, .select3, .select4, .select5"), { width:"98%"});
            
            container.find("#status").on('change',gameStatusChanged);
            
            container.find("#status").jCombo(mainUrl+"/comboselect?filter=game_status:id:game_status", 
                        {  selected_value : ''+ gameData.status_id });
            container.find("#location_id").jCombo(mainUrl+"/comboselect?filter=location:id:id|location_name&delimiter=%20|%20", 
                        {  selected_value : ''+ gameData.dropdownlocation });
                        
            container.find('.date').datepicker({format:'mm/dd/yyyy',autoclose:true});
            container.find('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
            container.find('input[type="checkbox"],input[type="radio"]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square_green'
            });     
            container.find(".nogallary a.fancybox").removeAttr("rel");

            form.parsley();            
            form.on('submit', formSubmit);              
            
        }      
    };
    
    function gameStatusChanged(options) {
        options = options || {};
        var GAME_UP = 1,
            GAME_DOWN = 2,
            GAME_MOVE = 3,
            statusSelector = $(this), 
            status=statusSelector.val(),
            initialStatus = statusSelector.data('original-value') || 0,
            UP_TO_DOWN  = initialStatus == GAME_UP   && status == GAME_DOWN,
            UP_TO_MOVE  = initialStatus == GAME_UP   && status == GAME_MOVE,
            STAYING_UP  = initialStatus == GAME_UP   && status == GAME_UP,
            STAYING_DOWN= initialStatus == GAME_DOWN && status == GAME_DOWN,
            DOWN_TO_UP  = initialStatus == GAME_DOWN && status == GAME_UP,
            MOVE_TO_UP  = initialStatus == GAME_MOVE && status == GAME_UP,
            STAYING_MOVE= initialStatus == GAME_MOVE && status == GAME_MOVE,
            
            statusSelectorHasSelect2 = statusSelector.data('select2'),
            locationSelector = container.find("#location_id"),
            locationLabelAddon = container.find(".locationLabelModifier"),
            locationSelectorHasSelect2 = locationSelector.data('select2'),
            upFromRepairContainer = container.find(".upFromRepairDetails"),
            downForRepairContainer = container.find(".downforRepairDetails"),
            location = locationSelector.val(),
            initialLocation = locationSelector.data('original-value') || 0,            
            intendedLocation = container.find("[name=intended_first_location]").val() || 0,
            submitButton = container.find("#submit"),
            isSold = container.find("[name=sold]").val() == 1;
        
        locationLabelAddon.hide();
        if(initialStatus == GAME_DOWN) {
            // disable game move
            statusSelector.find('option[value='+GAME_MOVE+']').prop('disabled', true);
        }        
        if(initialStatus == GAME_MOVE) {
            // disable game down
            statusSelector.find('option[value='+GAME_DOWN+']').prop('disabled', true);
            locationLabelAddon.show();
        }
        form.parsley().destroy();
        container.find('[name=location_id]').prop('required', false);
        form.parsley();        
        // 
        submitButton.prop('disabled', false);
        if(STAYING_UP) {
            submitButton.prop('disabled', true);
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
            showUpFromRepair(false);
            showDownForRepair(false);   
            return;
        }
        
        if(UP_TO_DOWN) {
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
            showUpFromRepair(false);
            showDownForRepair(true);  
            return;
        }
        
        if(UP_TO_MOVE) {
            if (locationSelectorHasSelect2) {
                locationSelector.select2("val", 0);
                locationSelector.select2('enable', true);
            }
            else {
                locationSelector.val(0);
                locationSelector.prop('disabled', false);
            }
            showUpFromRepair(false);
            showDownForRepair(false);            
            locationLabelAddon.show();
            return;
        }
        
        //
        if(STAYING_DOWN) {
            submitButton.prop('disabled', true);
            if (locationSelectorHasSelect2) {
                locationSelector.select2('enable', false);
            }
            else {
                locationSelector.prop('disabled', true);
            }              
            showUpFromRepair(false);
            showDownForRepair(false); 
            return;
        }
        
        if(DOWN_TO_UP) {
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
        if(STAYING_MOVE) {
            submitButton.prop('disabled', true);
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
        
        if(MOVE_TO_UP) {
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
    
    function showUpFromRepair(showIt) {
        var fieldRequired = showIt !== false;
        if (showIt === false) {
            container.find(".upFromRepairDetails").hide();             
        }
        else {
            container.find(".upFromRepairDetails").show();
        }
        
        form.parsley().destroy();
        container.find('[name=date_up]').prop('required', fieldRequired);
        container.find('[name=solution]').prop('required', fieldRequired);           
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
