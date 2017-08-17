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
</div>	
<script>
$(document).ready(function(){
    var searchParams="{{ \Session::get('searchParams') }}";
    var searchParams = searchParams.replace(/&amp;/g, '&');
    if("{{ \Session::get('filter_before_redirect') }}" == "redirect" && searchParams!='') {
     <?php
     if(\Session::has('filter_before_redirect') && \Session::has('filter_before_redirect') == 'redirect')
        {
            \Session::put('filter_before_redirect','no');
        }
     ?>
        reloadData('#{{ $pageModule }}', '/{{ $pageModule }}/data' + searchParams.replace("&amp;", "&"));
    }
    else {
        <?php

        \Session::put('filter_before_redirect','no');
         ?>
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?view=manage');
    }
    <?php
   if(isset($error))
       {
           ?>
           notyMessageError("{{$error}}");
    <?php
    }
    ?>

});	
</script>	
@endsection
