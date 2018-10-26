/**
 * Created by shayan on 10/25/2018.
 */
$(function () {

    /*$(".custom-select2").select2({
        closeOnSelect : false,
        placeholder : "Placeholder",
        allowHtml: true,
        allowClear: true,
        tags: true
    });*/

    $(document).on('click','.select2-result-label',function(){
       console.log('clicked');
    });


})

function iformat(icon, badge) {
    var originalOption = icon.element;
    var originalOptionBadge = $(originalOption).data('badge');

    return $('<span><i class="fa ' + $(originalOption).data('icon') + '"></i> ' + icon.text + '<span class="badge">' + originalOptionBadge + '</span></span>');
}