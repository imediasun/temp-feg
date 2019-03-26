<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<p> Exception Message : </p>
<div>
    <b>Error Code:</b>         {{ $ex->getCode() }} <br>
    <b>Exception Message:</b>  {{ $ex->getMessage() }}<br>
    <b>Line Number:</b>        {{ $ex->getLine() }}

    <br>
    <br>
    <b>Stack Trace:</b>

    {{json_encode($ex->getTrace())}}
</div>
</body>
</html>