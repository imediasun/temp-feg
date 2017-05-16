@extends('layouts.app')

@section('content')
<div class="page-content row" style="padding: 1% 2%; background-color: #fff;">    
    <h2>FEG System Utilities Manager &gt; Modules</h2>
    @if (!empty($data))
    <ul>
    @foreach($data as $item)
    <li>{!! $item !!}</li>
    @endforeach
    </ul>
    @endif
</div>	
@endsection