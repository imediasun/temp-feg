App.autoCallbacks.reloaddata = function() {
    if (App.lastSearchMode == 'simple') {
        App.simpleSearch.populateFields();  
    }
    else {

    }
};
App.autoCallbacks.ajaxinlinesave = function() {
    
};
App.autoCallbacks.advancesearch = function() {
    var modal = this, searchButton = modal.find('.doSearch');
    searchButton.click(function(){
        App.lastSearchMode = 'advanced';
    });
    App.search.populateFields(modal);    
};
App.autoCallbacks.columnselector = function() {
    
};


App.search.populateFields = function(modal) {
    App.populateFieldsFromCache(modal, App.search, true);
}