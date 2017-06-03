<!DOCTYPE html>
<html lang="en">
<head>
@yield('afterheadstart', '')
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
		<link href="{{ asset('sximo/js/plugins/select2/select2.css')}}" rel="stylesheet"/>
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
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/select2/select2.min.js') }}"></script>
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
	</script>
	<script type="text/javascript" src="{{ asset('sximo/js/plugins/ajax/noty/packaged/jquery.noty.packaged.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('sximo/js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('sximo/js/search.js') }}"></script>
    <script type="text/javascript" src="{{ asset('sximo/js/simple-search.js') }}"></script>
    <!-- End Search and storage  -->
    @yield('beforeheadend', '')	
  	</head>
  	<body class="sxim-init" >
    @yield('afterbodystart', '')
	<div id="wrapper">
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
