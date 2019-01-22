@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{asset('sximo/css/jquery.timepicker.css')}}">
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
/*
** Orignal Code
$(document).ready(function(){

	reloadData('#{{ $pageModule }}','{{ $pageModule }}/data');

});*/
var pageModule = '{{$pageModule}}',
        pageUrl = '{{$pageUrl}}',
        viewNewLocationSetupId = @if(empty(@$_GET['view'])) "" @else "{{ \SiteHelpers::encryptID($_GET['view'], true) }}" @endif,
        hasViewNewLocationSetup = viewNewLocationSetupId && viewNewLocationSetupId != 0;

$(document).ready(function(){

    if(hasViewNewLocationSetup){
        ajaxViewDetail('#'+pageModule, pageUrl + "/show/"+viewNewLocationSetupId);
    }
    reloadData('#{{ $pageModule }}','{{ $pageModule }}/data', UNFN, { isBackground: hasViewNewLocationSetup});
});
</script>	
@endsection