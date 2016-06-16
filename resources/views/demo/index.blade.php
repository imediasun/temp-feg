
@section ('content')
@foreach($active as $data)
    <li>{{$data->type}}</li>
@endforeach