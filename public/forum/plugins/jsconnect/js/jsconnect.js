jQuery(document).ready(function($) {
var jsUrl = gdn.definition('JsAuthenticateUrl', false);

// add on code to support live and admin login url [15 May 2017] [element5]
// add-on code to support auto change to https [30 May 2017] [element5]
var locationIsLive = /^live\./.test(location.host),
    isHttps = /^https\:/.test(location.protocol);

if (jsUrl) {

    if (isHttps) {
        jsUrl = jsUrl.replace(/^http\:/, 'https:');
    }
    if (locationIsLive) {
        jsUrl = jsUrl.replace('://admin.', '://live.');
    }
    console.log(jsUrl);
   $.ajax({
      url: jsUrl,
      dataType: 'json',
      success: function(data) {
         if (data['error']) {
            $('form').attr('action', gdn.url('/entry/jsconnect/error'));
         } else if (!data['name']) {
//            data = {'error': 'unauthorized', 'message': 'You are not signed in.' };
            $('form').attr('action', gdn.url('/entry/jsconnect/guest'));
         } else {
            for(var key in data) {
               if (data[key] == null)
                  data[key] = '';
            }
         }

         var connectData = $.param(data);
         $('#Form_JsConnect').val(connectData);
         $('form').submit();
      },
      error: function(data, x, y) {
         $('form').attr('action', gdn.url('/entry/jsconnect/error'));
      }
   });
}
   
$.fn.jsconnect = function(options) {
   if (this.length == 0)
      return;
   
   var $elems = this;
   
   // Collect the urls.
   var urls = {};
   $elems.each(function(i, elem) {
      var rel = $(elem).attr('rel');
      
      if (urls[rel] == undefined)
         urls[rel] = [];
      urls[rel].push(elem);
   });
   
   for (var url in urls) {
      var elems = urls[url];

      // Get the client id from the url.
      var re = new RegExp("client_?id=([^&]+)(&Target=([^&]+))?", "g");
      var matches = re.exec(url);
      var client_id = false, target = '/';
      if (matches) {
         if (matches[1])
            client_id = matches[1];
         if (matches[3])
            target = matches[3];
      }
      
      // Make a request to the host page.
      $.ajax({
         url: url,
         dataType: 'json',
         success: function(data, textStatus) {
            var connectUrl = gdn.url('/entry/jsconnect?client_id='+client_id+'&Target='+target);
            if (isHttps) {
                connectUrl = connectUrl.replace(/^http\:/, 'https:');
            }
            if (locationIsLive) {
                connectUrl = connectUrl.replace('://admin.', '://live.');
            }

            var signedIn = data['name'] ? true : false;

            if (signedIn) {
               $(elems).find('.ConnectLink').attr('href', connectUrl);
               $(elems).find('.Username').text(data['name']);
         
               if (data['photourl'])
                  $(elems).find('.UserPhoto').attr('src', data['photourl']);

               $(elems).find('.JsConnect-Connect').show();
               $(elems).find('.JsConnect-Guest').hide();
            } else {
               $(elems).find('.JsConnect-Connect').hide();
               $(elems).find('.JsConnect-Guest').show();
            }
            $(elems).show();
         },
         error: function(data, x, y) {
            $(elems).find('.JsConnect-Connect').hide();
            $(elems).find('.JsConnect-Guest').show();

            $(elems).show();
         }
      });
   }
};

$('.JsConnect-Container').livequery(function() { $(this).jsconnect(); });

});