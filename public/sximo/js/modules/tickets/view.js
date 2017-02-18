/* global App, UNDEFINED, UNFN, jQuery, pageModule, pageUrl, userId, ticketID */ 
(function(){
    "use strict";    
    var modules = App.modules || (App.modules = {}),
        module = modules.tickets || (modules.tickets = {}),
        mainView = module.view || (module.view = {}),
        view = module.detailedView || (module.detailedView = {}),
        container,
        moduleName,
        mainModuleName,
        url,
        mainUrl,
        form,
        ticket,
        comments,
        creator,
        followers;

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
        form = container.find('#servicerequestsFormAjax');
        ticket = data.ticket;
        comments = data.comments;
        creator = data.creator;
        followers = data.followers;
        
        if (container && container.length) {
            container.find('.editor').summernote();
            container.find('.previewImage').fancybox();
            container.find('.tips').tooltip({'html': true});

            renderDropdown(container.find(".select2, .select3, .select4, .select5"), { width:"98%"});
            renderDropdown(container.find(".Priority, .Status"), { width:"20%" });
            
            container.find("#location_id").jCombo(mainUrl+"/comboselect?filter=location:id:id|location_name&delimiter=%20|%20",
                    {  selected_value : '' + ticket.location_id });

            container.find("#followers").jCombo(mainUrl+"/comboselect?filter=users:id:first_name|last_name",
                    {  selected_value : '' + followers.join(',') });

            container.find('.date').datepicker({format:'mm/dd/yyyy',autoClose:true});
            container.find('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
            container.find('input[type="checkbox"],input[type="radio"]').not('.isFollowing').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square_green'
            });     
            container.find(".nogallary a.fancybox").removeAttr("rel");
            container.find('.removeCurrentFiles').on('click',function(){
                var removeUrl = $(this).attr('href');
                $.get(removeUrl,function(response){});
                $(this).parent('div').empty();
                return false;
            });            

            container.find(".addAttachmentField").on('click', function (){
                var elm = $(this),
                    containerSelector = elm.data('container'),
                    inputContainer = container.find(containerSelector);
                
                inputContainer.append("<input  type='file' name='Attachments[]'  />");
                elm.html("Attach more files");
                return false;
            });
            
            
            container.find("#followers").select2().on("change", function(e) {
                var elm = e.target,
                    added = e.added,
                    removed = e.removed,
                    isSubscribe = added && added.id || false,
                    user = isSubscribe || removed && removed.id || '',
                    subscribeCommand = isSubscribe ? 'yes': 'unsubscribe',
                    url = [pageUrl,'subscribe',ticket.TicketID, user, subscribeCommand].join('/'),
                    foundMe = userId == user,
                    toggleButton = container.find('[name=isFollowingTicket]');
                
                if (added || removed) {
                    if (foundMe) {
                         toggleButton.bootstrapSwitch('state', !!isSubscribe, true);                    
                    }

                    $.ajax({
                        type:'GET',
                        url: url,
                        data: {'ajax': true},
                        success:function(data){}
                    });                    
                    
                }

            });
                               
            container.find(".isFollowing")
                .on('switchChange.bootstrapSwitch', function(event, state) {
                    var elm = $(this),
                        followersField = container.find("#followers"),
                        allFollowers, i, followerID, foundMe,
                        ajaxRunning = elm.data('ajax'),
                        isSubscribe = elm.prop('checked'),
                        subscribeCommand = isSubscribe ? 'yes': 'unsubscribe',
                        url = [pageUrl,'subscribe',ticket.TicketID, userId, subscribeCommand].join('/');
                    if (ajaxRunning && ajaxRunning.abort) {
                        ajaxRunning.abort();
                    }
                    
                    allFollowers = followersField.val();
                    foundMe = false;
                    for(i = 0; i < allFollowers.length; i++) {
                        if (userId == allFollowers[i]) {
                            foundMe = i;
                            break;
                        }
                    }
                    if (isSubscribe && foundMe === false) {
                        allFollowers.push(userId);
                        followersField.select2('val', allFollowers);
                    }
                    if (!isSubscribe && foundMe !== false) {
                        allFollowers.splice(foundMe, 1);
                        followersField.select2('val', allFollowers);
                    }
                    
                    ajaxRunning = $.ajax({
                        type:'GET',
                        url: url,
                        data: {'ajax': true},
                        success:function(data){}
                    });
                    elm.data('ajax', ajaxRunning);
                    
                    
               
                }).bootstrapSwitch();
         


            form.parsley();            
            form.on('submit', formSubmit);              
            
        }      
    };
  
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
