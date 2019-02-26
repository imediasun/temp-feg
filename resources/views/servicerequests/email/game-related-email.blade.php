<h3>{{ $data['Subject'] }}</h3>

Location : {{ $data['location']->id }} - {{ $data['location']->location_name }}<br/>
Game : {{ $data['game']['game_id'] }}  |  {{ $data['game']['game_title'] }} <br/>
Issue Type : {{ $data['issue_type'] }}<br/>
Functionality : {{ $data['functionality'] }}<br/>
Date : {{ date('Y/m/d',strtotime($data['game_realted_date'])) }}
<br/>
<br/>
@if(!$is_partRequest)
<b> Troubleshooting Checklist :</b>
<br/><br/>
@foreach($checkList as $item)
    <div style="margin-bottom: 6px; margin-right: 10px; margin-left: 30px;">{{ $item->check_list_name }} = <span
                style="font-weight: 700;"> {{ in_array($item->id,$savedCheckList) ? 'Done':'Not Done' }}</span></div>
@endforeach
@else
    <b> Part Request(s) :</b>
<table border="1" cellpadding="2" cellspacing="0" style="width: 50%;">
    <tr>
        <th>Part Number</th>
        <th>Qty</th>
        <th>Cost</th>
    </tr>
    @foreach($partRequests as $partRequest)
        <tr>
            <td>{{ $partRequest->part_number }}</td>
            <td>{{ $partRequest->qty }}</td>
            <td>{{ CurrencyHelpers::formatPrice($partRequest->cost) }}</td>
        </tr>
        @endforeach
</table>
    @endif
<br/>

@if(!empty($data['shipping_priority']))
<br/>
Shipping Priority : {{ $data['shipping_priority'] }}
@endif
<br/><br/>
<b>Troubleshooting Description :</b>

<p><?php echo nl2br($data['Description']); ?></p>

Direct link to service request: {{ $url }}