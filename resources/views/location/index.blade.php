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
<?php
        if(! isset($id)){
            $id= 0;
        }
?>
<script>
$(document).ready(function(){
    var id = {{ $id  }};
    if(id && id != 0){
        ajaxViewDetail('#{{ $pageModule }}',"{{$pageUrl}}/show/"+id); 
    }
    reloadData('#{{ $pageModule }}','{{$pageUrl}}/data');
});	
</script>	
@endsection