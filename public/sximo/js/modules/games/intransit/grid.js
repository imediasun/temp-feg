/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl, siteUrl */ 
(function(){
    "use strict";    
    var modules = App.modules || (App.modules = {}),
        module = modules.gamesintransit || (modules.gamesintransit = {}),
        view = module.grid || (module.grid = {}),
        container,
        moduleName,
        url,
        forms,
        addNewGameForm,
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
        var config_id, 
                addNewGameButton,
                addNewGameForm, 
                addNewGameFormSubmit,
                assetField,
                assetFieldParent,
                assetError;
        
        container.find("#game_title").jCombo(url + 
                "/comboselect?filter=game_title:id:game_title",
                { initial_text: 'Select Game Title'});        
          
        addNewGameButton = container.find('.addNewGameButton');
        addNewGameForm = container.find('#addnewgameFormAjax');
        assetField = addNewGameForm.find('#asset_number');
        assetFieldParent = assetField.parent();
        assetError = addNewGameForm.find('#asset_available');
        addNewGameFormSubmit = addNewGameForm.find('[type=submit]')
                .prop('disabled', true);
        
        addNewGameForm.parsley();
        addNewGameForm.submit(function () {

            if (addNewGameForm.parsley('isValid')) {
                var options = {
                    dataType: 'json',
                    success: addNewGameResponse
                };
                showProgress();
                $(this).ajaxSubmit(options);
            }
            return false;

        });
//        addNewGameForm.parsley('addListener', {
//            onFieldValidate: function(elm) {                
//            }
//        });
        
        addNewGameButton.on('click', function () {
            SximoModalShow(container.find("#myModal"));
            addNewGameForm[0].reset();
            addNewGameForm.parsley('reset');
            addNewGameForm.find('#game_title').select2('val', '');
            addNewGameFormSubmit.prop('disabled', true);
            assetError.hide('300');
        });
      
        assetField.focus(function(){
            addNewGameFormSubmit.prop('disabled', true);
            assetField.parsley('isValid');
            assetError.hide('300');
        })
        .blur(function(){
            addNewGameFormSubmit.prop('disabled', true);
            var elm = $(this),
                ajax = elm.data('ajaxCalled'),
                isValid,
                asset_number = ('' + elm.val()).replace(/^\s+?|\s+?$/g, '');
            
            if (ajax && ajax.abort) {
                ajax.abort();
            }
            
            if (!asset_number) {                
                return;
            }
                        
            isValid = elm.parsley('isValid');
            
            if (!isValid) {
                return;
            }            
            ajax = $.ajax({
                url: url + '/asset-number-availability/'+asset_number,
                method:'get',
                dataType:'json',
                success:function(result){
                    if(result.status=="error") {
                        assetError.css('color','red');
                    }
                    else{
                        assetError.css('color','green');
                        addNewGameFormSubmit.prop('disabled', false);
                    }
                    assetError.show('500');
                    assetError.text(result.message);
                }
            });
            
            elm.data('ajaxCalled', ajax);
        });        
    
        
        // Column config
        config_id = $("#col-config").val();
        if( config_id == 0 ) {
            $('#edit-cols,#delete-cols').hide();
        }
        else {
            $('#edit-cols,#delete-cols').show();
        }
        
        //
        if ($("#private").is(":checked")) {
            $('#groups').hide();
        }
        else{
            $('#groups').show();
        }       
        //
        $("#col-config").on('change',function(){
            var configId = $("#col-config").val();
            reloadData('#'+ moduleName, moduleName + '/data?config_id=' + configId 
                    + getFooterFilters());
        });

        $("#public,#private").change(function () {
            if ($("#public").is(":checked")) {
                $('#groups').show();
            }
            else {
                $('#groups').hide();
            }
        });

        $('#delete-cols').click(function(){
            if(confirm('Are You Sure, You want to delete this Columns Arrangement?')) {
                showProgress();
                var module = moduleName;
                var config_id = $("#col-config").val();
                $.ajax({
                    method: 'get',
                    data: {module: module, config_id: config_id},
                    url: siteUrl + '/tablecols/delete-config',
                    success: function (data) {
                        showResponse(data);
                    }
                });
            }
        });    
    
    
    };
    
    function addNewGameResponse(data) {
        hideProgress();
        if (data.status == 'success') {
            notyMessage(data.message);
            ajaxViewClose('#' + moduleName);
            $('.reloadDataButton').click();
            SximoModalHide(container.find("#myModal"));
            
        } else {
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
    
    function showResponse(data) {
        hideProgress();
        if (data.status == 'success') {
            ajaxViewClose('#' + moduleName);
            ajaxFilter('#'+ moduleName, url + '/data');
            notyMessage(data.message);
            $('#sximo-modal').modal('hide');
        } else {
            notyMessageError(data.message);
            return false;
        }
    }       
}());
