var UNDEFINED, 
    App = {
        lastSearchMode: '',
        autoCallbacks: {},
        search: {cache: {}},
        simpleSearch: {cache: {}},
        columnSort: {cache: {}},
        populateFieldsFromCache: function (container, cacheObject, isComplex) {
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
                                showBetweenFields({
                                    field: elmName,
                                    fieldElm : elm,
                                    fieldElm2 : elm2,
                                    previousValue2 : val2,
                                    dashElement : null
                                });                            
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

function initiateSearchFormFields(container) {
    container.find('.date').datepicker({format:'mm/dd/yyyy',autoclose:true});
    container.find('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});    
    container.find('.sel-search-multiple').select2();
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

function updateNativeUIFieldsBasedOn(options) {
    
}

function makeSimpleSearchFieldsToInitiateSearchOnEnter() {
    //$('.simpleSearchContainer')
    console.log('simple search field interactivity config');
}
