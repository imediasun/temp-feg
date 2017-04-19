function performSimpleSearch(params) {
    if (!params) {
        params = {};
    }
    var elm = this, 
        container = params.container || $('.simpleSearchContainer'), 
        attrArray = {},
        attr = '',
        moduleID = params.moduleID,
        url = params.url,
        ajaxSearch = params.ajaxSearch !== false,
        searchAttr = '',
        searchAttr2 = '',
        cache = {};

    container.find('.form-control').each(function(i){

        var UNDEFINED, 
            elm = this,
            valueField = $(elm),
            fieldName = valueField.attr('name'),
            isRangeEndField = valueField.attr('data-range-end-field') == '1',
            operate = valueField.attr('data-simpleSearchOperator') || "equal",
            isRangeEndField,
            value = valueField.hasClass('checkbox')?valueField.is(':checked')?1:0:valueField.val(),
            isValueDate = valueField.hasClass('date'),
            isValueDateTime = valueField.hasClass('datetime');

        // normalize field name for range end field
        if (isRangeEndField) {
            fieldName = fieldName.replace(/\_end$/, '');
        }
        
        // normalize values to '' if null/undefined to avoid unwanted results
        if (value === null || value === UNDEFINED ) {
            value = '';
        }
        
        // not required to be included
        if (!fieldName || fieldName === '_token' || value === '') {
            return;
        }        
        
        // cache original values
        if (cache[fieldName] === UNDEFINED) {
            cache[fieldName] = {operator: operate};
        }
        if (isRangeEndField) {                    
            cache[fieldName].value2 = value;
        }
        else {
            cache[fieldName].value = value;
            if (cache[fieldName].value2 === UNDEFINED) {
                cache[fieldName].value2 = null;
            }
        }            
        
        // convert date format to ISO for serverside consumption
        if(value && isValueDate) {
            value = $.datepicker.formatDate('yy-mm-dd', new Date(value));
        }
        
        // encode URI if needed
        if(App.needsURIEncoding(value, valueField)){
            value = encodeURIComponent(value);
        }
        
        // store querystring specific values
        if(value !== '' && value !== UNDEFINED && value !== null)
        {
            if (!attrArray[fieldName]) {
                attrArray[fieldName] = {};
            }
            attrArray[fieldName].operator = operate;
            
            if (isRangeEndField) {                
                attrArray[fieldName].endValue = value;
            }
            else {
                attrArray[fieldName].value = value;
            }            
        }

    });
    
    // build search specific querystring 
    searchAttr = App.buildSearchQueryFromArray(attrArray);
    
    // build final querystring
    attr = '?simplesearch=1&search=' + searchAttr + 
            getFooterFilters({'simplesearch': true, 'search': true, 'page': true});
    
    // store cache
    App.simpleSearch.cache = cache;
    // set Search Mode 
    App.lastSearchMode = 'simple';

    // fetch data
    if (ajaxSearch) {
        reloadData(moduleID, url+ '/data' + attr);    
    }
    else {
        window.location.href = url+attr;
    }

}

$(document).ready(function() {

});          

App.simpleSearch.populateFields = function()  {
    var container = $('.simpleSearchContainer');                
    if (container.length) {
        App.populateFieldsFromCache(container, App.simpleSearch);
    }
};
