@extends('layouts.app')

@section('content')
<div class="page-content row" style="padding: 1% 2%; background-color: #fff;">    
    <h2>{!! $title !!}</h2>
    @if (!empty($data))
    @foreach($data as $item)
    <div>{!! $item !!}</div>
    @endforeach
    @endif
</div>	
@endsection