function performSimpleSearch(params) {

    if (!params) {
        params = {};
    }
    var elm = this, 
        container = params.container || $('.simpleSearchContainer'), 
        attr = '?simplesearch=1&search=',
        moduleID = params.moduleID,
        url = params.url,
        ajaxSearch = params.ajaxSearch !== false,
        cache = {};

    container.find('.form-control').each(function(i){

        var elm = this,
            valueField = $(elm),                
            fieldName = valueField.attr('name'),                
            operate = valueField.attr('data-simpleSearchOperator') || "equal",
            value = valueField.val(),
            isValueDate = valueField.hasClass('date'),
            isValueDateTime = valueField.hasClass('datetime');

        if (value === null || value === UNDEFINED ) {

            value = '';
        }
        else
        {
            if(value != "")
            {
               value=String(value);
                if(value.includes('&'))
                {
                    value=value.replace(/&/g, '_amp');
                }
            }
            alert(value);
        }
        alert(value);

        if (fieldName) {            
            cache[fieldName] = {value:value, value2: null, operator: operate};
        }

        if(value && isValueDate) {
            value  = $.datepicker.formatDate('yy-mm-dd', new Date(value));
        }                    

        if(value !=='' && typeof value !=='undefined' && fieldName !='_token')
        {
            attr += fieldName+':'+operate+':'+value+'|';
        }

    });

    attr += getFooterFilters({'simplesearch': true, 'search': true, 'page': true});
        //alert(attr);
    App.simpleSearch.cache = cache;
    App.lastSearchMode = 'simple';
    alert(attr);
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
