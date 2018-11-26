<h3>{{ $data['Subject'] }}</h3>

Location : {{ $data['location']->id }} - {{ $data['location']->location_name }}<br/>
Game : {{ $data['game']['game_id'] }}  |  {{ $data['game']['game_title'] }} <br/>
Issue Type : {{ $data['issue_type'] }}<br/>
Functionality : {{ $data['functionality'] }}<br/>
Date : {{ date('Y/m/d',strtotime($data['game_realted_date'])) }}
<br/>
<b> Troubleshooting Checklist :</b>
@foreach($checkList as $item)
    <div style="margin-bottom: 5px;">{{ $item->check_list_name }} = <span
                style="font-weight: 700;"> {{ in_array($item->id,$savedCheckList) ? 'Done':'Not Done' }}</span></div>
@endforeach


Part Number : {{ $data['functionality'] }}<br/>
Costs : {{ CurrencyHelpers::formatCurrency($data['cost']) }}<br/>
Quantity : {{ $data['qty'] }}<br/>
Shipping Priority : {{ $data['shipping_priority'] }}
<br/>
<b>Troubleshooting Description :</b>

<p>{{ @nl2br($data['Description']) }}</p>

Direct link to service request: {{ $url }}