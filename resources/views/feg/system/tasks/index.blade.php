@extends('layouts.app')

@section('content')
<div class="page-content row {{ $pageModule }}Container">
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
		<div class="ajaxLoading"></div>
		<div id="{{ $pageModule }}View"></div>			
        <div id="{{ $pageModule }}Grid">
            @include('feg.system.tasks.table') 
        </div>
	</div>	
	<!-- End Content -->  
</div>	
<script>
$(document).ready(function(){
	//reloadData('#{{ $pageModule }}','{{ $pageModule }}/data');	
});	
</script>	
@endsection
@section ('beforebodyend')


    <script type="text/javascript">
        var pageModule = '{{$pageModule}}',
            pageUrl = '{{$pageUrl}}';
    </script>  
    <script type="text/javascript" src="{{ asset('sximo/js/elm5tasks.js') }}"></script>  
@append
@endsection
