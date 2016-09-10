function performAdvancedSearch(params) {
    if (!params) {
        params = {};
    }
    var elm = this, 
        container = params.container || $('#advance-search'), 
        attr = '?search=', 
        moduleID = params.moduleID,
        url = params.url,
        ajaxSearch = params.ajaxSearch,
        cache = {};

        container.find(' tr.fieldsearch').each(function(i){
			var UNDEFINED,                 
                trcontainer = this,
                jQcontainer = $(trcontainer),                
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
        reloadData(moduleID,url+"/data"+attr);  
    }
    else {
        window.location.href = url+attr;
    }
    
}

function changeSearchOperator( val , field , elm )
{
	if(val =='is_null') {
		$('input[name='+field+']').attr('readonly','1');
		$('input[name='+field+']').val('is_null');
	} else if(val =='not_null') {
		$('input[name='+field+']').attr('readonly','1');
		$('input[name='+field+']').val('not_null');		

	} else if(val =='between') {
		html = '<input name="'+field+'" class="date form-control" placeholder="Start" style="width:100px;"  /> -  <input name="'+field+'_end" class="date form-control"  placeholder="End" style="width:100px;"    />';
		$('#field_'+field+'').html(html);
	} else {
		$('input[name='+field+']').removeAttr('readonly');
		$('input[name='+field+']').val('');	
	}
}

App.autoCallbacks.reloaddata = function(params) {
    if (!params) {
        params = {};
    }    
    if (params.isClear) {
        App.search.cache = {};
        App.simpleSearch.cache = {};
    }
    else {
        $(".sbox-tools a.tips").addClass('btn-search');
        if (App.lastSearchMode == 'simple') {
            App.simpleSearch.populateFields();  
        }
        else {

        }        
    }    

};
App.autoCallbacks.ajaxinlinesave = function(params) {
    
};
App.autoCallbacks.advancedsearch = function(params) {
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
};



function changeSearchOperator_new(operatorValue, field, elm)
{
    var $elm = $(elm),
        container = $elm.closest('tr.fieldsearch'),
        fieldElement = container.find("[name="+field+"]"),
        fieldValue = fieldElement.val(),
        field2Element = container.find("[name="+field+"_end]"),
        field2Value = field2Element.length && field2Element.val(),
        cacheValue1 = fieldElement.data('cachedValue'),
        cacheValue2 = field2Element.length && field2Element.data('cachedValue'),
        cacheOperator = $elm.data('cachedValue'),
        html;
    
    fieldElement.data('cachedValue', fieldValue);
    $elm.data('cachedValue', operatorValue);
    if (field2Element.length) {
        field2Element.data('cachedValue', field2Value);
    }
    
    switch (operatorValue) {
        case 'is_null':
            fieldElement.prop('readonly',true);
            fieldElement.val('is_null');            
            break;
        case 'not_null':
            fieldElement.prop('readonly', true);
            fieldElement.val('not_null');            
            break;
        case 'between':
            html = '<input name="'+field+'" class="date form-control" placeholder="Start" style="width:100px;"  /> -  <input name="'+field+'_end" class="date form-control"  placeholder="End" style="width:100px;"    />';
            $('#field_'+field+'').html(html);            
            break;
        default:
            fieldElement.prop('readonly', false);
            fieldElement.val('');	            
            break;
    }	
    
    
    
}


