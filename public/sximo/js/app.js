var UNDEFINED, 
    UNFN = function () {},
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
                        if (elm.hasClass('sel-search-multiple')) {
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
                        if (elm2.hasClass('sel-search-multiple')) {
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
    container.find('.sel-search-multiple').each(function(i, elm) {
        var $elm = $(elm);
        if (!$elm.data('select2')) {
            $elm.select2();
        }
    });    
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
}

function autoSetMainContainerHeight() {
    var setHeight = function (){
            var height = Math.max($(window).height() - $(".footer").outerHeight(), $(".navbar-default").height());
            $("#page-wrapper").css({
                'min-height': height + 'px',
                'height': 'auto',
                'transition' : 'height 0s'
            });                    
        };
    window.setTimeout(setHeight, 1000);
    $('nav.navbar-default').on('hidden.bs.collapse', setHeight);
    $('nav.navbar-default').on('shown.bs.collapse', setHeight);    
    $(window).resize(setHeight);
}

jQuery(document).ready(function($){
    // Adjust main panel's height based on overflowing nav-bar
    autoSetMainContainerHeight();
});

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
