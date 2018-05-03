@extends('layouts.app')

@section('content')
    <input type="hidden" id="searchParamsString" value="">
    <script>
        var searchParams;
        $(document).ready(function(){
            searchParams = "{{ \Session::get('searchParamsForManageFEGStore') }}";
            searchParams = searchParams.replace(/&amp;/g, '&');
            var v1 = searchParams.indexOf("v1");
            var v2 = searchParams.indexOf("v2");
            var v3 = searchParams.indexOf("v3");
            if(v1 != -1 || v2 != -1 || v3 != -1) {
                $('.ajaxLoading').show();
                $.ajax({
                    type: "GET",
                    url: "{{ Url('/managefegrequeststore/searchfilterparemsresult') }}" + searchParams,
                    success: function (response) {
                        searchParams = response;
                        if (searchParams != '') {
                            $("#searchParamsString").val(searchParams);
                            <?php
                            if(\Session::has('filter_before_redirect') && \Session::has('filter_before_redirect') == 'redirect')
                               {
                                   \Session::put('filter_before_redirect','no');
                               }
                            ?>
                               reloadData('#{{ $pageModule }}', '/{{ $pageModule }}/data' + searchParams.replace("&amp;", "&"));
                        } else {
                            $("#searchParamsString").val("?view=manage'");
                            <?php \Session::put('filter_before_redirect','no'); ?>
                            reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?view=manage');
                        }

                    }
                });
            }else{
                <?php \Session::put('filter_before_redirect','no'); ?>
                           reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?view=manage');
            }

            <?php
           if(isset($error)) { ?>
                   notyMessageError("{{$error}}");
            <?php } ?>

        });
    </script>
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
</div>


@endsection
