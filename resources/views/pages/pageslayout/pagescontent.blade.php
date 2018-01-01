@extends ('layouts.app')
@section('content')

    <div class="page-content-wrapper m-t">

        <div class="sbox animated fadeInRight">
            <div class="sbox-title"> {{ $pageTitle }}{!! $editLink !!}</div>
            <div class="sbox-content">
                <div class="col-md-12"
                     style="padding-top: 50px; padding-right:0px;  padding-bottom: 50px; background-color: rgb(255, 255, 255);">
                    @yield('pagecontent')
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@stop