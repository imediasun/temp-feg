<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Hello {{ $firstname }} , </h2>
		<p> Thank your for joining our site </p>
		<p> Bellow is your account Info </p>
		<p>
			Email : {{ $email }} <br />
			Password : {{ $password }}<br />
		</p>
		<p> Please follow this link to activate your account  <a href="{{ URL::to('user/activation?code='.$code) }}"> Active my account now</a></p>
		<p> If the link is not working, copy and paste link below into your browser.</p>
		<p> {{ URL::to('user/activation?code='.$code) }} </p> 
		<br /><br /><p> Thank You </p><br /><br />
		
		{{ CNF_APPNAME }} 
	</body>
</html>