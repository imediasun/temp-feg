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
    if (session()->has('game_id')) {
        $game_id = \Session::get('game_id');
    } else {
        $game_id = 0;
    } ?>
</div>
	
@endsection

@section ('beforebodyend')

    <script>
        $(document).ready(function () {
            var game_id="{{ $game_id }}";

            if(game_id!=0){
                ajaxViewDetail('#{{ $pageModule }}',"{{url()}}/mylocationgame/show/"+game_id); return false;
                //reloadData('#{{ $pageModule }}','/sximo/public/order/data');
            }
            reloadData('#{{ $pageModule }}','{{ $pageModule }}/data');	
        });	
    </script>
    <script type="text/javascript">
        var pageModule = '{{$pageModule}}',
            pageUrl = '{{$pageUrl}}';
    </script>
    <script type="text/javascript" src="{{ asset('sximo/js/modules/games/view.js') }}"></script>      
    <script type="text/javascript" src="{{ asset('sximo/js/modules/games/form.js') }}"></script>      
    
@endsection
