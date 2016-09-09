function performSimpleSearch(params) {
    if (!params) {
        params = {};
    }
    var elm = this, 
        container = $('.simpleSearchContainer'), 
        attr = '?search=', 
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
            cache[fieldName] = value;            
        }

        if(value && isValueDate) {
            value  = $.datepicker.formatDate('yy-mm-dd', new Date(value));
        }                    

        if(value !=='' && typeof value !=='undefined' && fieldName !='_token')
        {
            attr += fieldName+':'+operate+':'+value+'|';
        }

    });

    $('.table-actions :input').each(function () {
        var elm = $(this), 
            fieldName = elm.attr('name'), 
            val = elm.val();
        if (fieldName != 'page' && fieldName != 'search' && val !== '' && val !== null) {
            attr += '&' + fieldName + '=' + val;            
        }
    });        
    
    
    App.simpleSearch.cache = cache;
    App.lastSearchMode = 'simple';
    
    reloadData(params.moduleID,params.url+attr);    
}

$(document).ready(function() {

});          

App.simpleSearch.populateFields = function()  {
    var container = $('.simpleSearchContainer');                
    if (container.length) {
        App.populateFieldsFromCache(container, App.simpleSearch);
    }
};
