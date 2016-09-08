var UNDEFINED, 
    App = {
        lastSearchMode: '',
        autoCallbacks: {},
        search: {cache: {}},
        simpleSearch: {cache: {}},
        columnSort: {cache: {}},
        populateFieldsFromCache: function (container, cacheObject) {
            var cache = cacheObject.cache, elmName, elm, val;
            if (cache) {            
                for(elmName in cache) {
                    elm = container.find('.form-control[name=' + elmName + ']');
                    if (elm.length) {
                        val = cache[elmName];
                        if (elm.hasClass('sel-search')) {
                            elm.select2('val', val);
                        }
                        else {
                            elm.val(val);
                        }
                    }                    
                }
            }                    
        }
    };



