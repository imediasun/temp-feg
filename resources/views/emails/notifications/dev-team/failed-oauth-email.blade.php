<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p> Id : {{$user->id}}<br>
			Email : {{ $user->email }} <br />
			First Name : {{ $user->first_name }}<br />
			Last Name : {{ $user->last_name }}<br />
			OAuth Token : {{$user->oauth_token}}<br>
			Refresh Token : {{$user->refresh_token}}<br>
			Oauth Email : {{$user->oauth_email}}<br>
			OAuth Refreshed at: {{$user->oauth_refreshed_at}}
		</p>
		<p> Exception Message : </p>
		<div>
			{{ $e->getMessage() }}
		</div>

	</body>
</html>