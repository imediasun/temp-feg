@extends('layouts.login')

@section('content')

    <div class="text-center">
        <img src="{{ asset('sximo/images/feg_new_logo.png') }}"/>
        <img id="logo-bar" src="{{ asset('sximo/images/logo_bar.png') }}" class="full-width"/>
    </div>
    <div class="sbox ">

        <div class="sbox-content">

            @if(Session::has('message'))
                {!! Session::get('message') !!}
            @endif
            <ul class="parsley-error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <ul class="nav nav-tabs col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                <li class="active col-lg-4 col-md-4 col-sm-4 col-xs-4 no-padding">
                    <a href="#tab-forgot" data-toggle="tab"> {{ Lang::get('core.forgotpassword') }} </a>
                </li>
            </ul>
            <div class="tab-content col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">

                <div class="tab-pane m-t active" id="tab-forgot">


                    <form method="post" action="{{ url('user/request')}}" class="form-vertical box" id="fr">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group has-feedback">
                            <div class="">
                                <label>{{ Lang::get('core.enteremailforgot') }}</label>
                                <input type="text" name="credit_email" placeholder="{{ Lang::get('core.email') }}"
                                       class="form-control" required/>
                                <i class="icon-envelope form-control-feedback"></i>
                            </div>
                        </div>
                        <div class="form-group has-feedback">
                            <button type="submit"
                                    class="btn darkblue-btn pull-right"> {{ Lang::get('core.sb_submit') }} </button>
                        </div>

                        <div class="clr"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#or').click(function () {
                $('#fr').toggle();
            });
        });
    </script>
@stop
