@extends('layouts.app')

@section('content')

{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
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
        <ul class="nav nav-tabs" style="margin-bottom:10px;">
          <li class="active"><a href="{{ URL::to('core/users')}}"><i class="fa fa-user"></i> Users </a></li>
          <li ><a href="{{ URL::to('core/groups')}}"><i class="fa fa-users"></i> Groups</a></li>
          <li class=""><a href="{{ URL::to('core/users/blast')}}"><i class="fa fa-envelope"></i> Send Email </a></li>
        </ul>        
        
		<div class="resultData"></div>
		<div class="ajaxLoading"></div>
		<div id="{{ $pageModule }}View"></div>
        <div id="{{ $pageModule }}Grid">
            @include("core.users.table")
        </div>
	</div>
	<!-- End Content -->
    <?php
    if(! isset($id)){
        $id= 0;
    }
    ?>
    
</div>
<style>
    .table th.right { text-align:right !important;}
    .table th.center { text-align:center !important;}

</style>
@stop