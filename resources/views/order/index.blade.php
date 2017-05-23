@extends('layouts.app')

@section('content')

<div class="page-content row">
  <!-- Begin Header & Breadcrumb -->
    <div class="page-header">
      <div class="page-title">
        <h3> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>
    </div>
	<!-- End Header & Breadcrumb -->

	<!-- Begin Content -->
	<div class="page-content-wrapper m-t">
		<div class="resultData"></div>
        <div id="{{ $pageModule }}View"></div>
		<div id="{{ $pageModule }}Grid"></div>
	</div>
	<!-- End Content -->
    <?php
        //echo \Session::get('filter_before_redirect');
    if(! isset($id)){
        $id= 0;
    }
    ?>

</div>
<script>
$(document).ready(function(){
    var id = "{{ $sid  }}";
    if(id){
        ajaxViewDetail('#order',"{{url()}}/order/update/1/"+id); return false;
       // ajaxViewDetail('#order',"http://demo/sximo/public/order/update/1/"+id); return false;
        //reloadData('#{{ $pageModule }}','/sximo/public/order/data');
    }
    else{
        var searchParams="{{ \Session::get('searchParams') }}";
        if("{{ \Session::get('filter_before_redirect')}}" == "redirect")
        {
           <?php if(\Session::has('filter_before_redirect') && \Session::has('filter_before_redirect') == 'redirect')
            {
            \Session::put('filter_before_redirect','no');
            }
            ?>
            reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data' + searchParams.replace("&amp;", "&"));
        }
        else
        {
            <?php
     \Session::put('filter_before_redirect','no');
     ?>
            reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data');
        }
    }

});

App.autoCallbacks.registerCallback('ajaxview.before', function (eventData){
    var url = eventData.url,
        id,
        checkUrl,
        isEdit = /\/order\/update\/[^0][^\/]/.test(url);

    if (isEdit) {
        id = url.split('/').pop().replace(/[^\d]/, '');
        checkUrl = siteUrl + '/order/check-editable/'+id;

        blockUI();
        $.ajax({
            type: "GET",
            url: checkUrl,
            success: function (data) {
                if(data.status === 'success'){
                    unblockUI();
                    if (eventData && eventData.callback) {
                        eventData.callback();
                    }
                }
                else {
                    if (data.action && data.action =='clone') {
                            App.notyConfirm({
                                message: data.message,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',
                                confirm: function (){
                                    $.ajax({
                                        type: "GET",
                                        url: data.url,
                                        success: function (cdata) {
                                            if (cdata && cdata.status=='success') {
                                                notyMessage(cdata.message);
                                                $(".orderTableReload").click();
                                                ajaxViewDetail("#order", cdata.editUrl);
                                            }
                                            else {
                                                notyMessageError(cdata.message);
                                            }
                                            unblockUI();                                            
                                        }
                                    });
                                },
                                cancel: function (){
                                    unblockUI();
                                }
                            });
                    }
                    else {
                        notyMessageError(data.message);
                        unblockUI();
                    }
                }
            }
        });
    }
    else {
        if (eventData && eventData.callback) {
            eventData.callback();
        }
    }
});


</script>
@endsection
