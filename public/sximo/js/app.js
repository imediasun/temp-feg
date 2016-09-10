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
            attr = getFooterFiltersWithoutSort(),
            allAttr = attr + ('&sort=' + field + '&order=' + nextOrder);
            
        reloadData('#'+module, url+'/data?colheadersort=1' + allAttr);
        
    });
}

