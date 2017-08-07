@extends('layouts.app')

@section('content')
<div class="page-content row {{ $pageModule }}Page">
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
	<div class="page-content-wrapper m-t clearfix">
		<div class="resultData"></div>
		<div id="{{ $pageModule }}View"></div>			
		<div id="{{ $pageModule }}Grid"></div>
	</div>	
	<!-- End Content -->  
   
    <div class='panel panel-primary testInputs m-lg clearfix'>
        <div class="panel-heading">Test here</div>
          <div class="panel-body white-bg">
            {!! Form::open(array('url'=>'feg/system/systememailreportmanager/test', 'class'=>'form-horizontal','files' => false, 'id'=> 'systemreportsemailmanagerTestFormAjax')) !!}
                
                {!! Form::text('test_report_name', '', array('class'=>'form-control m-b-xs', 'placeholder'=>'Report Name')) !!}
                {!! Form::text('test_location', '', array('class'=>'form-control m-b-xs', 'placeholder'=>'Location ID (optional)')) !!} 
                <button type="submit" class="btn btn-primary btn-sm testSubmit">Test</button>
            {!! Form::close() !!}                   
          </div>
          <div class="panel-footer testOutput" style='overflow-wrap: break-word; word-wrap: break-word;'>Result: </div>        
    </div>
    <div class='panel clearfix'>
        
    </div>
</div>	
<script>
    
    var UNDEFINED,         
        users = <?php echo json_encode($users); ?>,
        userGroups = <?php echo json_encode($userGroups); ?>,
        usersPerGroup = <?php echo json_encode($usersPerGroup); ?>,
        locationContactNames = <?php echo json_encode($locationContactNames); ?>;
    
$(document).ready(function(){
	reloadData('#{{ $pageModule }}','{{ $pageModule }}/data');	
});	



(function (){
    var form = $('#systemreportsemailmanagerTestFormAjax');
//    $('.testSubmit').click(function (e){
//        e.preventDefault();
//        return false;
//    });
    form.submit(function(e){
//		e.preventDefault();
        var options = { 
            dataType:      'json', 
            success:       function (data) {
                console.log(data);
                var out = data || {},
                    to = (out.to || '').split(',').join(', '),
                    cc = (out.cc || '').split(',').join(', '),
                    bcc = (out.bcc || '').split(',').join(', '),
                    html = "<p><strong>TO:</strong> " + to + "</p>" +
                    "<p><strong>CC:</strong> " + cc + "</p>" +
                    "<p><strong>BCC:</strong> " + bcc + "</p>" ;
                
                $('.testOutput').html(html);
            }  
        }  
        $(this).ajaxSubmit(options); 
        return false;

	});    
    
}());    
</script>	
@endsection