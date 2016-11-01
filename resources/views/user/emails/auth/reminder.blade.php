<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Password Reset</h2>

		<div>
            @if(isset($token))
			To reset your password, complete this form: {{ URL::to('user/reset?token='.$token) }}.
                @elseif(isset($email))
            To reset your password, complete this form: {{ URL::to('user/reset?email'.$email) }}.
                @else
                <p>hello world</p>
                @endif
            <p>helllllll</p>

		</div>
	</body>
</html>