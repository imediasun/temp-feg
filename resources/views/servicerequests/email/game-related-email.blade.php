<h3>{{ $data['Subject'] }}</h3>

Location : {{ $data['location']->id }} - {{ $data['location']->location_name }}<br/>
Game : {{ $data['game']['game_id'] }}  |  {{ $data['game']['game_title'] }} <br/>
Issue Type : {{ $data['issue_type'] }}<br/>
Functionality : {{ $data['functionality'] }}<br/>
Date : {{ date('Y/m/d',strtotime($data['game_realted_date'])) }}
<br/>
<br/>
<b> Troubleshooting Checklist :</b>
<br/><br/>
@foreach($checkList as $item)
    <div style="margin-bottom: 6px; margin-right: 10px; margin-left: 30px;">{{ $item->check_list_name }} = <span
                style="font-weight: 700;"> {{ in_array($item->id,$savedCheckList) ? 'Done':'Not Done' }}</span></div>
@endforeach

<br/><br/>
Part Number : {{ $data['part_number'] }}<br/>
@if(!empty($data['cost']))
Costs : {{ CurrencyHelpers::formatPrice($data['cost']) }}<br/>
@endif
@if(!empty($data['qty']))
Quantity : {{ $data['qty'] }}
@endif
@if(!empty($data['shipping_priority']))
<br/>
Shipping Priority : {{ $data['shipping_priority'] }}
@endif
<br/><br/>
<b>Troubleshooting Description :</b>

<p><?php echo nl2br($data['Description']); ?></p>

Direct link to service request: {{ $url }}