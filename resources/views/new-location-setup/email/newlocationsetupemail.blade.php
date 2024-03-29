<p>The FEG team has brought a new location's server online for location  {{ $row['location_id'].' '.$row['location_name'] }}. Please follow the link below for access credentials.</p>

{{--@if($type == 'element5Digital')--}}
{{--Location: {{ $row['location_id'] }}<br>--}}
{{--Location Name: {{ $row['location_name'] }}<br>--}}

{{--@if($row['use_tv'] == 1)--}}
    {{--Use Teamviewer: Yes <br>--}}
    {{--Teamviewer ID: {{ $row['teamviewer_id'] }}<br>--}}
    {{--Teamviewer ID: {{ $row['teamviewer_passowrd'] }}<br>--}}
    {{--@endif--}}

{{--@if($row['is_server_locked'] == 1)--}}
    {{--Should server be locked?: No <br>--}}
    {{--Window User: {{ $row['windows_user'] }}<br>--}}
    {{--Window Password: {{ $row['windows_user_password'] }}<br>--}}
{{--@endif--}}

{{--@if($row['is_remote_desktop'] == 1)--}}
    {{--Remote Desktop Needed?: Yes <br>--}}
    {{--RDP Computer Name: {{ $row['rdp_computer_name'] }}<br>--}}
    {{--RDP User: {{ $row['rdp_computer_user'] }}<br>--}}
    {{--RDP Password: {{ $row['rdp_computer_password'] }}<br>--}}
{{--@endif--}}

    {{--@elseif($type == 'embed')--}}
    {{--Location: {{ $row['location_id'] }}<br>--}}
    {{--Location Name: {{ $row['location_name'] }}<br>--}}

    {{--@if($row['use_tv'] == 1)--}}
        {{--Use Teamviewer: Yes <br>--}}
        {{--Teamviewer ID: {{ $row['teamviewer_id'] }}<br>--}}
        {{--Teamviewer ID: {{ $row['teamviewer_passowrd'] }}<br>--}}
    {{--@endif--}}

    {{--@if($row['is_server_locked'] == 1)--}}
        {{--Should server be locked?: No <br>--}}
        {{--Window User: {{ $row['windows_user'] }}<br>--}}
        {{--Window Password: {{ $row['windows_user_password'] }}<br>--}}
    {{--@endif--}}

    {{--@if($row['is_remote_desktop'] == 1)--}}
        {{--Remote Desktop Needed?: Yes <br>--}}
        {{--RDP Computer Name: {{ $row['rdp_computer_name'] }}<br>--}}
        {{--RDP User: {{ $row['rdp_computer_user'] }}<br>--}}
        {{--RDP Password: {{ $row['rdp_computer_password'] }}<br>--}}
    {{--@endif--}}
    {{--@elseif($type == 'sacoa')--}}
    {{--Location: {{ $row['location_id'] }}<br>--}}
    {{--Location Name: {{ $row['location_name'] }}<br>--}}

    {{--@if($row['use_tv'] == 1)--}}
        {{--Use Teamviewer: Yes <br>--}}
        {{--Teamviewer ID: {{ $row['teamviewer_id'] }}<br>--}}
        {{--Teamviewer ID: {{ $row['teamviewer_passowrd'] }}<br>--}}
    {{--@endif--}}

    {{--@if($row['is_server_locked'] == 1)--}}
        {{--Should server be locked?: No <br>--}}
        {{--Window User: {{ $row['windows_user'] }}<br>--}}
        {{--Window Password: {{ $row['windows_user_password'] }}<br>--}}
    {{--@endif--}}

    {{--@if($row['is_remote_desktop'] == 1)--}}
        {{--Remote Desktop Needed?: Yes <br>--}}
        {{--RDP Computer Name: {{ $row['rdp_computer_name'] }}<br>--}}
        {{--RDP User: {{ $row['rdp_computer_user'] }}<br>--}}
        {{--RDP Password: {{ $row['rdp_computer_password'] }}<br>--}}
    {{--@endif--}}
    {{--@else--}}
    {{--Location: {{ $row['location_id'] }}<br>--}}
    {{--Location Name: {{ $row['location_name'] }}<br>--}}

    {{--@if($row['use_tv'] == 1)--}}
        {{--Use Teamviewer: Yes <br>--}}
        {{--Teamviewer ID: {{ $row['teamviewer_id'] }}<br>--}}
        {{--Teamviewer ID: {{ $row['teamviewer_passowrd'] }}<br>--}}
    {{--@endif--}}

    {{--@if($row['is_server_locked'] == 1)--}}
        {{--Should server be locked?: No <br>--}}
        {{--Window User: {{ $row['windows_user'] }}<br>--}}
        {{--Window Password: {{ $row['windows_user_password'] }}<br>--}}
    {{--@endif--}}

    {{--@if($row['is_remote_desktop'] == 1)--}}
        {{--Remote Desktop Needed?: Yes <br>--}}
        {{--RDP Computer Name: {{ $row['rdp_computer_name'] }}<br>--}}
        {{--RDP User: {{ $row['rdp_computer_user'] }}<br>--}}
        {{--RDP Password: {{ $row['rdp_computer_password'] }}<br>--}}
    {{--@endif--}}
{{--@endif--}}
{{--<p>--}}
   {{--<b>Please follow the link below for access credentials.:</b>--}}
    {{--<br>--}}
    <a href="{{ $url }}" target="_blank">{{ $url }}</a>
{{--</p>--}}
<p>Please direct any questions relating to these server credentials to {{\Session::get('ufname') .' '. \Session::get('ulname')}} or Silvia Linter</p>
<p>An FEG Admin Automated Message<br>
<a href="https://www.fegllc.com" style="text-decoration: none">https://www.fegllc.com</a>
<br>
Phone: (847) 842-6310</p>