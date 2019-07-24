
function reloadData( id,url,callback, options,tabSwitch,clear)
{
    console.log(id,url,callback, options,tabSwitch,clear)
    options = options || {};
    var isClearSearch = /data\?search\=$/.test(url),
        isBackground = options.isBackground || false,
        clearFilters;

    if (clear != undefined){
        isClearSearch = true;
    }
    if (tabSwitch === undefined) {
        App.autoCallbacks.runCallback.call($(id + 'Grid'), 'beforereloaddata',
            {id: id, url: url, isClear: isClearSearch});
    }
        
    if (isClearSearch) {
        clearFilters = getClearDataFilters();        
        url += clearFilters;         
    }

    if (!isBackground) {
        $('.ajaxLoading').show();
    }
	$.post( encodeURI(url) ,function( data ) {

		$( id +'Grid' ).html( data );
        if (clear != undefined) {
            typeof callback === 'function' && callback(data);
        }

        if (tabSwitch === undefined) {
            App.autoCallbacks.runCallback.call($(id + 'Grid'), 'reloaddata',
                {id: id, url: url, data: data, isClear: isClearSearch});
        }
        if (!isBackground) {
            if(pageModule=='order' || pageModule == 'managefegrequeststore'){
                console.log('debug me');
                var left = localStorage.getItem('scrollLeft');
                var top = localStorage.getItem('scrollTop');
                $(id + 'Grid').show();
                $('.ajaxLoading').hide();
                if(left!=0 && top !=0) {
                   var total = $('.datagrid').width()+$('.sideMenuNav').width() + 100;
                   left = Math.floor(total);
                   top = Math.floor(top);
                    var values = [left,top];
                    setTimeout(function (data) {
                        $(window).scrollTop(data[1]);
                        $('.table-responsive').animate(
                            {   scrollLeft: data[0]
                            },0);
                        left = 0;
                        top = 0;
                        localStorage.setItem('scrollTop', 0);
                        localStorage.setItem('scrollLeft', 0);
                    },0,values);


               }

            }else{
                $(document).scrollTop(0);
                $( id +'Grid' ).show();
                $('.ajaxLoading').hide();
            }
        }
        
	});


    if(id=='#servicerequests' || id=="#product"){
        if(url){
            console.log(url)
        var array = url.split("?");
            if(array[1]){
        var array2 = array[1].split("=");
        array2[0]='simplesearch';
        var final_url=array[0]+'?'+array2.join('=')
        $.post( encodeURI(final_url) ,function( data ) {
            $( id +'Grid' ).html( data );
        })
            }
        }
    }

}


function getClearDataFilters(id, url) {
    
    var filter = '',
        excludes = {}, includes = {}, forces = {}, blinds = {},
        callbackModifications = {
            include: includes, 
            exclude: excludes, 
            force: forces,
            blind: blinds
        };
    
    App.autoCallbacks.runCallback.call($( id +'Grid' ), 'beforeclearsearch', 
            {id:id, url:url, data: callbackModifications});    
            
    excludes = $.extend({}, {'search': true, 'page': true}, callbackModifications.exclude);
    includes = $.extend({}, {}, callbackModifications.include); //{'rows': true, 'sort': true, 'order': true}
    forces = $.extend({}, {}, callbackModifications.force);
    blinds = $.extend({}, {}, callbackModifications.blind);

    filter = getFooterFiltersWith(includes, excludes, forces, blinds);

    return filter;
}

function getFooterFilters(excludeList, forceSetFields) {

    var attr = "", fieldKey;
    if (!forceSetFields) {
        forceSetFields = {};
    }
    if (!excludeList) {
        excludeList = {};
    }
    if (excludeList['_token'] === UNDEFINED) {
        excludeList['_token'] = true;
    }
    
    for(fieldKey in forceSetFields) {
        $('.table-actions [name='+fieldKey+']').val(forceSetFields[fieldKey]);
    }        
    $('.table-actions :input').each(function () {
        var elm = $(this), 
            fieldName = elm.attr('name'), 
            val = elm.val(),
            isExlude = excludeList[fieldName] === true;
        if (!isExlude && val !== '' && val !== null) {
            attr += '&' + fieldName + '=' + val;
        }
    });
    return attr;
}
function getFooterFiltersWithoutSort() {
    var attr = getFooterFilters({'sort': true, 'order': true});
    return attr;
}

function getFooterFiltersWith(includeList, excludeList, forceSetFields, blindFields, returnArray) {
    var UNDEFINED,
        attr = "", 
        attrs = {}, 
        finalAttrs = {},
        key,
        val,
        fieldKey;

    includeList = includeList || {};
    excludeList = excludeList || {};
    forceSetFields = forceSetFields || {};
    blindFields = blindFields || {};
    
    for(fieldKey in forceSetFields) {
        $('.table-actions [name='+fieldKey+']').val(forceSetFields[fieldKey]);
    } 
    
    $('.table-actions :input').each(function () {
        var elm = $(this), 
            fieldName = elm.attr('name'), 
            val = elm.val(),
            isInclude = includeList[fieldName] === true;
        if (val !== '' && val !== null) {
            attrs[fieldName] = val;
            
        }
    });
    
    for(key in includeList) {
        if (includeList[key]===true && attrs[key] !== UNDEFINED) {
            finalAttrs[key] = attrs[key];
        }
    }
    if ($.isEmptyObject(finalAttrs)) {
        finalAttrs = $.extend({}, attrs);
    }
    
    for(key in blindFields) {
        finalAttrs[key] = blindFields[key];
    }    
    
    for(key in excludeList) {
        if (excludeList[key]===true && finalAttrs[key] !== UNDEFINED) {
            delete finalAttrs[key];
        }
    }
    
    if (returnArray) {
        return finalAttrs;
    }
    for(key in finalAttrs) {
        val = finalAttrs[key];
        if (val !== '' && val !== null) {
            attr += '&' + key + '=' + val;
        }        
    }
    
    return attr;    
}


function ajaxDoSearch( id ,url )
{
	var attr = '', elm, val = "";
	$( id +'Search :input').each(function() {
        elm = $(this);
        val = elm.val();
		if(val !=='') { attr += this.name+':'+val+'|'; }
	});
	reloadData( id ,url+'&search='+attr);
}


function ajaxQuickAdd(id, url )
{

	var attr = '';
	var datas = $( id +'Search :input').serialize();
	$.post( url+'/quicksave' ,datas, function( data ) {
		if(data.status =='success')
		{
			ajaxFilter( id , url+'/data' );
			$( ".resultData" ).html( data.message );
		} else {
			$( ".resultData" ).html( data.message );
		}			
	});

	
}

function ajaxInlineRemove(id,url)
{

		if(confirm('are u sure remove selected row?'))
		{
			$.get(url, function( data ) {
				$(id).remove();
			});
			
		}
		return false;
}
function ajaxInlineSave(id,url,reloadurl)
{

	var datas = $( id +'Form :input').serialize();
	$.post( url ,datas, function( data ) {
		$('.ajaxLoading').show();
		$.post( reloadurl ,function( data ) {
			$( id+'Grid' ).html( data );
			$('.ajaxLoading').hide();
            App.autoCallbacks.runCallback.call($( id +'Grid' ), 'ajaxinlinesave', 
                {id:id, url:url, data:data, reloadurl: reloadurl});
		});							
	});
}	

function ajaxInlineEdit(id,url,reloadurl)
{
	$(id +' span').each(function() {
		val = $(this).html();
		val = val.split(':');
		$('input[name='+val[0]+']').val(val[1]);
	});
}


function ajaxFilter( id ,url,opt,column,idExt)
{
    var id2 = id;
    if(idExt !=undefined){
        id = idExt;
    }
    var attr = '', elm, val;
        $(id + 'Filter :input').each(function () {
			elm = $(this);
			val = elm.val();
//            if (this.value != '' && this.value!=0) {
              if (this.name != '_token') {
                if (val !== '' && val !== null) {
                    if ( this.name == "sort" && column !== undefined) {

                       attr +=  "sort="+column+"&";
                    }

                    else {
                        
                        attr += this.name + '=' + val + '&';

                    }
                }                  
              }


        });

    if(opt  !== undefined) {
        attr += opt;
    }
    id = id2;

reloadData(id, url+"?"+attr);
}



function ajaxCopy(  id , url,extra )
{
    if($(".ids:checked").length > 0) {
        if (confirm('Are you sure you want to copy selected row(s)')) {
            var datas = $(id + 'Table :input').serialize();
            $.post(url + '/copy', datas, function (data) {
                if (data.status == 'success') {
                    notyMessage(data.message);
                    ajaxFilter(id, url + '/data',extra);
                } else {
                    notyMessage(data.message);
                }
            });
        } else {
            return false;
        }
    }
    else
    {
        notyMessageError("Please select one or more rows.");
    }

}

function ajaxRemove( id, url,extra )
{
    var datas = $( id +'Table :input').serialize();
    if($(".ids:checked").length > 0) {
    if(confirm('Are you sure you want to delete the selected row(s)?')) {

        $.post( url+'/delete' ,datas,function( data ) {

            if(data.status =='success')
            {
                //console.log("called succes");
                notyMessage(data.message);
                ajaxFilter( id ,url+'/data',extra );
            } else {
                //console.log("called error");
                notyMessageError(data.message);
            }
        });

    }
    }
    else
    {
        notyMessageError("Please select one or more rows.");
    }
}

//Clear all vendors mail schedule
function ajaxClearSchedule( url )
{
    if(confirm('Are you sure you want to clear all vendors schedule.')) {
        $('.ajaxLoading').show();
        $.post( url+'/delete',function( data ) {

            if(data.status =='success')
            {
                console.log("called succes");
                notyMessage(data.message);
            } else {
                console.log("called error");
                notyMessageError(data.message);
            }
            $('.ajaxLoading').hide();
        });

    }
    
}


//This function is used to send product list to respective vendor.
function ajaxSendProductList( url )
{
    $('.ajaxLoading').show();

    $.post( url,function( data ) {


        if(data.status =='success')
        {
            console.log("called succes");
            notyMessage(data.message);
        } else {
            console.log("called error");
            notyMessageError(data.message);
        }
        $('.ajaxLoading').hide();
    });


}


function ajaxRemoveProduct(id, url) {
    var datas = $(id + 'Table :input').serialize();
    if ($(".ids:checked").length > 0) {
        if (confirm('Are you sure you want to delete the selected row(s)?')) {

            $.post(url + '/delete', datas, function (data) {

                for (var i = 0; i < data.length; i++) {
                    var dataMessage = data[i];
                    if (dataMessage.status == 'success') {
                        //console.log("called succes");
                        notyMessage(dataMessage.message);
                        ajaxFilter(id, url + '/data');
                    } else {
                        //console.log("called error");
                        notyMessageError(dataMessage.message);
                    }
                    $('.btn.btn-search[data-original-title="Reload Data"]').trigger("click");
                }

            });

        }
    }
    else {
        notyMessageError("Please select one or more rows.");
    }
}
function ajaxGameDispose( id, url )
{
    var datas = $( id +'Table :input').serialize();
    if($(".ids:checked").length > 0) {
    if(confirm('Are you sure you want to dispose the selected game(s)?')) {

        $.post( url+'/dispose' ,datas,function( data ) {

            if(data.status =='success')
            {
                //console.log("called succes");
                notyMessage(data.message);
                ajaxFilter( id ,url+'/data' );
            } else {
                //console.log("called error");
                notyMessageError(data.message);
            }
        });

    }
    }
    else
    {
        notyMessageError("Please select one or more rows.");
    }
}

function ajaxViewDetail( id , url, closeLoadingAfterComplete )
{
    if(closeLoadingAfterComplete === undefined){
        closeLoadingAfterComplete = false;
    }
    var beforeSximoEvent = 'ajaxview.before',
        beforeSximoEventModule = 'ajaxview.before.'+id,
        showView = function () {
            $('.ajaxLoading').show();
            console.log(url);
            $.get( url ,function( data ) {
                $( id +'View').html( data );
                $( id +'Grid').hide( );
                var w = $(window);
                var duration = 300;
                $('html, body').animate({scrollTop: 0}, duration);
                $('.control-label,.label-control').each(function () {
                    var str = $(this).text();
                    if (str.indexOf(':') == -1) {
                        $(this).addClass('addcolon');
                    }
                });

                App.autoCallbacks.runCallback.call(window, 'ajaxViewOpened',eventData);
                if(!closeLoadingAfterComplete) {
                    $('.ajaxLoading').hide();
                }
            });
        },
        eventData = {url:url, id:id, callback: showView};

    if (App.autoCallbacks[beforeSximoEventModule] && App.autoCallbacks[beforeSximoEventModule].length) {
        App.autoCallbacks.runCallback.call(window, beforeSximoEvent,eventData);
    }
    if (App.autoCallbacks[beforeSximoEvent] && App.autoCallbacks[beforeSximoEvent].length) {
        App.autoCallbacks.runCallback.call(window, beforeSximoEvent, eventData);
    }
    else {
        showView();
    }
    
}

function ajaxViewClose( id , elm, options) {
    options = options || {};
    var view = $(id + 'View'),
        grid = $(id + 'Grid'),
        $elm = elm && $(elm) || [];

    if ($elm.length) {
        if (!view.length) {
            view = $elm.closest('.moduleView');
        }
        if (!grid.length) {
            grid = view.closest('.page-content').find('.moduleGrid');
        }
    }


    view.html('');

    if ($('.simpleSearchContainer').find('.bootstrap-switch-wrapper').length > 0 || $('.table.datagrid').find('.bootstrap-switch-wrapper').length > 0 || id == '#managefreightquoters') {
        if (id === "#product")
        {
            $('.btn.btn-search[data-original-title="Reload Data"]').trigger("click");
        } else {
        var url = id + '/data';
        url = url.replace('#', '');
        reloadData(id, url);
    }
}
    else
    {
        grid.show();
    }
    if (options.modal) {
        SximoModalHide(options.modal, options.callback);
    }
    else {
        if (!options.noModal) {
            SximoModalHide($('#sximo-modal'), options.callback);
        }
        
    }
}

function ajaxViewChange(id , newContent, elm, options)
{
    options = options || {};
    var view = $(id+'View'),
        pos,
        top = 0,
        $elm = elm && $(elm) || [];
    
    if ($elm.length) {
        if (!view.length) {
            view = $elm.closest('.moduleView');
        }
    }
    if (options.modal) {
        SximoModalHide(options.modal, options.callback);
    }
    else {
        if (!options.noModal) {
            SximoModalHide($('#sximo-modal'), options.callback);
        }
    }
    
    view.html(newContent);
    pos = view.position();
    top = pos && pos.top || 0;
    scrollTo(0, top);
    
}


function ajaxPopupStatic(url ,w , h)
{
    var newwindow;
	var w = (w == '' ? w : 800 );	
	var h = (h == '' ? wh: 600 );	
	newwindow=window.open(url,'name','height='+w+',width='+h+',resizable=yes,toolbar=no,scrollbars=yes,location=no');
	if (window.focus) {newwindow.focus()}
}

function notyMessage(message, options, messageType, title)
{
    options = options || {};
    if (!messageType) {
        messageType = 'success';
    }
    if (!title) {
        title = '';
    }
    
	var finalOptions = $.extend({}, {
		  "closeButton": true,
		  "debug": false,
		  "positionClass": "toast-bottom-right",
		  "onclick": null,
		  "showDuration": "300",
		  "hideDuration": "1000",
		  "timeOut": "5000",
		  "extendedTimeOut": "1000",
		  "showEasing": "swing",
		  "hideEasing": "linear",
		  "showMethod": "fadeIn",
		  "hideMethod": "fadeOut"
	}, options);
    
	toastr[messageType](message, title, finalOptions);
	
}
function notyMessageError(message, options, title)
{
    options = options || {};
    if (!title) {
        title = '';
    }
    
	var finalOptions = $.extend({}, {
		  "closeButton": true,
		  "debug": false,
		  "positionClass": "toast-bottom-right",
		  "onclick": null,
		  "showDuration": "300",
		  "hideDuration": "1000",
		  "timeOut": "5000",
		  "extendedTimeOut": "1000",
		  "showEasing": "swing",
		  "hideEasing": "linear",
		  "showMethod": "fadeIn",
		  "hideMethod": "fadeOut"
        }, options);

	toastr.error(message, title, finalOptions);
	
}

function notyConfirm(id, url)
{
	
	var n = noty({				
		text: 'Are you sure you want to delete the selected row(s)?',
		type: 'Confirm',
		timeout : 50,
		layout: 'topCenter',
		
		buttons: [
			{addClass: 'btn btn-primary btn-sm', text: 'Ok', onClick: function($noty) {
					var datas = $( id +'Table :input').serialize();
					$.post( url+'/destroy' ,datas,function( data ) {
						if(data.status =='success')
						{
							notyMessage(data.message );
							ajaxFilter( id ,url+'/data' );
						} else {
							notyMessage(data.message );
						}				
					});	
					$noty.close();
				}
			},
			{addClass: 'btn btn-danger btn-sm', text: 'Cancel', onClick: function($noty) {
					$noty.close();
					//noty({text: 'You clicked "Cancel" button', type: 'error'});
				}
			}
		]
	});		
	
}

function addMoreFiles(id){

   $("."+id+"Upl").append('<input type="file" name="'+id+'[]" />')
}

function SximoModalLarge( url , title)
{
	$('#sximo-modal-lg #sximo-modal-content').html(' ....Loading content , please wait ...');
	$('#sximo-modal-lg  .modal-title').html(title);
	$('#sximo-modal-lg  #sximo-modal-content').load(url,function(){
        var modal = $('#sximo-modal-lg'),
            titletrim = title.replace(/[\s\W]/ig, '').replace(/^\d+?/,'').toLowerCase();
            App.autoCallbacks.runCallback.call(modal, titletrim, {url:url, title:title});
	});
	$('#sximo-modal-lg').modal('show');	
}
$(function(){
    $(".collapse-close, .cancelButton").click(function(){
        $(document).scrollTop(0);
    });
});
