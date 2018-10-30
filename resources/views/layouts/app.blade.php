<!DOCTYPE html>
<html lang="en">
<head>
@yield('afterheadstart', '')
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="google-site-verification" content="Fykw9_aQCQ8gaf5Ei_mtcIQrtNDyyZIcTP4zkGR2PNw"/>
<title> {{ CNF_APPNAME }} </title>
<meta name="keywords" content="">
<meta name="description" content=""/>
<link rel="shorStcut icon" href="{{ asset('fegpo.png')}}" type="image/x-icon"/>

		<link href="//fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/>
        <link href="{{ asset('sximo/js/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet"/>
		<link href="{{ asset('sximo/js/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css')}}" rel="stylesheet"/>
		<link href="{{ asset('sximo/fonts/awesome/css/font-awesome.min.css')}}" rel="stylesheet"/>
		<link href="{{ asset('sximo/js/plugins/bootstrap.summernote/summernote.css')}}" rel="stylesheet"/>
		<link href="{{ asset('sximo/js/plugins/datepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet"/>
		<link href="{{ asset('sximo/js/plugins/bootstrap.datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet"/>
 		@if($_SERVER['REQUEST_URI']  !== '/ordersetting')
			@if(!str_contains($_SERVER['REQUEST_URI'], ['module', 'feg/config', 'core', 'feg/tables', 'feg/menu', 'user/profile']) &&  in_array($pageModule, ['product','locationgroups', 'location']))
				<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet"/>
			@else
				<link href="/sximo/js/plugins/select2/select2.css" rel="stylesheet" />
			@endif
		@else
			<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		@endif
		<link href="{{ asset('sximo/js/plugins/iCheck/skins/square/blue.css')}}" rel="stylesheet"/>
		<link href="{{ asset('sximo/js/plugins/fancybox/jquery.fancybox.css') }}" rel="stylesheet"/>
        <link href="{{ asset('sximo/css/multi-select.css') }}" rel="stylesheet"/>
		<link href="{{ asset('sximo/css/animate.css')}}" rel="stylesheet"/>
		<link href="{{ asset('sximo/css/icons.min.css')}}" rel="stylesheet"/>
		<link href="{{ asset('sximo/css/bootstrap-select.css')}}" rel="stylesheet"/>
		<link href="{{ asset('sximo/js/plugins/toastr/toastr.css')}}" rel="stylesheet"/>
        <link href="{{ asset('sximo/css/dropzone.css')}}" rel="stylesheet"/>
		<link href="{{ asset('sximo/css/sximo.css')}}" rel="stylesheet"/>
    <link href="{{ asset('sximo/css/bootstrap-switch.css')}}" rel="stylesheet"/>
    <link href="//gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet"/>
        <script type="text/javascript">
            var siteUrl = "{{ url() }}",
                __noErrorReport = {{ env('PREVENT_ERROR_REPORT_PROMPT', false) ? 'true':'false' }};
        </script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery.min.js') }}"></script>

			<script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery.cookie.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery-ui.min.js') }}"></script>

		<script type="text/javascript" src="{{ asset('sximo/js/plugins/iCheck/icheck.min.js') }}"></script>

		@if($_SERVER['REQUEST_URI']  !== '/ordersetting')
			@if(!str_contains($_SERVER['REQUEST_URI'], ['module', 'feg/config', 'core', 'feg/tables', 'feg/menu', 'user/profile']) && in_array($pageModule, ['product','locationgroups', 'location']))
				<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
			@else
				<script type="text/javascript" src="{{ asset('sximo/js/plugins/select2/select2.js') }}"></script>
			@endif
		@else
			<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
		@endif
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/fancybox/jquery.fancybox.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/prettify.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/parsley.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/datepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/switch.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/bootstrap.datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/bootstrap/js/bootstrap.js') }}"></script>
       <script type="text/javascript" src="{{ asset('sximo/js/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/sximo.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery.form.js') }}"></script>
    <script type="text/javascript" src="{{ asset('sximo/js/plugins/dropzone.js') }}"></script>
    <script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery.jCombo.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/toastr/toastr.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/bootstrap.summernote/summernote.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/simpleclone.js') }}"></script>
        <script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery.multi-select.js') }}"></script>
    <script type="text/javascript" src="{{ asset('sximo/js/plugins/bootstrap-switch.js') }}"></script>
    <!-- Latest compiled and minified CSS -->
    <!-- Latest compiled and minified JavaScript -->
       <script type="text/javascript" src="{{ asset('sximo/js/plugins/bootstrap-select.js') }}"></script>

    <!-- AJax -->
		<link href="{{ asset('sximo/js/plugins/ajax/ajaxSximo.css')}}" rel="stylesheet">
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/ajax/ajaxSximo.js') }}"></script>

		<!-- End Ajax -->

		<!--[if lt IE 9]>
			<script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->


	<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	<script src="//cdn.jsdelivr.net/momentjs/2.10.6/moment.min.js"></script>
    <!-- Search and storage  -->
    <link href="{{ asset('sximo/css/search.css')}}" rel="stylesheet">
    <link href="{{ asset('sximo/css/feg_new_styles.css') }}" rel="stylesheet">
	<script>
		//define global level js variables here
		var PREVENT_CONSOLE_LOGS = '{{env('PREVENT_CONSOLE_LOGS')}}';
		var pageModule = '{{$pageModule or ''}}';

	</script>
	<script type="text/javascript" src="{{ asset('sximo/js/plugins/ajax/noty/packaged/jquery.noty.packaged.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('sximo/js/app.js?vertime=2018-04-27-16-28-00') }}"></script>
    <script type="text/javascript" src="{{ asset('sximo/js/search.js?version='.config('app.version')) }}"></script>
    <script type="text/javascript" src="{{ asset('sximo/js/simple-search.js?version='.config('app.version')) }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.min.js"></script>

	@yield('select2Custom')

	<!-- End Search and storage  -->
    @yield('beforeheadend', '')

    @if(!str_contains($_SERVER['REQUEST_URI'], ['module', 'feg/config', 'core', 'feg/tables', 'feg/menu', 'user/profile']) && $pageModule !== 'product')
		<link href="{{ asset('sximo/css/select2-custom.css') }}" rel="stylesheet"/>
    @else
        <link href="{{ asset('sximo/css/select2-custom-for-product.css') }}" rel="stylesheet"/>
    @endif
	<script src="{{ asset('sximo/js/select2-custom.js') }}" ></script>
  	</head>
  	<body class="sxim-init" >
    @yield('afterbodystart', '')
	@if(env('APP_ENV')=='development' || env('APP_ENV')=='staging' || env('APP_ENV')=='demo')
	<div class="debugbarbtn" onmouseover="$(this).css({'left':'0px'},1000);" onmouseout="$(this).css({'left':'-121px'});" onclick="$('.locationDebugBar').toggle('slow');" style="position: fixed; cursor: pointer; top:0px; z-index: 99999999;  font-weight: 700;     background: #ffffff;
    color: #2b2929;
    padding:5px 10px; left: -121px; border-right: 5px solid red;" title="Location DebugBar">Location DebugBar</div>
	<div class="locationDebugBar container" style="min-height:100px;  box-shadow: black 0px 0px 1px inset; max-height: 450px; overflow-y: auto; font-size: 11px; color: black; background: white; top:20px; position: fixed; display: none; width: 100%; z-index: 99999;">
		<?php
		$debuggerData = \App\Library\FEGDBRelationHelpers::getAllExcludedDataDebugger();
		?>
		<div class="row" style="max-height:450px; overflow-y: auto;">
			<div class="col-md-4">
		<h4>Location Groups</h4>
				<div>
					<?php $i=0 ?>
					@foreach($debuggerData['locationGroups'] as $locationGroup)
							<div  style="@if($i%2==0) background:#dfdfdf; @endif padding: 2px; border-bottom: 1px dotted black;" >
						{{ $locationGroup }}
					</div>
						<?php $i++ ?>
						@endforeach

				</div>
			</div>
			<div class="col-md-4">
				<h4>Excluded Product Types for Current Location</h4>
				<div>
					<?php $i=0 ?>
					@foreach($debuggerData['productTypes'] as $productType)
							<div  style="@if($i%2==0) background:#dfdfdf; @endif padding: 2px; border-bottom: 1px dotted black;" >
							{{ $productType }}
						</div>
						<?php $i++ ?>
					@endforeach

				</div>
			</div>
			<div class="col-md-4">
				<h4>Excluded Product for Current Location</h4>
				<div>
					<?php $i=0 ?>
					@foreach($debuggerData['products'] as $product)
						<div  style="@if($i%2==0) background:#dfdfdf; @endif padding: 2px; border-bottom: 1px dotted black;" >
							{{ $product }}
						</div>
						<?php $i++ ?>
					@endforeach

				</div>
			</div>
		</div>
	</div>
	@endif
	<div id="wrapper" {!! (isset($sid) && $sid!='') ? 'style="pointer-events:none"' : '' !!}>
		@include('layouts/sidemenu')
		<div class="gray-bg " id="page-wrapper">
			@include('layouts/headmenu')
			<div class="ajaxLoading"></div>
			@yield('content')
		</div>

		<div class="footer fixed">
		    <div class="pull-right">

		    </div>
		    <div>
		        <strong>Copyright</strong> &copy; 2014-{{ date('Y')}} . {{ CNF_COMNAME }}
		    </div>
		</div>

	</div>

<div class="modal fade" id="sximo-modal" tabindex="-1" role="dialog">
<div class="modal-dialog">
  <div class="modal-content">
	<div class="modal-header bg-default">
		<button type="button " class="btn-xs collapse-close btn btn-danger pull-right" data-dismiss="modal"  aria-hidden="true"><i class="fa fa fa-times"></i></button>
		<h4 class="modal-title">&nbsp;</h4>
	</div>
	<div class="modal-body" id="sximo-modal-content">

	</div>

  </div>
</div>
</div>

<div class="theme-config">
    <div class="theme-config-box">
     <!--   <div class="spin-icon">
            <i class="fa fa-cogs fa-spin"></i>
        </div> -->
        <div class="skin-setttings">
            <div class="title">Select Color Schema</div>
            <div class="setings-item">
                    <ul>
	                    <li><a href="{{ url('home/skin/sximo') }}"> Default Skin  <span class="pull-right default-skin"> </span></a></li>
	                    <li><a href="{{ url('home/skin/sximo-dark-blue') }}"> Dark Blue Skin <span class="pull-right dark-blue-skin"> </span> </a></li>
	                    <li><a href="{{ url('home/skin/sximo-light-blue') }}"> Light Blue Skin <span class="pull-right light-blue-skin"> </span> </a></li>

                    </ul>


            </div>

        </div>
    </div>
</div>

{{ Sitehelpers::showNotification() }}
@yield('beforebodyend', '')
@include('sximo.module.utility.inlinegrid')
@yield('inlinedit', '')
	<div class="custom_overlay"></div>
</body>
@yield('afterbodyend', '')
</html>
