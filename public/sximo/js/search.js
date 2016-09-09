function performAdvancedSearch(params) {
    if (!params) {
        params = {};
    }
    var elm = this, 
        container = params.container || $('.#advance-search'), 
        attr = '?search=', 
        moduleID = params.moduleID,
        url = params.url,
        ajaxSearch = params.ajaxSearch,
        cache = {};

        container.find(' tr.fieldsearch').each(function(i){
			var UNDEFINED,                 
                container = this,
                jQcontainer = $(container),                
                field = jQcontainer.attr('id'),
                name = jQcontainer.attr('name'),                
                operatorField = jQcontainer.find('#'+field+'_operate'),
                operate = operatorField.val(),
                valueField = jQcontainer.find("[name="+field+"]"),
                value = valueField.val(),
                value2Field = jQcontainer.find("[name="+field+"_end]"),
                value2 = value2Field.val(),
                isValueDate = valueField.hasClass('date'),
                isValue2Date = value2Field.hasClass('date'),
                isValueDateTime = valueField.hasClass('datetime'),
                isValue2DateTime = value2Field.hasClass('datetime');
                
                if (value === null || value === UNDEFINED ) {
                    value = '';
                }
                if (value2 === null || value2 === UNDEFINED ) {
                    value2 = '';
                }
                if (field && name !='_token') {
                    cache[field] = {value:value, value2: value2, operator: operate};;            
                }                
				if(value && isValueDate) {
                    value  = $.datepicker.formatDate('yy-mm-dd', new Date(value));
                }                    
				if(value2 && isValue2Date) {
                    value2  = $.datepicker.formatDate('yy-mm-dd', new Date(value2));
                }                    
				if(value && isValueDateTime) {
                    //value  = $.datepicker.formatDate('mm/dd/yy hh:ii:ss', new Date(value));
                }                    
				if(value && isValue2DateTime) {
                    //value2  = $.datepicker.formatDate('mm/dd/yy hh:ii:ss', new Date(value2));
                }                    
					            
			if(value !=='' && typeof value !=='undefined' && name !='_token')
			{
				if(operate =='between')
				{
					attr += field+':'+operate+':'+value+':'+value2+'|';
				} else {
					attr += field+':'+operate+':'+value+'|';
				}						
			}
			
		});

    attr += getFooterFilters(true, true);
    
    App.search.cache = cache;
    App.lastSearchMode = 'advanced';
    if (ajaxSearch) {
        reloadData(moduleID,url+attr);  
    }
    else {
        window.location.href = url+attr;
    }
    
}

App.autoCallbacks.reloaddata = function() {
    if (App.lastSearchMode == 'simple') {
        App.simpleSearch.populateFields();  
    }
    else {

    }
};
App.autoCallbacks.ajaxinlinesave = function() {
    
};
App.autoCallbacks.advancedsearch = function() {
    var modal = this, searchButton = modal.find('.doSearch');
    searchButton.click(function(){
        App.lastSearchMode = 'advanced';
    });
    App.search.populateFields(modal);    
};
App.autoCallbacks.advancesearch = function() {
    App.autoCallbacks.advancedsearch.call(this);
};
App.autoCallbacks.columnselector = function() {
    
};


App.search.populateFields = function(modal) {
    App.populateFieldsFromCache(modal, App.search, true);
}