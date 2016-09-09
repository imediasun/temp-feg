function performSimpleSearch(params) {
    if (!params) {
        params = {};
    }
    var elm = this, 
        container = params.container || $('.simpleSearchContainer'), 
        attr = '?search=',
        moduleID = params.moduleID,
        url = params.url,        
        cache = {};

    container.find('.form-control').each(function(i){

        var elm = this,
            valueField = $(elm),                
            fieldName = valueField.attr('name'),                
            operate = "equal",
            value = valueField.val(),
            isValueDate = valueField.hasClass('date'),
            isValueDateTime = valueField.hasClass('datetime');

        if (value === null || value === UNDEFINED ) {
            value = '';
        }
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

    attr += getFooterFilters(true, true);
    
    App.simpleSearch.cache = cache;
    App.lastSearchMode = 'simple';
    
    reloadData(moduleID,url+attr);    
}

$(document).ready(function() {

});          

App.simpleSearch.populateFields = function()  {
    var container = $('.simpleSearchContainer');                
    if (container.length) {
        App.populateFieldsFromCache(container, App.simpleSearch);
    }
};
