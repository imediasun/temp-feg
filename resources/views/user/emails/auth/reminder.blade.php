<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
<h5>Hello FEG Team,</h5>

<p>
	The new admin will be ready for your use as of 8:00 AM Eastern Time. Because this admin will be using a new database, your old admin credentials will not work. Your login will be your FEGLLC.com email address. To set your password, please click the link below.
</p>

<div>
	@if(isset($token))
		<a href="{{ URL::to('user/reset?token='.$token) }}">{{ URL::to('user/reset?token='.$token) }}</a>
	@elseif(isset($id))
		<a href="{{ URL::to('user/reset?id='.$id) }}">{{ URL::to('user/reset?id='.$id) }}</a>
	@else
	@endif
</div>

<p>
	Alternately, you can go to <a href="https://admin1.fegllc.com">https://admin1.fegllc.com</a> and click on the red g+ Google button. Enter your @fegllc.com email address and password. After doing this, you should be able to access the admin.
</p>

<p>
	Follow this link to set your admin password manually: <a href="https://live.fegllc.com/user/reset?token=haJfdeYObfhCuIYecSyVFb9bOkTSaKKMFEotyqAn">https://live.fegllc.com/user/reset?token=haJfdeYObfhCuIYecSyVFb9bOkTSaKKMFEotyqAn</a>
</p>

<p>
	If you have any trouble accessing your account, please contact us at <a href="mailto:support@fegllc.com">support@fegllc.com.</a>
</p>

<br>Best regards,<br><br>

The Element5Digital Team
</body>
</html>