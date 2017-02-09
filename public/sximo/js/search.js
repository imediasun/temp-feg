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
            
            // not required 
            if (!field || name === '_token') {
                return;
            }
                
            // normalize values to '' if null/undefined to avoid unwanted results
            if (value === null || value === UNDEFINED ) {
                value = '';
            }
            if (value2 === null || value2 === UNDEFINED ) {
                value2 = '';
            }

            // cache original value
            cache[field] = {value:value, value2: value2, operator: operate};           

            // convert date format to ISO for serverside consumption
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
					            
            // encode URI if needed
            if(App.needsURIEncoding(value, valueField)){
                value = encodeURIComponent(value);
            }
            // encode URI if needed
            if(App.needsURIEncoding(value2, value2Field)){
                value2 = encodeURIComponent(value2);
            }
            
            // normalize range search to simple search if 
            // one of the range values are not present
            if (operate === 'between') {
                if (value === '' || value2 === '') {
                    operate = "equal";
                }                
                if (value === '' && value2 !== '') {
                    value = value2;
                }
                
            }
			if(value !=='' && value !== UNDEFINED && name !='_token')
			{
				if(operate =='between')
				{
					attr += field+':'+operate+':'+value+':'+value2+'|';
				} 
                else {
					attr += field+':'+operate+':'+value+'|';
				}						
			}
			
		});

    attr += getFooterFilters({'simplesearch': true, 'search': true, 'page': true});
    
    App.search.cache = cache;
    App.lastSearchMode = 'advanced';
    if (ajaxSearch) {
        reloadData(moduleID, url+"/data"+attr);  
    }
    else {
        window.location.href = url+attr;
    }
    
}

function changeSearchOperator( val , field , elm ,type)
{
    var $elm = jQuery(elm),
        grandParent = $elm.closest('tr'),
        fieldElm = grandParent.find('input[name='+field+']'),
        parent = fieldElm.closest('td'),
        fieldElmVal = fieldElm.val(),
        fieldElm2 = parent.find('input[name=' + field + '_end]'),
        fieldElm2Val = fieldElm2.val(),
        dashElement = parent.find('.betweenseparator'),
        previousValue = fieldElm.data('previousValue'),
        previousValue2 = fieldElm.data('previousValue2'),
        previousOperator = fieldElm.data('previousOperator'),
        previousOperatorWasNull = previousOperator && 
            (previousOperator == 'is_null' || previousOperator == 'not_null'),
        operatorIsNull = (val == 'is_null' || val == 'not_null');

    if (!previousOperatorWasNull) {
        fieldElm.data('previousValue', fieldElmVal);
    }
    if (fieldElm2Val !== UNDEFINED && fieldElm2Val !== null) {
        fieldElm.data('previousValue2', fieldElm2Val);
    }
    fieldElm.data('previousOperator', val);

    fieldElm
            .prop('readonly', false)
            .attr('placeholder', '')
            .width('100%')
            .removeClass('pull-left'); 
    
    if (previousOperatorWasNull && !operatorIsNull) {
        if (fieldElm.hasClass('sel-search-multiple') || fieldElm.data('select2')) {
            fieldElm.select2('val', previousValue);            
        }
        else {
            fieldElm.val(previousValue);
        }
        if (fieldElm.hasClass('date')) {           
            fieldElm.datepicker('update');
        }
        if (fieldElm.hasClass('datetime')) {
            fieldElm.datetimepicker('update');                
        }        
    }
    
    if (fieldElm2) {
        fieldElm2.remove();
    }
    if (dashElement) {
        dashElement.remove();
    }    
    
    switch (val) {
        case "is_null":
        case "not_null":
            fieldElm.prop('readonly', true)
                    .val(val);
            break;
        
        case 'between':
            showBetweenFields({
                field: field,
                fieldElm : fieldElm,
                fieldElm2 : fieldElm2,
                previousValue2 : previousValue2,
                dashElement : dashElement
            });
            break;
            
        default:
            break;
    }

}
function showBetweenFields(options) {
    if (!options) {
        options = {};
    }
    var fieldElm = options.fieldElm, 
        field = options.field || fieldElm.attr('name'),
        fieldElm2 = options.fieldElm2, 
        previousValue2 = options.previousValue2, 
        dashElement = options.dashElement;
    
    fieldElm.width('48%')
                .attr('placeholder', 'Start')
                .addClass('pull-left');

    fieldElm2 = jQuery('<input name="'+field+'_end" class="form-control" />')
                .insertAfter(fieldElm);
    fieldElm2.attr('placeholder', "End")
            .addClass('pull-left')
            .width('48%');

//            if (fieldElm.hasClass('sel-search-multiple')) {
//                fieldElm2.addClass('.sel-search-multiple').select2();    
//                fieldElm2.select2('val', previousValue2);            
//            }            
    if (previousValue2 !== UNDEFINED && previousValue2 !== null) {
        fieldElm2.val(previousValue2);
    }            
    if (fieldElm.hasClass('date')) {
        fieldElm2.addClass('date')
                .datepicker({format:'mm/dd/yyyy',autoclose:true});                 
        fieldElm2.datepicker('update');
    }
    if (fieldElm.hasClass('datetime')) {
        fieldElm2.addClass('datetime')
                .datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});    
        fieldElm2.datetimepicker('update');

    }

    dashElement = jQuery('<div class="betweenseparator"> - </div>')
                    .insertAfter(fieldElm);
    dashElement
        .addClass('pull-left')
        .css({
            "margin": "1%",
            "height": "100%",
            "line-height": "2em"
        });    
    
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
    
    makeSimpleSearchFieldsToInitiateSearchOnEnter();

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
