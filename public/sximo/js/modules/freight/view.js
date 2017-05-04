/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl */ 
(function(){
    "use strict";    
    var modules = App.modules || (App.modules = {}),
        module = modules.freight || (modules.freight = {}),
        view = module.view || (module.view = {}),
        container,
        moduleName,
        mainModuleName,
        url,
        mainUrl,
        form,
        freightData;
        
    view.init = function (options, data) {  
        var to_contact_name = data['to_contact_name'],
            email_notes = data['email_notes'];
    
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
        form = container.find('#managefreightquotersFormAjax');
        freightData = data;
    
        if (container && container.length) {
            container.find('.date').datepicker({format:'mm/dd/yyyy', autoclose:true});
            container.find('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});     

            renderDropdown(container.find("input[id^='company']"), {
                'width': '100%',
                'data': data['companies_dropdown'],
                'placeholder': "Select Company"
            });

            renderDropdown(container.find("#freight_company_1"), {
                'width':'100%',
                'data': data['companies_dropdown'],
                'placeholder': "Select Company"
            });

            container.find("input[id^='loc_game_']").each(function(key,value){
                renderDropdown($(this), {
                    'width': '100%',
                    'data': data['game_drop_down'],
                    'placeholder': "Select Game"
                });
                if($(this).val() == '' || $(this).val() == null || $(this).val() == '0')
                {
                    $(this).siblings('.select2-container').children('a').children('span').first().text('Select Game').css('color','#999999');
                }
            });

            if(to_contact_name !== "" && email_notes === "") {
                $("#email_notes").val("Hi"+to_contact_name+",");
            }

            $('input[type="checkbox"],input[type="radio"]').iCheck({
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


            $("#send_email_update").on('ifToggled', function(){
                var elm = $(this),
                    others =  $("input[id^=include_in_email]"),
                    isChecked = elm.prop('checked');

                if (isChecked) {
                    others.iCheck('check'); 
                }
                else {
                    others.iCheck('uncheck');
                }
            });
        
        /*
            $("#send_email_update").on('change',function(){
                if($(this).is(':checked'))
                {
                    $("input[id^=include_in_email]").attr('checked','checked');
                }
                else{
                    $("input[id^=include_in_email]").removeAttr('checked');
                }
            });

            $("#email").change(function() {
                if(this.checked) {
                    $( ":checkbox" ).prop('checked', true);
                    $( "checkbox[id^='new_ship_']").prop('checked', false);
                }
                else
                {
                    $( ":checkbox" ).prop('checked', false);
                }
            }); 
        */

            
            
        } 
        
    };
        


    
}());
