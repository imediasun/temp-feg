/* Sximo builder 
	copyright 2014 . sximo builder com 
*/

jQuery(document).ready(function($){
		
		if($.cookie("sxintheme") != '')
		{
			$('#switchTheme').attr('href',$.cookie("sxintheme"));
		} 		
		if($.cookie("sximo-sidebar") =='minimize-sidemenu'){
			$("body").addClass("minimize-sidemenu");
			$('#sidemenu').removeClass('expanded-menu');
		} else {
			$("body").removeClass("minimize-sidemenu");
			$('#sidemenu').addClass('expanded-menu');
		}
		$(window).bind("load resize", function() {
			if ($(this).width() < 769) {
				$('body').addClass('body-small')
			} else {
				$('body').removeClass('body-small')
			}
		})
      
      /*Return to top*/
      var offset = 220;
      var duration = 500;
      var button = $('<a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>');
      button.appendTo("body");
      
      jQuery(window).scroll(function() {
        offset = Math.min(jQuery(window).height(), 220);
        if (jQuery(this).scrollTop() > offset) {
            jQuery('.back-to-top').fadeIn(duration);
        } else {
            jQuery('.back-to-top').fadeOut(duration);
        }
      });
    
      jQuery('.back-to-top').click(function(event) {
          event.preventDefault();
          jQuery('html, body').animate({scrollTop: 0}, duration);
          return false;
      });

  	$('.switch').bootstrapSwitch();
	$('.date').datepicker({format:'mm/dd/yyyy',autoclose:true})
	$('.datetime').datetimepicker({format: 'mm/dd/yyyy HH:ii:ss P'});
	
	/* Tooltip */
	$('.previewImage').fancybox(
     
    );
	$('.tips').tooltip();	
	$('.editor').summernote();
    renderDropdown($(".select2"), { width:"98%"});
    renderDropdown($(".select-liquid"), {minimumResultsForSearch: "-1"});
	$('.panel-trigger').click(function(e){
		e.preventDefault();
		$(this).toggleClass('active');
	});

	$('.dropdown, .btn-group').on('show.bs.dropdown', function(e){
		$(this).find('.dropdown-menu').first().stop(true, true).fadeIn(100);
	});
	$('.dropdown, .btn-group').on('hide.bs.dropdown', function(e){
		$(this).find('.dropdown-menu').first().stop(true, true).fadeOut(100);
	});
	$('.popup').click(function (e) {
		e.stopPropagation();
	});	
     window.prettyPrint && prettyPrint();

	$(".checkall").click(function() {
		var cblist = $(".ids");
		if($(this).is(":checked"))
		{				
			cblist.prop("checked", !cblist.is(":checked"));
		} else {	
			cblist.removeAttr("checked");
		}	
	});

	$('.nav li ul li.active').parents('li').addClass('active');
	
	
		$('input[type="checkbox"],input[type="radio"]').not('.test').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue'
		});	
		$('.checkall').on('ifChecked',function(){
			$('input[type="checkbox"]').iCheck('check');
		});
		$('.checkall').on('ifUnchecked',function(){
			$('input[type="checkbox"]').iCheck('uncheck');
		});	
    $('.navbar-minimalize').click(function () {
      var w = $("body");
		w.toggleClass("minimize-sidemenu");
			
		if( w.hasClass('minimize-sidemenu'))
		{
			$('#sidemenu').removeClass('expanded-menu');
			$.cookie("sximo-sidebar",'minimize-sidemenu', {expires: 365, path: '/'});
		} else {
			$('#sidemenu').addClass('expanded-menu');
			 $.cookie("sximo-sidebar",'maximaze-sidemenu', {expires: 365, path: '/'});	
		}		
    })

	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){
			if(response.status == 'success')
			{
				
			}
		});
		$(this).parent('div').empty();	
		return false;
	});	
	  	
})
function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this, args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}
;
function addMoreFiles(id){

   $("."+id+"Upl").append('<input type="file" name="'+id+'[]" />')
}

function SximoConfirmDelete( url )
{
	if(confirm('Are u sure deleting this record ? '))
	{
		window.location.href = url;	
	}
	return false;
}
function SximoDelete(  )
{	
	var total = $('input[class="ids"]:checkbox:checked').length;
	if(confirm('Are you sure removing selected rows ?'))
	{
			$('#SximoTable').submit();// do the rest here	
	}	
}
function SximoModal( url , title)
{
	$('#sximo-modal-content').html(' ....Loading content , please wait ...');
	$('.modal-title').html(title);
	$('#sximo-modal-content').load(url,function(){
        var modal = $('#sximo-modal-content').closest('.modal'),
            titletrim = title.replace(/[\s\W]/ig, '').replace(/^\d+?/,'').toLowerCase();
            App.autoCallbacks.runCallback.call(modal, titletrim, {url:url, title:title});
	});
	$('#sximo-modal').modal('show');	
}
function SximoModalShow(modal, callbackName, data) {
	modal.modal('show');
    if (callbackName) {
        App.autoCallbacks.runCallback.call(modal, callbackName, data);   
    }
}
function SximoModalHide(modal, callbackName, data) {    
    if (modal && modal.length) {
        modal.each(function (){
            $(this).modal('hide');
        });
    }	
    if (callbackName) {
        App.autoCallbacks.runCallback.call(modal, callbackName, data);   
    }    
}
function addInactiveItem(field,id,module,check,column,withId)
{
	$.ajax({
		method:'GET',
		url:'vendor/itemcheck',
		data: {
			id:id,
			module:module,
			check:check,
			column:column,
			withId:withId
		}
	}).success(function (item) {
        if(item != 0)
        {
            setTimeout(function()
            {
                console.log(item);
                var option = new Option(item, id);
                option.selected = true;

                $(field).append(option);
                $(field).trigger("change");
            }, 1000);
        }
    })

}
(function ($, window, document, undefined) {

    var pluginName = "sximMenu",
        defaults = {
            toggle: true
        };

    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype = {
        init: function () {

            var $this = $(this.element),
                $toggle = this.settings.toggle;

            $this.find('li.active').has('ul').children('ul').addClass('collapse in');
            $this.find('li').not('.active').has('ul').children('ul').addClass('collapse');

            $this.find('li').has('ul').children('a').on('click', function (e) {
                e.preventDefault();

                $(this).parent('li').toggleClass('active').children('ul').collapse('toggle');

                if ($toggle) {
                    $(this).parent('li').siblings().removeClass('active').children('ul.in').collapse('hide');
                }
            });
        }
    };

    $.fn[ pluginName ] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);
