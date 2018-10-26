var UNDEFINED,
    UNFN = function () {},
    exportThreshold = 10000,
    App = {
        lastSearchMode: '',
        handlers: {},
        functions: {},
        formats: {},
        ajax: {},
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
                            if (typeof val =='string') {
                                val = val.split(',');
                            }
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
                    text: options.confirmButtonText || 'OK',
                    onClick: function ($noty) {
                        $noty.close();
                        confirmCallback($noty);
                    }
                }
            ],
        cancelButton = options.cancelButton || {
                addClass: 'btn btn-danger btn-sm',
                text: options.cancelButtonText || 'Cancel',
                onClick: function($noty) {
					$noty.close();
                    cancelCallback($noty);
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

    if (options.modal !== UNDEFINED) {
        notyOptions.modal = options.modal;
    }
    if (options.closeWith !== UNDEFINED) {
        notyOptions.closeWith = options.closeWith;
    }
    if (options.killer !== UNDEFINED) {
        notyOptions.killer = options.killer;
    }
    if (options.progressBar !== UNDEFINED) {
        notyOptions.progressBar = options.progressBar;
    }
    if (options.force !== UNDEFINED) {
        notyOptions.force = options.force;
    }
    if (options.container !== UNDEFINED) {
        notyOptions.container = options.container;
    }
    if (options.animation !== UNDEFINED) {
        notyOptions.animation = options.animation;
    }
    if (options.callbacks !== UNDEFINED) {
        notyOptions.callbacks = options.callbacks;
    }
    if (options.container !== UNDEFINED) {
        notyOptions.container = options.container;
    }
    if (options.id !== UNDEFINED) {
        notyOptions.id = options.id;
    }
    if (options.theme !== UNDEFINED) {
        notyOptions.theme = options.theme;
    }
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

var getExcludedProductTypesAndProducts = [];
App.autoCallbacks.registerCallback('reloaddata', function(params){
    initExport(this);
    //alignColumns(this);
    //initUserPopup(this);
    $(document).scrollTop(0);
    getExcludedProductTypesAndProductIds();
});

App.autoCallbacks.registerCallback('advancedsearch', function(params){
    excludeProductsAndProductTypes();
});

App.autoCallbacks.registerCallback('ajaxViewOpened', function(params){
    setTimeout(function () {
        excludeProductsAndProductTypes();
    }, 1000);
});
App.autoCallbacks.registerCallback('inline.row.config.after', function(params){
    if(pageModule == 'shopfegrequeststore' || pageModule == 'order' || pageModule == 'product' || pageModule == 'location' || pageModule == 'locationgroups' || pageModule == 'managefegrequeststore') {
        setTimeout(function () {
            excludeProductsAndProductTypes();
        }, 1000);
    }
});
function getExcludedProductTypesAndProductIds(){
    $.ajax({
        url: '/location/get-excluded-products-and-product-types',
        method: 'GET',
        success: function (result) {
            getExcludedProductTypesAndProducts = result;
            excludeProductsAndProductTypes();
        }
    })
}

function excludeProductsAndProductTypes(){
    setTimeout(function(){
         if(pageModule == 'shopfegrequeststore' || pageModule == 'order' || pageModule == 'product' || pageModule == 'location' || pageModule == 'locationgroups' || pageModule == 'managefegrequeststore'){
            var productTypeSelectorsNames = ['prod_type_id', 'Product_Type', 'order_type', 'prod_type_id[]'];
            $.each(productTypeSelectorsNames, function (key, val) {


                var selectBox = $('select[name="'+val+'"]');
                if(selectBox.length != 0){

                    $.each(getExcludedProductTypesAndProducts.excluded_product_type_ids, function(key, val){
                        selectBox.find('[value="'+val+'"]').remove();
                    });
                }
            })
         }
    }, 1000);
}
App.autoCallbacks.registerCallback('columnselector', function(params){

});
App.autoCallbacks.registerCallback('ajaxinlinesave', function(params){
});

App.handlers.ajaxError = function (jQEvent, jQXhr, xhr, errorName) {
    console.log([errorName, jQEvent, jQXhr, xhr]);
    var obj = this,
        status = jQXhr.status,
        statusText = jQXhr.statusText,
        skipIf = {'unauthorized': false, 'abort': true, 'not found': true, 'Unprocessable Entity':true},
        skipIfStatus = {'0': true, '401': false, '403': true},
        isErrorNameString = typeof errorName == 'string',
        errorNameString = isErrorNameString && errorName.toLowerCase() || '';

    console.log([obj, jQEvent, jQXhr, xhr, errorName]);
    if(__noErrorReport ||
            !isErrorNameString ||
            !errorNameString ||
            skipIf[''+status] ||
            skipIf[errorNameString]) {
        return;
    }
    App.autoCallbacks.runCallback.call(obj, 'ajaxerror',{
        jQEvent: jQEvent, jQXhr: jQXhr, xhr: xhr, errorName: errorName, context: obj
    });
};

//App.handlers.ajaxSuccess = function (a,b,c) {
//    var obj = this;
//    console.log(arguments);
//    App.autoCallbacks.runCallback.call(obj, 'ajaxsuccess',{});
//};
//App.handlers.ajaxFail = function (a,b,c) {
//    var obj = this;
//    console.log(arguments);
//    App.autoCallbacks.runCallback.call(obj, 'ajaxfail',{});
//};

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
    console.log(get_browser().name);
    console.log(get_browser().version);
    switch (get_browser().name){
        case 'Chrome':
            if(get_browser().version<57){
                $('#browser_notification').show();
            }
            break;
        case 'Firefox':
            if(get_browser().version<52){
                $('#browser_notification').show();
            }
            break;
        case 'Safari':
            if(get_browser().version<9){
                $('#browser_notification').show();
            }
            break;
        case 'Opera':
            if(get_browser().version<14){
                $('#browser_notification').show();
            }
            break;
        case 'IE':
            if(get_browser().version<11){
                $('#browser_notification').show();
            }
            break;
        case 'MSIE':
            if(get_browser().version<11){
                $('#browser_notification').show();
            }
            break;
    }
}

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
                            showEasing: 'linear'
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
            var attr = $elm.attr('customOption');

            if (typeof attr !== typeof undefined && attr !== false) {
                loadjscssfile('//cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js', 'js')
                loadjscssfile('//cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css', 'css')

                    options.closeOnSelect = false;
                    options.allowHtml = true;
                    options.tags= true;
            }

            if (!$elm.data('select2')) {
                $elm.select2(options);
            }
        });
    }
}
function loadjscssfile(filename, filetype){
    if (filetype=="js"){ //if filename is a external JavaScript file
        var fileref=document.createElement('script')
        fileref.setAttribute("type","text/javascript")
        fileref.setAttribute("src", filename)
    }
    else if (filetype=="css"){ //if filename is an external CSS file
        var fileref=document.createElement("link")
        fileref.setAttribute("rel", "stylesheet")
        fileref.setAttribute("type", "text/css")
        fileref.setAttribute("href", filename)
    }
    if (typeof fileref!="undefined")
        document.getElementsByTagName("head")[0].appendChild(fileref)
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

    $('body').bind('ajaxError', function(jQEvent, jQXhr, xhr, errorName){
        App.handlers.ajaxError.call(this, jQEvent, jQXhr, xhr, errorName);
    });
//    $('body').bind('ajaxStart', function(e){
//        console.log(['ajaxStart', arguments]);
//    });
//    $('body').bind('ajaxSend', function(e){
//        console.log(['ajaxSend', arguments]);
//    });

});

// TODO: Clean and refactor the below code
jQuery(document).ready(function ($) {
    $('.ajaxLoading').bind('DOMSubtreeModified', function(e) {
        /*if (e.target.innerHTML.length > 0) {
            console.log('if');
            console.log(e);
        }
        else
        {
            console.log('else');
            console.log(e);
        }*/
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

        $('body #sidemenu a[href="http://dev.fegllc.com/forum"],a[href="http://admin1.fegllc.com/forum"]').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            var Link = $(this);
            Link.attr("target", "_blank");
            window.open(Link.attr("href"));
            return false;
        });

    $('body #sidemenu a:not(.expand)').not('a[href="http://dev.fegllc.com/forum"]').not('a[href="http://admin1.fegllc.com/forum"]').not('#logo').on('click',function (e) {
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
                    if (data.noAuth) {
                        notyMessageError(data.message);
                        unblockUI();
                        window.location = siteUrl;
                    }
                    else if(data.is_view == 1)
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
                if(data.status == '500' || data.status == '401')
                {
                    notyMessageError(data.statusText);
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

/* Ajax Error Handling *///
App.functions.reportIssue = function (params, options) {
    var reportUrl = siteUrl + '/core/users/report-issue';
    notyMessage("Wait, Reporting error!");
    var RequestData = {};
    for (var entrie of params.xhr.data.entries()) {
        var obj = entrie[0];
        RequestData[obj] = entrie[1];
    }

    $.ajax({
        url: reportUrl,
        type:'POST',
        // method:'POST',
        data: {
            responseText: encodeURIComponent(encodeURIComponent(params.jQXhr.responseText)),
            readyState: params.jQXhr.readyState,
            status: params.jQXhr.status,
            statusText: params.jQXhr.statusText,
            type: params.xhr.type,
            url: params.xhr.url,
            pageUrl: location.href,
            userAgent: navigator.userAgent,
            data: RequestData || {}
        }
    })
    .done(function (data) {
        if (options.done) {
            options.done();
        }
    })
    .error(function (data) {
        if (options.done) {
            options.done();
        }
    });
};

App.autoCallbacks.registerCallback('ajaxerror', function(params){

    console.log(params);
    var obj = this;
    unblockUI();
    var defaultMessage = "OOPS Something Went Wrong.\n\
                    Please click the Report Issue \n\
                    button below to send an error report to the support team.";
    if(params.errorName == "Unauthorized"){
        defaultMessage = "Your session has expired. Please log back into the admin in order to complete this action. ";
    }
    if(params.errorName == "Unprocessable Entity"){ ///Set for ajax validation errors
        return;
    }
    App.notyConfirm({
        message : defaultMessage,
        modal: true,
        layout: 'center',
        type: 'error',
        closeWith: ['button'],
        killer: true,
        theme: 'relax',
        cancelButtonText: 'Close Window',
        cancel: function ($noty){
            unblockUI();
            $noty.close();
            if(params.errorName == "Unauthorized"){
                location.href = siteUrl;//location.reload();
            }

        },
        buttons: [{
                    addClass: 'btn btn-primary btn-sm',
                    text: 'Report Issue',
                    onClick: function ($noty) {
                        blockUI();
                        App.functions.reportIssue.call(obj, params, {'done': function (response) {
                            //response
                            unblockUI();
                            $noty.close();
                            notyMessage("Error reported!");
                            //location.href = siteUrl;
                            //location.reload();
                        }});
                    }
                }]
    });
});

/**
 * Shorthand ajax
 * @param string url
 * @param object options
 * @returns jQXhr
 */
App.ajax.request = App.ajax.submit = App.ajax.getData = function (url, options) {
    options = options || {};
    var settings = options.settings || {},
        done = options.done || UNFN,
        fail = options.fail || UNFN,
        always = options.always || UNFN,
        isBlockUI = options.blockUI || false,
        ajaxSettings = $.extend({}, {
            url: url,
            type: options.type || options.method || 'get',
            method: options.method || options.type || 'get',
            dataType: options.dataType || 'json',
            data: options.data || {},
        }, settings),
        xhr;

    if (isBlockUI) {
        blockUI();
    }
    xhr = $.ajax(ajaxSettings)
            .done(function (data, textStatus, jqXHR){
                done(data, textStatus, jqXHR);
                if (settings.done && typeof settings.done == 'function') {
                    settings.done(data, textStatus, jqXHR);
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown){
                fail(jqXHR, textStatus, errorThrown);
                if (settings.fail && typeof settings.fail == 'function') {
                    settings.fail(jqXHR, textStatus, errorThrown);
                }
            })
            .always(function (d, status, x){
                if (isBlockUI) {
                   unblockUI();
                }
                always(d, status, x);
                if (settings.always && typeof settings.always == 'function') {
                    settings.always(d, status, x);
                }
            });

    return xhr;
};


/*** GLOBAL FORM CLEANUP BEFORE CLIENT SIDE VALIDATION */
App.autoCallbacks.registerCallback('parsley.form.validate.before', function (event, parameters) {
    var form = this;
    App.functions.cleanupForm(form, {'email': ['trim'], 'email_2': ['trim']});
});

$.fn.parsley.defaults.listeners.onBeforeFormValidate = function (event, items, ParsleyForm) {
    var $form = ParsleyForm.$element,
        ret;
    ret = App.autoCallbacks.runCallback.call($form, 'parsley.form.validate.before',{
        event: event, items: items, parsleyForm: ParsleyForm
    });
    console.log([event, items, ParsleyForm]);
    return ret;
};
$.fn.parsley.defaults.listeners.onFormValidate = function (isFormValid, event, ParsleyForm) {
    var $form = ParsleyForm.$element,
        ret;
    ret = App.autoCallbacks.runCallback.call($form, 'parsley.form.validate.after',{
        event: event, isValid: isFormValid, parsleyForm: ParsleyForm
    });
    console.log([event, isFormValid, ParsleyForm]);
    return ret;
};

$.fn.parsley.defaults.validators.emails = function() {
    return {
        validate: function(val, param) {
            if (param) {
                return App.validateEmails(val, param == 'pass-blank');
            }
            return true;
        },
        priority: 2
    };
};

$.fn.parsley.defaults.messages.emails = "Enter comma separated valid emails";

// pass actions as {'email': ['trim'], 'email_2': ['trim']}
// pass options as {'skipTrimForRequiredFields':true} to skip trim on required fields
App.functions.cleanupForm = function (form, myActionList, options) {
    options = options || {};
    var inputs = form.find(":input"),
        actionList = myActionList || {'email': ['trim'], 'email_2': ['trim']};

    if (inputs.length) {
        inputs.each(function (){
            var elm = $(this),
                elmName = elm.attr('name'),
                required = elm.attr('required'),
                val = elm.val(),
                actions = actionList[elmName];
            if(!options.length && !options.skipTrimForRequiredFields == true){
                if(required !== UNDEFINED){
                    val = App.applyFormats(val, ["trim"], {'form': form});
                    elm.val(val);
                }
            }
            if (actions && actions.length) {
                if (val !== UNDEFINED) {
                    val = App.applyFormats(val, actions, {'form': form});
                    elm.val(val);
                }
            }
        });
    }
};

App.functions.cleanupFormData = function (data, myActionList, options) {
    var i, item, key, val, actions,
        actionList = myActionList || {};

    for(i in data) {
        item = data[i];
        key = item['name'];
        actions = actionList[key];
        if (actionList[key]) {
            val = item['value'];
            val = App.applyFormats(val, actions, {'data': data, 'ajaxOptions': options});
            data[i]['value'] = val;
        }
    }
    return data;
};

App.formats = {
    trim: function (val) {
        var newVal = val;
        if (val && typeof val === 'string') {
            if (val.trim) {
                newVal = val.trim();
            }
            else {
                newVal = val.relpace(/^\s+?|\s+?$/g, '');
            }
        }
        return newVal;
    }
};

App.applyFormats = function (val, formats, options) {
    var formatList = App.formats, i, format;
    for(i in formats) {
        format = formats[i];
        if (formatList[format]) {
            val = formatList[format](val);
        }
    }
    return val;
};

/**
 * Renders select2 based auto-complete supports tag-like multiple entries by default
 * @param jQueryElement elm
 * @param Object options
 * @returns element
 */
App.initAutoComplete = function (elm, options) {
    options = options || {};
    var isAjax = options.ajax || options.url || false,
        defaultData = function (params) { return {
            search: params,
            returnSelf: 1,
        }},
        defaultDataParser = function (data) {
            var s2Data = [], i;
            for(i in data) {
                s2Data.push({'id': data[i], 'text': data[i]});
            }
            return {results:s2Data};
        },
        ajaxOptions = $.extend(true, {}, {
            allowClear: options.allowClear || true,
            delay: options.delay || 500,
            url: options.url || '',
            dataType: options.dataType || 'json',
            cache: options.cache || true,
            results: options.processResults || defaultDataParser,
            data: options.data || defaultData,
        }, options.ajax),

        acOptions = $.extend({}, {
            multiple:true,
            tags: true,
            minimumInputLength: 1,
            separator: ',', // seprator to join multiple values
            tokenSeparators: [',', ' ', ';']
        }, options);

    if (isAjax) {
        acOptions.ajax = ajaxOptions;
    }
    return elm.select2(acOptions);
};


App.validateEmails = function(values, skipBlank) {
    skipBlank = skipBlank === UNDEFINED ? true : skipBlank;
    var emails = App.formats.trim(values || '').split(/[,;]/),
        emailsLength = emails.length,
        email,
        valid = skipBlank,
        i;
    if (emails.length) {
        for(i = 0; i < emailsLength; i++) {
            email = App.formats.trim(emails[i] || '');
            if (skipBlank && !email) {
                continue;
            }
            valid = App.validateEmail(email);
            if (!valid) {
                break;
            }
        }
    }
    return valid;
};
App.validateEmail = function(email) {
    var regExp = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))){2,6}$/i;
    return regExp.test(email);
};

function getCartTotal()
{
    $.ajax({
        url: siteUrl + '/addtocart/cartdata',
        method:'get',
        success:function(data){
            setShopingCartTotal(data);
        }
    });
}
function setShopingCartTotal(data){
    var total = data['shopping_cart_total'] || "0.00";
    $('#nav_cart_total').text('$ '+total);
    if(data['total_cart_items'] > 0){
        $("#update_text_to_add_cart").text(data['total_cart_items']);
    }
}
String.prototype.rtrim = function() {
    var trimmed = this.replace(/0+$/g, '');
    return trimmed;
};

jQuery.fn.fixDecimal = function(places) {
    places = places || 2;
    var val = getFlooredFixed($.trim($(this).val()),5);

    if(val.indexOf('.') == -1){
        val = val+'.00000';
    }
    val = val+'00';
    val = val.slice(0, (val.indexOf("."))+6);
    val = val.split('.');
    var number = 0;
    if(val[1]){
        var fixed = val[1].substring(0, places);
        var decimalSection = (val[1].substring(places)).rtrim();
        number = val[0]+'.'+fixed+''+decimalSection;
    }else{
        number = val[0];
    }
    return number;
};

function getFlooredFixed(v, d){
    //console.log("original number received : "+v);
   var num = (Math.floor((v * Math.pow(10, d)).toFixed(d)) / Math.pow(10, d));
    //console.log("original number : "+num);
    var num2 = num.toFixed(d);
    //console.log("after toFixed number : "+num2);
    return num2;
}

$(document).ready(function(){
    //getCartTotal();

    //to open the link in new tab (Pages)
    var check = $('div[id$="Grid"]');
    if (check.length == 0){
        var url = $(location).attr('href');
        if (url.toLowerCase().indexOf('feg') != -1 || url.toLowerCase().indexOf('core') != -1) {
            $('.sbox-content a').each(function () {
                this.setAttribute('target', '_blank')
            })
        }
    }
});
//need to fix notiation to camel case notation
function getProductSubTypes(productTypeId, productSubTypeSelectorArray, selectBox){


    $.each(productSubTypeSelectorArray, function (key, productSubTypeNameAttr) {

        var tag = '';

        if(selectBox.parent().is('td'))
            tag = 'tr';
        else
            tag = 'div';


        var subTypeSelectBox = '';

        if(selectBox.parent().parent().parent().parent().is('#advance-search'))
            subTypeSelectBox = selectBox.closest(tag).parent().parent().find('select[name="'+productSubTypeNameAttr+'"]');
        else if(selectBox.parent().parent().is('tr'))
            subTypeSelectBox = selectBox.closest(tag+':has(select[name="'+productSubTypeNameAttr+'"])').find('select[name="'+productSubTypeNameAttr+'"]');
        else
            subTypeSelectBox = selectBox.closest(tag).parent().find('select[name="'+productSubTypeNameAttr+'"]');


        if( subTypeSelectBox.length > 0 ){

            console.log(subTypeSelectBox);

            subTypeSelectBox.attr('disabled', 'disabled')

            var selectedSubtypes = subTypeSelectBox.val() == null ? [] : subTypeSelectBox.val();

            $.ajax({
                url: 'product/get-product-subtype?product_type_id='+productTypeId,
                type: 'get',
                success: function(result){
                    subTypeSelectBox.attr('disabled', null);
                    if(typeof selectBox.val() == 'string')
                        subTypeSelectBox.val(null).trigger("change");

                    populateProductSubTypeSelect(subTypeSelectBox, result, selectBox, selectedSubtypes);
                }
            })

        }

    });


}
function populateProductSubTypeSelect(subTypeSelectBox, result, selectBox, selectedSubtypes){
    if(subTypeSelectBox.length > 0){

        subTypeSelectBox.empty();

        if(typeof selectBox.val() == 'string'){
            subTypeSelectBox.append('<option value="" selected style="display: none">-- Select --</option>');
        }

        $.each(result, function (i, item) {

            var selectedOrNot = (selectedSubtypes.length > 0 && selectedSubtypes.indexOf(item.id) != -1) ? 'selected' : '';

            console.log(item.id, item.type_description);
            subTypeSelectBox.append('<option '+selectedOrNot+' value="'+item.id+'">'+item.type_description+'</option>');
        });

        subTypeSelectBox.val(selectedSubtypes).trigger('change.select2');
    }
}

/**
 * global level code which populates product subtype when a product type is selected
 * Covered Areas:
 *  1. Simple Search with support of multiselect
 *  2. Advanced Search
 *  3. Inline editing
 *  4. Product create and edit
 */
$(document).on('change', 'select' ,function () {

    if(pageModule != 'shopfegrequeststore'){
        var nameOfSelectBox = $(this).attr('name');
        var productTypeId = $(this).val();
        var productTypeSelectorsNames = ['prod_type_id', 'Product_Type', 'order_type', 'prod_type_id[]']
        if(productTypeSelectorsNames.indexOf(nameOfSelectBox) != -1){
            var productSubTypeSelectors = [
                "prod_sub_type_id",
                "Product_Sub_Type",
                "product_type",
                "prod_sub_type_id[1]"
            ];
            getProductSubTypes(productTypeId, productSubTypeSelectors, $(this));
        }
    }
});
$(function(){
    $(document).on("click",".collapse-close,.cancelButton",function(){
        $(document).scrollTop(0);
    });
if (window.location.href.indexOf('/product') > -1) {
    $.ajax({
        url: '/product/location-and-groups',
        type: 'GET',
        success: function (response) {
            var optionHTML = response.groups;
            optionHTML += response.locations;
            setTimeout(setExcludeLocationDropdown, 2000, optionHTML);
        }
    });

}

});
var signleAjaxCall = true;
 function productExcludedLocationDropDown(object) {
    $('.ajaxLoading').show();
    if(signleAjaxCall) {
        signleAjaxCall = false;
        reinitfield($(object).attr('data-id'));
        var row = $(object);
        $.ajax({
            url: '/product/location-and-groups/' + row.attr('data-id'),
            type: 'GET',
            success: function (response) {
                var optionHTML = '<option value="select_all">Select All</option>'+response.groups;
                optionHTML += response.locations;
                var selectedValues = response.selectedValues;
                setExcludeLocationDropdown(optionHTML, row.attr('data-id'), selectedValues);
                $('.ajaxLoading').hide();
                signleAjaxCall = true;
            }
        });
    }
}
function reinitfield(id){
    $('tr#form-0 td[data-form="excluded_locations_and_groups"] select').attr({"multiple":'multiple',"name":'excluded_locations_and_groups[]'})
    $('tr#form-0 td[data-form="excluded_locations_and_groups"] select').change();
    $('tr#form-'+id+' td[data-field="excluded_locations_and_groups"] select').attr({"multiple":'multiple',"name":'excluded_locations_and_groups[]'})
    $('tr#form-'+id+' td[data-field="excluded_locations_and_groups"] select').change();
}
var totalAttempts = 0;
function setExcludeLocationDropdown(responseHTML,id,selectedValues){
    totalAttempts = totalAttempts +1;
    if(id){
        $('tr#form-'+id+' td[data-field="excluded_locations_and_groups"] select').attr({"multiple":'multiple',"name":'excluded_locations_and_groups[]'}).addClass("select2");
        $('tr#form-'+id+' td[data-field="excluded_locations_and_groups"] select').select2({
            closeOnSelect: false,
            width: '100%'
        });
        $('tr#form-'+id+' td[data-field="excluded_locations_and_groups"] select').html(responseHTML);
        $('tr#form-'+id+' td[data-field="excluded_locations_and_groups"] select').change();
        if(selectedValues.length > 0) {
            $('tr#form-'+id+' td[data-field="excluded_locations_and_groups"] select').val(selectedValues);
        }
        $('tr#form-'+id+' td[data-field="excluded_locations_and_groups"] select').change();
        return ;
    }
    if($('tr#form-0 td[data-form="excluded_locations_and_groups"] select').length > 0){
        $('tr#form-0 td[data-form="excluded_locations_and_groups"] select').html(responseHTML);
        $('tr#form-0 td[data-form="excluded_locations_and_groups"] select').attr({"multiple":'multiple',"name":'excluded_locations_and_groups[]'})
        $('tr#form-0 td[data-form="excluded_locations_and_groups"] select').change();
    }else{
        if(totalAttempts > 15){
            return false;
        }
        setTimeout(setExcludeLocationDropdown, 2000, responseHTML);
        //setExcludeLocationDropdown(responseHTML);
    }
}

function updateDropdowns(dropdownName){
    $(document).on("change",'select[name="'+dropdownName+'"]',function(){

        var locationDropdownElm = $(this);
        var options  = locationDropdownElm.children('option');

        if(this.value == 'select_all'){

            var dropdownValues = [];

            options.each(function () {
                console.log(this.value);
                if (this.value != 'select_all'){
                    dropdownValues.push(Number(this.value));
                }else {
                    this.value = 'clear_all';
                    this.innerText = 'Clear All';
                    $('.select2-container--default .select2-results>.select2-results__options').children().first().html('Clear All');
                }
            });

            locationDropdownElm.val(dropdownValues);
            locationDropdownElm.change();


        }
        if(this.value == 'clear_all'){
            var dropdownValues = [];
            options.each(function () {
                if (this.value == 'clear_all'){
                    this.value = 'select_all';
                    this.innerText = 'Select All';
                    $('.select2-container--default .select2-results>.select2-results__options').children().first().html('Select All');
                }
            });
            locationDropdownElm.val(dropdownValues);
            locationDropdownElm.change();

        }
    });
}
function updateDropdownsGroups(dropdownName){
    $(document).on("change",'select[name="'+dropdownName+'"]',function(){
        var prevValue = '';
        var locationDropdownElm = $(this);
        var options  = locationDropdownElm.children('optgroup').children('option');
        var clear = this.value;
        if(this.value == 'select_all'){
            var dropdownValues = [];
            options.each(function () {
                if (this.value != 'select_all'){
                    dropdownValues.push(this.value);
                    locationDropdownElm.children('option').val('clear_all');
                    locationDropdownElm.children('option').text('Clear All');
                }else {
                    prevValue = this.value;
                    this.value = 'clear_all';
                    this.innerText = 'Clear All';
                }
            });
            $('.select2-container--default .select2-results>.select2-results__options').children().first().html('Clear All');
            locationDropdownElm.val(dropdownValues);
            locationDropdownElm.trigger('change');
        }
        if(clear == 'clear_all'){
            var dropdownValues = [];
                    locationDropdownElm.children('option').val('select_all');
                    locationDropdownElm.children('option').text('Select All');

            locationDropdownElm.val(dropdownValues);
            locationDropdownElm.trigger('change');
            $('.select2-container--default .select2-results>.select2-results__options').children().first().html('Select All');
        }
    });
}