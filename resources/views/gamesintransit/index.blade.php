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
		<div id="{{ $pageModule }}View" class="moduleView"></div>			
		<div id="{{ $pageModule }}Grid" class="moduleGrid"></div>
	</div>	
	<!-- End Content -->  
</div>	
@endsection

@section ('beforebodyend')

    <script type="text/javascript">
        var pageModule = '{{$pageModule}}',
            pageUrl = '{{$pageUrl}}',
            siteUrl = '{{ url() }}';
    
        $(document).ready(function () {
            App.autoCallbacks.registerCallback('reloaddata', function(){
                App.modules.gamesintransit.grid.init({
                    'container': $('#'+pageModule+'Grid'),
                    'moduleName': pageModule,
                    'url': pageUrl
                });      
            });
            reloadData('#{{ $pageModule }}','{{ $pageModule }}/data');	
        });
            
    </script>
    <script type="text/javascript" src="{{ asset('sximo/js/modules/games/view.js') }}"></script>      
    <script type="text/javascript" src="{{ asset('sximo/js/modules/games/form.js') }}"></script>      
    <script type="text/javascript" src="{{ asset('sximo/js/modules/games/intransit/grid.js') }}"></script> 
    
@endsection
