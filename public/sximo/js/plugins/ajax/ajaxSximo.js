
function reloadData( id,url,callback)
{
    var isClearSearch = /data\?search\=$/.test(url),
        clearFilters;
    
    App.autoCallbacks.runCallback.call($( id +'Grid' ), 'beforereloaddata', 
        {id:id, url:url, isClear: isClearSearch});       
        
    if (isClearSearch) {
        clearFilters = getClearDataFilters();        
        url += clearFilters;         
    }

	$('.ajaxLoading').show();
	$.post( url ,function( data ) {
		$( id +'Grid' ).html( data );
		typeof callback === 'function' && callback(data);
        App.autoCallbacks.runCallback.call($( id +'Grid' ), 'reloaddata', 
            {id:id, url:url, data:data, isClear: isClearSearch});
        $( id +'Grid' ).show();
		$('.ajaxLoading').hide();
	});

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


function ajaxFilter( id ,url,opt,column)
{
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


reloadData(id, url+"?"+attr);
}



function ajaxCopy(  id , url )
{
    if($(".ids:checked").length > 0) {
        if (confirm('Are you sure you want to Copy selected row(s)')) {
            var datas = $(id + 'Table :input').serialize();
            $.post(url + '/copy', datas, function (data) {
                if (data.status == 'success') {
                    notyMessage(data.message);
                    ajaxFilter(id, url + '/data');
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

function ajaxRemove( id, url )
{
    var datas = $( id +'Table :input').serialize();
    if($(".ids:checked").length > 0) {
        if (confirm('Are you sure you want to delete the selected row(s)?')) {

            $.post(url + '/delete', datas, function (data) {

                if (data.status == 'success') {
                    //console.log("called succes");
                    notyMessage(data.message);
                    ajaxFilter(id, url + '/data');
                } else {
                    //console.log("called error");
                    notyMessageError(data.message);
                }
            });

        }
    }
    else
    {
        notyMessageError("Please select one or more rows");
    }
}

function ajaxViewDetail( id , url )
{
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
		$('.ajaxLoading').hide();
	});

}

function ajaxViewClose( id , elm)
{
    var view = $(id+'View'),
        grid = $(id+'Grid'),
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
    if($('.table.datagrid').find('.bootstrap-switch-wrapper').length > 0 || id == '#managefreightquoters')
    {
        var url = id+'/data';
        url = url.replace('#','');
        reloadData(id,url);
    }
    else
    {
        grid.show();
    }
	$('#sximo-modal').modal('hide');
}

var newwindow;
function ajaxPopupStatic(url ,w , h)
{
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
