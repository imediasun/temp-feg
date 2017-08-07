<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
		<script type="text/javascript">
			// checking browser and browser version
			function get_browser() {
				var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
				if(/trident/i.test(M[1])){
					tem=/\brv[ :]+(\d+)/g.exec(ua) || [];
					return {name:'IE',version:(tem[1]||'')};
				}
				if(M[1]==='Chrome'){
					tem=ua.match(/\bOPR|Edge\/(\d+)/)
					if(tem!=null)   {return {name:'Opera', version:tem[1]};}
				}
				M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
				if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
				return {
					name: M[0],
					version: M[1]
				};
			}

			function verifyBrowser() {
				console.log(get_browser().name);
				console.log(get_browser().version);
				switch (get_browser().name){
					case 'Chrome':
						if(get_browser().version<57){
							$('#browser_notification').show();
						}
						break;
					case 'Firefox':
						if(get_browser().version<52){
							$('#browser_notification').show();
						}
						break;
					case 'Safari':
						if(get_browser().version<9){
							$('#browser_notification').show();
						}
						break;
					case 'Opera':
						if(get_browser().version<14){
							$('#browser_notification').show();
						}
						break;
					case 'IE':
						if(get_browser().version<11){
							$('#browser_notification').show();
						}
						break;
					case 'MSIE':
						if(get_browser().version<11){
							$('#browser_notification').show();
						}
						break;
				}
			}

			$( document ).ready(function() {
				verifyBrowser();
			});
		</script>

		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

    <link href="{{ asset('sximo/css/feg_new_styles.css')}}" rel="stylesheet">
	
  	</head>
<body id="login-bg" style="background:url('{{asset("sximo/images/feg-login-bg.jpg") }}');background-size: 100% auto;background-attachment: fixed;background-position: center top;background-size: cover; background-color:#003673">
<a href="#"><div id="browser_notification" style="
		background-color: #d71b21;
		padding: 5px;
		font-size: 18px;
		font-weight: bold;
		color: #FFF;
		text-align: center;
		display: none;
	" onclick="$(this).hide();">Warning, this browser is not supported. Some admin functions may not work correctly.</div></a>
	<div class="middle-box  ">
        <div>

            @yield('content')	
        </div>
    </div>



</body> 
</html>