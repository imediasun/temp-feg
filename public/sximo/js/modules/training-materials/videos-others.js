/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl, mainModule, mainUrl */ 
(function(){
    "use strict";    
    var modules = App.modules || (App.modules = {}),
        module = modules.training || (modules.training = {}),
        view = module.videos || (module.videos = {}),
        container,
        moduleName,
        mainModuleName,
        url,
        mainUrl,
        forms,
        videosData;
    
    view.init = function (options, data) {    
        options = options || {};
        data = data || {};
        moduleName = options.moduleName || pageModule;
        mainModuleName = options.mainModuleName || pageModule;
        url = options.url || pageUrl;
        mainUrl = options.mainUrl || pageUrl;
        videosData = data;    
        container = options.container || $(options.container);
        if (!container.length) {
            container = $("#"+moduleName+"View");
        }
        if (!container.length) {
            container = jQuery;
        }
        forms = container.find('.videoContainer form');
        forms.on('submit', formSubmit);              

    };

    function formSubmit() {
        var form = $(this),
            deleteConfirmed = confirm("The video will be deleted. Press OK to confirm?"),
            options = {
                dataType     :  'json',
                beforeSubmit :  preProcessForm,
                success      :  showFormResponse
            };
        if (deleteConfirmed) {
            showProgress();
            form.ajaxSubmit(options);
        }
        return false;   

    }
    function preProcessForm(arr, $form, options) {
    }      
    function showFormResponse(data)  {

        hideProgress();
        if(data.status === 'success'){
            notyMessage(data.message);
            reloadData('#'+ pageModule, mainModule + '/data');     
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
