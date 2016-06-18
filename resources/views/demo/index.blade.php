
@section ('content')
@foreach($company as $data)
    <li>{{$data->company_name_short}}</li>b=
    <li>{{$data->company_name_long}}</li>
@endforeach