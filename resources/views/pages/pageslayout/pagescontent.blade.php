@extends ('layouts.app')
@section('content')

    <div class="page-content-wrapper m-t">

        <div class="sbox animated fadeInRight">
            <div class="sbox-title"> {{ $pageTitle }}{!! $editLink !!}</div>
          @yield('pagecontent')

        </div>
    </div>
@stop