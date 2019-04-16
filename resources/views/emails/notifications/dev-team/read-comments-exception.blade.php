<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<p> Exception Message : </p>
<div>
    @foreach($messages as $message)
        <p>{{$message}}</p>
    @endforeach
</div>
</body>
</html>