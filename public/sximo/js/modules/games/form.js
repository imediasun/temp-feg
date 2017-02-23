/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl, siteUrl */ 
(function(){
    "use strict";    
    var modules = App.modules || (App.modules = {}),
        module = modules.games || (modules.games = {}),
        view = module.formView || (module.formView = {}),
        container,
        moduleName,
        mainModuleName,
        url,
        mainUrl,
        form,
        gameData;
   
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
            
            container.find("#sold").on('ifToggled', decidedToSell);                  
            
            container.find("#game_title_id").jCombo(mainUrl + "/comboselect?filter=game_title:id:game_title",
                    {  selected_value : '' + gameData.game_title_id });

            container.find("#game_type_id").jCombo(mainUrl + "/comboselect?filter=game_type:id:game_type",
                    {  selected_value : '' + gameData.game_type_id });

            container.find("#version_id").jCombo(mainUrl + "/comboselect?filter=game_version:id:version",
                    {  selected_value : '' + gameData.version_id });

            container.find("select#location_id").jCombo(mainUrl + "/comboselect?filter=location:id:id|location_name&delimiter=%20|%20",
                    {  selected_value : '' + gameData.location_id });

            container.find("#mfg_id").jCombo(mainUrl + "/comboselect?filter=vendor:id:vendor_name",
                    {  selected_value : '' + gameData.mfg_id });

            container.find("select#status_id").jCombo(mainUrl + "/comboselect?filter=game_status:id:game_status",
                    {  selected_value : '' + gameData.status_id });            
            
            
            container.find('#game_type_id').change(function() {
                var game_selected_value = container.find('#game_type_id option:selected').text();
                if(game_selected_value.trim() === 'Merchandise')
                {
                    container.find('#multi_products').show();
                    container.find("#product_id").jCombo(mainUrl + "/comboselect?filter=products:id:vendor_description",
                            {  selected_value : '' + gameData.product_id });

                }
                else
                {
                    container.find('#multi_products').hide();
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
                var game_selected_value = container.find('#game_type_id option:selected').text();
                if(game_selected_value.trim() != 'Merchandise')
                {
                    container.find("#product_id").removeAttr('value');
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
    
    function decidedToSell(event){
        var elm = $(this),
            isChecked = elm.prop('checked'),
            initialValue = elm.data('original-value') || 0,
            initiallySold = initialValue == 1,
            locationContainer = container.find('[name=location_id]'),
            statusContainer = container.find('[name=status_id]'),
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
                //statusContainer.select2('enable', false);
            }
            else {
                statusContainer.val(3);
                //statusContainer.prop('disabled', true);
            }                    
            if (locationContainer.data('select2')) {
                locationContainer.select2('val', 0);
                //locationContainer.select2('enable', false);
            }
            else {
                locationContainer.val(0);
                //locationContainer.prop('disabled', true);
            }
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
                //statusContainer.select2('enable', true);
                statusContainer.select2('val', cacheStatus, true);
            }
            else {
                //statusContainer.prop('disabled', false);
                statusContainer.val(cacheStatus).trigger('change');
            }
            if (locationContainer.data('select2')) {
                locationContainer.select2('val', cacheLocation);
            }
            else {
                locationContainer.val(cacheLocation);
            }
             if (locationContainer.data('select2')) {
                 //locationContainer.select2('enable', !cacheLocationDisabled);
             }
             else {
                 //locationContainer.prop('disabled', cacheLocationDisabled);
             }
        }
                
        showSoldDetails(isChecked);              
    }
    
    function showSoldDetails(showIt) {
        var fieldRequired = showIt !== false;
        form.parsley().destroy();
        if(showIt === false) {
            container.find('.soldInputs').removeClass('gameIsSold');            
            container.find('.soldDetails').hide();            
        }
        else {
            container.find('.soldInputs').addClass('gameIsSold');
            container.find('.soldDetails').show();
        }        
        container.find('[name=date_sold]').prop('required', fieldRequired);
        container.find('[name=sold_to]').prop('required', fieldRequired);
        form.parsley();
    }      

}());
