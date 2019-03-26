<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<p> Exception Message : </p>
<div>
    {{ 'Error Code:        '.$ex->getCode() }}
    {{ 'Exception Message: '.$ex->getMessage() }}
    {{ 'Line Number:       '.$ex->getLine() }}

    Stack Trace: {{--{{json_encode($ex->getTrace())}}--}}
    @foreach($ex->getTrace() as $trace)
        @foreach($trace->toArray() as $traceItem)
        <p>{{$traceItem}}</p>
        @endforeach
    @endforeach
</div>
</body>
</html>