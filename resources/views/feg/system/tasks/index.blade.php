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
    <span class='label label-success'>Now: {!! date('Y-m-d H:i:s') !!}</span>
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
@section ('beforeheadend')

<link href="{{ asset('sximo/css/elm5-tasks.css') }}" rel="stylesheet" type="text/css"/>

@endsection
@section ('beforebodyend')


    <script type="text/javascript">
        var pageModule = '{{$pageModule}}',
            pageUrl = '{{$pageUrl}}',
            tasksList = [];
        @if (!empty($rowData)) 
            tasksList = <?php echo json_encode($rowData); ?>;
        @endif
    </script>
    <!--<script type="text/javascript" src="{{ asset('sximo/js/plugins/ajax/noty/jquery.noty.js') }}"></script>-->  
    <script type="text/javascript" src="{{ asset('sximo/js/plugins/ajax/noty/packaged/jquery.noty.packaged.min.js') }}"></script>  
    <script type="text/javascript" src="{{ asset('sximo/js/plugins/moment/moment.min.js') }}"></script>  
    <script type="text/javascript" src="{{ asset('sximo/js/plugins/later/later.min.js') }}"></script>  
    <script type="text/javascript" src="{{ asset('sximo/js/plugins/prettycron/prettycron.js') }}"></script>  
    <script type="text/javascript" src="{{ asset('sximo/js/elm5tasks.js') }}"></script>  

@endsection
