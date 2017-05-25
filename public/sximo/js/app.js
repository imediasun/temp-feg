var UNDEFINED, 
    UNFN = function () {},
    exportThreshold = 10000,
    App = {
        lastSearchMode: '',
        autoCallbacks: {},
        search: {cache: {}},
        simpleSearch: {cache: {}},
        columnSort: {cache: {}},
        populateFieldsFromCache: function (container, cacheObject, rebuildRequiredElements) {
            var cache = cacheObject.cache, 

                elmName, elm, elm2, operatorElm,
                item, val, val2, operator;
        
            if (container.length && cache) {            
                for(elmName in cache) {
                    elm = container.find('.form-control[name=' + elmName + ']');
                    elm2 = container.find('.form-control[name=' + elmName + '_end]');
                    operatorElm = container.find('#'+elmName+'_operate');
                    item = cache[elmName];
                    val = item.value;
                    val2 = item.value2;

                    operator = item.operator;
                    if (elm.length) {
                        if (elm.hasClass('sel-search-multiple') || elm.data('select2')) {
                            elm.select2('val', val);
                        }
                        else {
                            elm.val(val);
                            if (elm.hasClass('date')) {
                                elm.datepicker('update');
                            }
                            if (elm.hasClass('datetime')) {
                                elm.datetimepicker('update');
                            }
                        }
                    }                    
                    if (elm2.length) {
                        if (elm2.hasClass('sel-search-multiple') || elm2.data('select2')) {
                            elm2.select2('val', val2);
                        }
                        else {
                            elm2.val(val2);
                            if (elm2.hasClass('date')) {
                                elm2.datepicker('update');
                            }
                            if (elm2.hasClass('datetime')) {
                                elm2.datetimepicker('update');
                            }
                        }
                    }                    
                    if (operatorElm.length) {
                        operatorElm.val(operator);
                    }
                    switch (operator) {
                        case 'between':
                            if (rebuildRequiredElements) {
                                showBetweenFields({
                                    field: elmName,
                                    fieldElm : elm,
                                    fieldElm2 : elm2,
                                    previousValue2 : val2,
                                    dashElement : null
                                });
                            }
                            break;
                        case "is_null":
                        case "not_null":
                            elm.prop('readonly', true);
                            break;                            
                    }
                }
            }                    
        }
    };
App.notyConfirm = function (options)
{
	if (!options) {
        options = {};
    }
    var text = options.message || 'Are you sure you want to do this?',
        type = options.type || 'confirm',
        timeout = options.timeout || 50,
        layout = options.layout || 'topCenter',
        confirmCallback = options.confirm || UNFN,
        cancelCallback = options.cancel || UNFN,
        buttons = options.buttons || [
                {   addClass: 'btn btn-primary btn-sm',
                    text: options.confirmButtonText || 'Ok',
                    onClick: function ($noty) {
                        $noty.close();   
                        confirmCallback();
                    }
                }
            ],
        cancelButton = options.cancelButton || {
                addClass: 'btn btn-danger btn-sm', 
                text: options.cancelButtonText || 'Cancel', 
                onClick: function($noty) {
					$noty.close();
                    cancelCallback();
				}
			},
            notyOptions;
            
        buttons.push(cancelButton);
        notyOptions = {
                text: text,
                type: type,
                timeout: timeout,
                layout: layout,		
                buttons: buttons
            };    
	noty(notyOptions);		
	
};

App.autoCallbacks.registerCallback = function (eventName, definedFunction, options) {
    options = options || {};
    var callbackName = options.callbackName,
        fn = typeof definedFunction === 'function' ? definedFunction : UNFN,
        bed = App.autoCallbacks[eventName] || (App.autoCallbacks[eventName] = []);

    fn.options = options;
    if (callbackName) {
        bed[callbackName] = fn;
    }
    else {
        bed.push(fn);
    }
};

App.autoCallbacks.runCallback = function (eventName, params, options) {
    options = options || {};
    params = params || {};

    var context = this,
        callbackName = options.callbackName,
        index,
        fn,
        bed = App.autoCallbacks[eventName] || (App.autoCallbacks[eventName] = []);

    if (callbackName) {
        fn = bed[callbackName];
        if (typeof fn === 'function') {
           fn.call(context, params);
        }
    }
    else {
        for (index in bed) {
            fn = bed[index];
            if (typeof fn === 'function') {
                fn.call(context, params);
            }
        }
    }

};

App.autoCallbacks.registerCallback('reloaddata', function(params){
    initExport(this);
    //alignColumns(this);
    //initUserPopup(this);
});
App.autoCallbacks.registerCallback('columnselector', function(params){

});
App.autoCallbacks.registerCallback('ajaxinlinesave', function(params){

});


/**
 *  This function can check if a value needs URI encoding.
 *  It can be used before building a custom querystring
 *
 * @param mixed             value   String to check
 * @param jQuery Element    Input   field containing the value
 * @returns {Boolean}
 */
App.needsURIEncoding = function (value, field) {
    //when search is a string the encode it
    //encoding is needed for & sign, especially in games title search for Cats & Mice
    //if arrays are encoded, it does not populate in advanced search field
    // except: date fields
    var needs = typeof value === 'string';
    if (value === '' || field.hasClass('date') || field.hasClass('datetime')) {
        needs = false;
    }
    return needs;
};


App.buildSearchQueryFromArray = function (fields) {
    var fieldName,
        field,
        value,
        operator,
        value2,
        attr = '';

    for(fieldName in fields) {
        field = fields[fieldName];
        operator = field['operator'];
        value = field['value'];
        value2 = field['endValue'];
        if (operator == 'between') {
            if (value === UNDEFINED || value2 === UNDEFINED) {
                operator = "equal";
            }
            if (value === UNDEFINED && value2 !== UNDEFINED) {
                value = value2;
                value2 = UNDEFINED;
            }
            if (value === UNDEFINED && value2 === UNDEFINED) {
                continue;
            }
        }
        attr += fieldName+':'+operator+':'+value;
        if (value2 !== UNDEFINED) {
            attr += ':'+value2;
        }
        attr += "|";
    }

    return attr;
};

function initiateSearchFormFields(container) {
    container.find('.date').datepicker({format:'mm/dd/yyyy',autoclose:true});
    container.find('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
    renderDropdown(container.find('.sel-search-multiple, .select3'));
}

function initDataGrid(module, url, options) {
    if (!options) {
        options = {};
    }
    
    var useAjax = options.useAjax !== false;
    module = module.replace(/[^\w-]/g, '');
    
    var table = $('table#'+module+'Table'),
        sortableCols = table.find('thead tr th.dgcsortable');        
    
    sortableCols.click(function(event){
        var th = this,
            elm = $(th),
            field = elm.attr('data-field'),
            sortable = elm.attr('data-sortable'),
            sorted = elm.attr('data-sorted'),
            sortedOrder = elm.attr('data-sortedOrder') || '',
            nextOrder = sortedOrder == 'asc' ? 'desc' : 'asc',
            attr = getFooterFilters({'sort': true, 'order': true}),
            allAttr = attr + ('&sort=' + field + '&order=' + nextOrder);
        
        
        if (useAjax) {
            reloadData('#'+module, url+'/data?colheadersort=1' + allAttr);
        }
        else {
            window.location.href = url+'?colheadersort=1' + allAttr;
        }            
        
        
    });
    console.log($('ul.pagination li').length);
    $('ul.pagination li.active')
        .prev().addClass('show-mobile')
        .prev().addClass('show-mobile');
    $('ul.pagination li.active')
        .next().addClass('show-mobile')
        .next().addClass('show-mobile');
    $('ul.pagination')
        .find('li:first-child, li:last-child, li.active')
        .addClass('show-mobile');
}

function autoSetMainContainerHeight() {
    var setHeight = function (){
            var page = $("#page-wrapper"),
                pageHeight = page.height(),
                pageNewHeight,
                setHeight = page.data('appliedHeight'),
                windowHeight = $(window).height(),
                footerHeight = $(".footer").outerHeight(),
                sidebarHeight = $(".navbar-default").height(),
                height = Math.max(windowHeight - footerHeight, sidebarHeight);
            if (setHeight === height) {
                return false;
            }
            page.data('appliedHeight', height);
            page.css({
                'min-height': height + 'px',
                'height': 'auto',
                'transition' : 'height 0s'
            });
            pageNewHeight = page.height();
            App.autoCallbacks.runCallback.call(page, 'page-resized',
                {
                    pageHeight: pageHeight, 
                    pageNewHeight: pageNewHeight, 
                    minHeight: height,
                    windowHeight: windowHeight,
                    footerHeight: footerHeight, 
                    sidebarHeight: sidebarHeight
                });
            return height;
        };
    window.setTimeout(setHeight, 1000);
    $('nav.navbar-default').on('hidden.bs.collapse', setHeight);
    $('nav.navbar-default').on('shown.bs.collapse', setHeight);    
    $(window).resize(setHeight);    
}

function numberFieldValidationChecks(element){
    element.keypress(isNumeric);
}

function isNumeric(ev) {

    var keyCode = window.event ? ev.keyCode : ev.which;
    //codes for 0-9
    if (keyCode < 48 || keyCode > 57) {
        //codes for backspace, delete, enter
        if (keyCode != 0 && keyCode != 8 && keyCode != 13 && !ev.ctrlKey) {
            ev.preventDefault();
        }
    }
}
// checking browser and browser version
function get_browser() {
    var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if(/trident/i.test(M[1])){
        tem=/\brv[ :]+(\d+)/g.exec(ua) || [];
        return {name:'IE',version:(tem[1]||'')};
    }
    if(M[1]==='Chrome'){
        tem=ua.match(/\bOPR|Edge\/(\d+)/)
        if(tem!=null)   {return {name:'Opera', version:tem[1]};}
    }
    M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
    return {
        name: M[0],
        version: M[1]
    };
}

function verifyBrowser() {
    console.log(get_browser());
    switch (get_browser().name){
        case 'Chrome':
            if(get_browser().version<56){
                $('#browser_notification').show();
            }
            break;
        case 'Firefox':
            if(get_browser().version<52){
                $('#browser_notification').show();
            }
            break;
        case 'Safari':
            if(get_browser().version<5){
                $('#browser_notification').show();
            }
            break;
        case 'IE':
            if(get_browser().version<11){
                $('#browser_notification').show();
            }
            break;
    }
}

$( document ).ready(function() {
    verifyBrowser();
});

function disableConsoleLogs(){
    var console = {};
    console.log = function(){};
    window.console = console;
}

function initUserPopup(container) {
    var userFields = [
            "td[data-field=contact_id]",
            "td[data-field=merch_contact_id]",
            "td[data-field=general_manager_id]",
            "td[data-field=regional_manager_id]",
            "td[data-field=vp_id]",
            "td[data-field=technical_user_id]"
        ],
        userPath = '/core/users/show/',
        userSelector = userFields.join(', '),
        cells = container.find(userSelector);

    cells.each(function (){
        var cell = $(this),
            innerElement = cell.find('.activeLink'),
            content = cell.html(),
            wrap = "<span class='activeLink text-info'>"+ content + "</span>";
        if (!innerElement.length) {
            cell.html(wrap);
            innerElement = cell.find('.activeLink');
            innerElement.click(function () {
                var value = cell.attr('data-values') || '',
                    val = cell.text(),
                    userUrl = '' + userPath + value + '/popup';
                if (value) {
                    SximoModal(userUrl, val);
                }
            });
        }
    });
}

function initExport(container) {
    
    var exportButtons = container.find('a[href*="/export/"]');

    if (exportButtons.length) {
        exportButtons.on('click', function () {
                var elm = $(this),
                    href = elm.attr('href'),
                    exportIDMatches = /[\?\&]exportID\=([^\&]*)/.exec(href),
                    exportId = exportIDMatches && exportIDMatches[1] || '',
                    setUrl = location.pathname + '/init-export/'+ exportId,
                    probeUrl = location.pathname + '/probe-export/'+ exportId;

                    if (exportId) {
                        setAndProbeExportSessionTimeout(setUrl, probeUrl);
                    }

            });
    }
}

function setAndProbeExportFormSessionTimeout(formElement) {

    var exportIDMatches = formElement.find('[name=exportID]'),
        exportId = exportIDMatches.val(),
        setUrl = location.pathname + '/init-export/'+ exportId,
        probeUrl = location.pathname + '/probe-export/'+ exportId;

//    console.log(exportId);
    if (exportId) {
        setAndProbeExportSessionTimeout(setUrl, probeUrl);
    }    
}

function setAndProbeExportSessionTimeout(setUrl, probeUrl) {
    $.post(setUrl, {}, function(data) {
        //console.log(data);
    });
    setTimeout(function (){

        $.post(probeUrl,
            {},
            function (data){
                //console.log(data);
                if (data && data.waiting) {
                    notyMessage("The file you have requested \n\
                        is large and may take several minutes \n\
                        to generate. Please do not close this \n\
                        page until your file is ready.",
                        {
                            showDuration: 0,
                            timeOut: 0,
                            showEasing: 'linear',
                        },
                        'info',
                        'Attention');
                }
            },
            'json'
        );

    }, exportThreshold);
}

function updateNativeUIFieldsBasedOn() {
    var searchCache,
        search = $('.table-actions input[name=search]').val() || '',
        isSimpleSearch = $('.table-actions input[name=simplesearch]').val() || 0, 
        splitFields = search.split('|'),//id:between:1:100|
        field, 
        item, i,
        fieldName, val, operator, val2;
        
    if (search) {
        searchCache = {};
        for(i in splitFields) {
            field = splitFields[i];
            if (field) {
                item = field.split(":");
                fieldName = item[0] || '';
                operator = item[1] || '';
                val = item[2] || '';
                val2 = item[3];
                if (fieldName) {
                    searchCache[fieldName] = {'operator': operator, 'value': val, 'value2': val2};
                }
            }
        }
        if (isSimpleSearch) {
            App.simpleSearch.cache = searchCache;
            App.simpleSearch.populateFields();
        }
        else {
            App.search.cache = searchCache;
        }
    }
    
}

function makeSimpleSearchFieldsToInitiateSearchOnEnter() {
    var simpleSearchContainer = $('.simpleSearchContainer'),
        hasSimpleSearch = simpleSearchContainer.length,
        simpleSearchButton =  hasSimpleSearch && 
                            simpleSearchContainer.find('.doSimpleSearch');
    if (hasSimpleSearch) {
        simpleSearchContainer.find('input[type=text]').keypress(function(event){
            var keycode = event.keyCode || event.which;
            if(keycode == '13') {
                simpleSearchButton.click();
            }
        });
    }
}

/**
 * This is a simple function to renders select2 dropdown.
 * However, before rendering it checks if the target element already has
 * been rendered with select2
 *
 * @param {type} elements Elements - result of jQuery select query
 * @param {type} options - Select2 options
 * @returns {undefined}
 */
function renderDropdown(elements, options) {
    options = options || {};
    if (elements && elements.length) {
        elements.each(function(i, elm){
            var $elm = $(elm);
            if (!$elm.data('select2')) {
                $elm.select2(options);
            }
        });
    }
}

function detectPUAA($) {
    var linksToPage = $(".linkPUAA.linkToCMSPage"),
        linksToModules = $(".linkPUAA"),
        ajax;

    linksToModules.on('click', function (e){
        e.preventDefault();
        var elm = $(this), 
            authValidator = siteUrl + "/urlauth/access",
            url = elm.attr('href');
            
        if (ajax && ajax.abort) {
            ajax.abort();            
        }
        
        ajax = $.ajax({
            type: 'POST',            
            url: authValidator,            
            data: {
                url: url,
                isPage: elm.hasClass('linkToCMSPage') * 1
            },
            success: function (data) {
                if(data.status === 'success'){
                    location.href = url;                    
                }
                else {
                    notyMessageError(data.message);
                }
            }
        });
        
        
    });

}


function alignColumns(gridContanier) {

    var table,
        tableWidth,
        originalTable,
        tdsCount,
        allTds,
        allThs,
        widthForEachTd,
        diff;

    tableWidth = ($('.table.table-striped').width());
    //console.log(tableWidth);
    originalTable = $('.table.table-striped').clone();
    //console.log(originalTable);
    tdsCount = ($('.table > tbody > tr:nth-child(2) td').length);

    allTds = $('.table > tbody > tr > td');
    allThs = $('.table > thead > tr > th');
    $('.table > tbody > tr').width(tableWidth+100).css('float','left');
    //console.log(tdsCount);
    widthForEachTd = (tableWidth/(tdsCount-2));
    $(originalTable).children('tbody').children('tr').children('td').each(function (index,value) {

        diff = tdsCount-($('.table > tbody > tr#form-0 > td').length);
        console.log(diff);
        if(allTds.eq(index).children('.icheckbox_square-blue').length == 0 && (index+diff)%tdsCount != 0)
        {
            allTds.eq(index).width(widthForEachTd).addClass( "equalWidth" );
        }
        else
        {
            allTds.eq(index).width(50).addClass( "equalWidth" );
        }
    });

    $(originalTable).children('thead').children('tr').children('th').each(function (index) {

        if(allThs.eq(index).children('.icheckbox_square-blue').length == 0 && index%tdsCount !=0)
        {
            allThs.eq(index).width(widthForEachTd).addClass( "equalWidth" );
        }
        else
        {
            console.log(index+' __ '+tdsCount);
            allThs.eq(index).width(50).addClass( "equalWidth" );
        }
    });
}

function blockUI() {
    $('.ajaxLoading').show();
}
function unblockUI() {
    $('.ajaxLoading').hide();
}

jQuery(document).ready(function($){

    //var browser=get_browser(); //need to uncomment it as required
    //console.log(browser.name+ " version:"+browser.version);
    // Adjust main panel's height based on overflowing nav-bar
    autoSetMainContainerHeight();

    // detect link to possible unauthorised access
    detectPUAA($);

    initExport(jQuery('.page-content-wrapper'));
    //initUserPopup(jQuery('.page-content-wrapper'));

    if(PREVENT_CONSOLE_LOGS){
        disableConsoleLogs();
    }
});

// TODO: Clean and refactor the below code 
jQuery(document).ready(function ($) {
    $('.ajaxLoading').bind('DOMSubtreeModified', function(e) {
        if (e.target.innerHTML.length > 0) {
            console.log('if');
            console.log(e);
        }
        else
        {
            console.log('else');
            console.log(e);
        }
    });
	navigator.sayswho= (function(){
		var ua= navigator.userAgent, tem,
				M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
		if(/trident/i.test(M[1])){
			tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
			return 'IE '+(tem[1] || '');
		}
		if(M[1]=== 'Chrome'){
			tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
			if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
		}
		M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
		if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
		return M.join(' ');
	})();

	console.log(navigator.sayswho);

    $('body #sidemenu a:not(.expand)').not('#logo').on('click',function (e) {
		e.preventDefault();
		var url = $(this).attr('href');
		var href = $(this).attr('href').split('/');
		console.log(url,href,href[href.length-1]);
		if(url != 'javascript:void(0)')
		{
			$('.ajaxLoading').show();
			$.ajax({
				url: siteUrl + '/core/users/check-access',
				method:'get',
				data: {
					module:href[href.length - 1],
					url: url.replace(siteUrl, '')
				}
			})
            .done(function (data) {
                if(data == false)
                {
                    $('.ajaxLoading').hide();
                    notyMessageError('You are not authorized to view this page');
                    //window.location = url;
                }
                else
                {
                    if(data.is_view == 1)
                    {
                        window.location = url;
                    }
                    else
                    {
                        $('.ajaxLoading').hide();
                        notyMessageError('You are not authorized to view this page.');
                    }
                }
                //$('.globalLoading').hide();

            })
            .error(function (data) {
                console.log(data);
                if(data.status == '500')
                {
                    window.location = url;
                }
                else
                {
                    notyMessageError('Error getting permission to the page you are trying to access');
                    $('.ajaxLoading').hide();
                }
            });
		}
	});
	
//	$('.item_dropdown li a').on('click', function () {
//		if($(this).parents('.item_title').find(">:first-child").text() != 'My Account')
//		{
//			window.localStorage.setItem('clicked_tab', $(this).parents('.item_title').find(">:first-child").text());
//		}
//		//alert($(this).parents('.item_title').find(">:first-child").text());
//	});
//	if(window.location.pathname != "/dashboard")
//	{
//		//console.log($('#sidemenu li.active .nav-label').text());
//
//		$('#sidemenu li.active .nav-label').text() == '' ? window.localStorage.getItem('clicked_tab') == '' || window.localStorage.getItem('clicked_tab') == null ? '' : $('.page-title.change_title').text(window.localStorage.getItem('clicked_tab')) : $('.page-title.change_title').text($('#sidemenu li.active .nav-label').text());
//	}
//	if(window.location.pathname == '/user/profile')
//	{
//		$('.page-title.change_title').text('My Account');
//	}

    $('#sidemenu').sximMenu();
	$('.spin-icon').click(function () {
        $(".theme-config-box").toggleClass("show");
    });

//	setInterval(function(){
//		var noteurl = $('.notif-value').attr('code');
//		$.get( noteurl +'/notification/load',function(data){
//			$('.notif-alert').html(data.total);
//			var html = '';
//			$.each( data.note, function( key, val ) {
//				html += '<li><a href="'+val.url+'"> <div> <i class="'+val.icon+' fa-fw"></i> '+ val.title+'  <span class="pull-right text-muted small">'+val.date+'</span></div></li>';
//				html += '<li class="divider"></li>';
//			});
//			html += '<li><div class="text-center link-block"><a href="'+noteurl+'/notification"><strong>View All Notification</strong> <i class="fa fa-angle-right"></i></a></div></li>';
//			$('.notif-value').html(html);
//		});
//	}, 60000);

});

