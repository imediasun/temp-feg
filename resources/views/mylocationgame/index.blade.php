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
		<div class="ajaxLoading"></div>
		<div id="{{ $pageModule }}View" class="moduleView"></div>			
		<div id="{{ $pageModule }}Grid" class="moduleGrid"></div>
	</div>	
	<!-- End Content -->
    <?php

    $game_id = \Request::get('gamedetails');
    if (!isset($game_id) && session()->has('game_id')) {
        $game_id = \Session::get('game_id');
    }
    if (empty($game_id)) {        
        $game_id = 0;
    } 
        
    ?>
</div>
	
@endsection

@section ('beforebodyend')

    <script type="text/javascript">
        var pageModule = '{{$pageModule}}',
            pageUrl = '{{$pageUrl}}';
    
        $(document).ready(function () {
            var game_id="{{ $game_id }}";
            if(game_id && game_id != 0){
                ajaxViewDetail('#{{ $pageModule }}',"{{url()}}/mylocationgame/show/"+game_id); 
                //return false;
            }
            App.autoCallbacks.registerCallback('reloaddata', function(){
                App.modules.games.grid.init({
                    'container': $('#'+pageModule+'Grid'),
                    'moduleName': pageModule,
                    'url': pageUrl
                });                
            });
            reloadData('#{{ $pageModule }}','{{ $pageModule }}/data');	            
        });	

    </script>
    <script type="text/javascript" src="{{ asset('sximo/js/modules/games/grid.js') }}"></script>      
    <script type="text/javascript" src="{{ asset('sximo/js/modules/games/view.js') }}"></script>      
    <script type="text/javascript" src="{{ asset('sximo/js/modules/games/form.js') }}"></script>      
    
@endsection
