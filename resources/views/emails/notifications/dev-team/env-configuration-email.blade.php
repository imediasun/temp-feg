<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <style>
        table{
            width: 100%;
            margin-bottom: 10px;
        }
        table tr th,table tr td{
            padding:5px 10px;
        }
    </style>
</head>
<body>
    <h1>FEG ENV Configuration</h1>

    <p><b>New Configurations Added in ENV File:</b></p>
    <table border="1" cellpadding="0" cellspacing="0">
        <tr>
            <th>Configuration Name</th>
            <th>Configuration Value</th>
            <th>Status</th>
        </tr>
        @if(!empty($newConfigurationsEnv))
            @foreach($newConfigurationsEnv as $newConfigurationsenv)
                <tr>
                    <td>{{ $newConfigurationsenv['option'] }}</td>
                    <td>{{ $newConfigurationsenv['value'] }}</td>
                    <td style="font-weight: 700; color:green;">{{ $newConfigurationsenv['status'] }}</td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="3">No existing record found.</td></tr>
        @endif
    </table>

    <p><b>Existing Configurations:</b></p>
    <table border="1" cellpadding="0" cellspacing="0">
        <tr>
            <th>Configuration Name</th>
            <th>Configuration Value</th>
            <th>Status</th>
        </tr>
        @if(!empty($existigConfigurations))
            @foreach($existigConfigurations as $existigConfiguration)
        <tr>
            <td>{{ $existigConfiguration['option'] }}</td>
            <td>@if(strtolower($existigConfiguration['status']) != 'ok') {{  $existigConfiguration['value'] }} @else -- @endif</td>
            <td style="font-weight: 700; @if(strtolower($existigConfiguration['status']) == 'ok') color:green; @else color:red; @endif">{{ $existigConfiguration['status'] }}</td>
        </tr>
            @endforeach
            @else
        <tr><td colspan="3">No existing record found.</td></tr>
        @endif
    </table>


    <br><br>
</body>
</html>