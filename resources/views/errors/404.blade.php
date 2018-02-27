<html>
<head>
    <title>{{ CNF_APPNAME }}</title>
    <link rel="shortcut icon" href="{{ asset('fegpo.png')}}" type="image/x-icon">
    <link href="{{ asset('sximo/js/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{ asset('sximo/css/sximo.css')}}" rel="stylesheet">
    <link href="{{ asset('sximo/css/animate.css')}}" rel="stylesheet">

    <link href="sximo/css/icons.min.css" rel="stylesheet">
    <link href="sximo/fonts/awesome/css/font-awesome.min.css" rel="stylesheet">
    <script src="//use.edgefonts.net/source-sans-pro.js"></script>

    <script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('sximo/js/plugins/parsley.js') }}"></script>
    <script type="text/javascript" src="{{ asset('sximo/js/plugins/bootstrap/js/bootstrap.js') }}"></script>
    <link href="{{ asset('sximo/css/feg_new_styles.css')}}" rel="stylesheet">
</head>
<body id="login-bg"
      style="background:url('{{asset("sximo/images/feg-login-bg.jpg") }}');background-size: 100% auto;background-attachment: fixed;background-position: center top;background-size: cover; background-color:#003673">
<div class="middle-box  " style="width: 550px; margin-left:-275px; font-size: 16px;">
    <div>
        <div class="text-center">
            <img src="{{ asset('sximo/images/feg_new_logo.png') }}"/>
            <img id="logo-bar" src="{{ asset('sximo/images/logo_bar.png') }}" class="full-width"/>
        </div>
        <div class="sbox ">
            <div class="sbox-content">
                <div class="alert alert-warning  fade in block-inner" id='tempError'>
                    <button class="close" onclick="window.location='/order'; " type="button">&times;</button>
                    <i class="icon-warning" style="vertical-align: text-bottom;"></i> &nbsp;Something went wrong. We are
                    trying to get back.
                </div>
            </div>
        </div>
    </div>


</div>
</body>
</html>
{!! $errorMessage !!}