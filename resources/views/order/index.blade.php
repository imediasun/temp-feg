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
		<div id="{{ $pageModule }}View"></div>
		<div id="{{ $pageModule }}Grid"></div>
	</div>
	<!-- End Content -->
    <?php
    if(! isset($id)){
        $id= 0;
    }
    ?>

</div>
<script>
$(document).ready(function(){
    var id = "{{ $sid  }}";
    if(id){

        ajaxViewDetail('#order',"{{url()}}/order/update/1/"+id); return false;
       // ajaxViewDetail('#order',"http://demo/sximo/public/order/update/1/"+id); return false;
        //reloadData('#{{ $pageModule }}','/sximo/public/order/data');
    }
    else{
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data');
    }

});
</script>
@endsection
