/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl */ 
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
            
            renderDropdown(container.find(".select2, .select3, .select4, select5"), { width:"98%"});
            container.find('.date').datepicker({format:'mm/dd/yyyy',autoClose:true});
            container.find('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
            container.find('input[type="checkbox"],input[type="radio"]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square_green'
            });     
            
            
            container.find("#game_title_id").jCombo(mainUrl + "/comboselect?filter=game_title:id:game_title",
                    {  selected_value : '' + gameData.game_title_id });

            container.find("#game_type_id").jCombo(mainUrl + "/comboselect?filter=game_type:id:game_type",
                    {  selected_value : '' + gameData.game_type_id });

            container.find("#version_id").jCombo(mainUrl + "/comboselect?filter=game_version:id:version",
                    {  selected_value : '' + gameData.version_id });

            container.find("#location_id").jCombo(mainUrl + "/comboselect?filter=location:id:location_name",
                    {  selected_value : '' + gameData.location_id });

            container.find("#mfg_id").jCombo(mainUrl + "/comboselect?filter=vendor:id:vendor_name",
                    {  selected_value : '' + gameData.mfg_id });

            container.find("#status_id").jCombo(mainUrl + "/comboselect?filter=game_status:id:game_status",
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
                    success:       showResponse
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
    
    function showProgress () {
        $('.ajaxLoading').show();
    }
    function hideProgress () {
        $('.ajaxLoading').hide();
    }

    function showResponse(data)  {

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


}());
