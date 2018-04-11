@extends('layouts.login')

@section('content')
    <style>
        /*.nav.nav-tabs li:last-child:after {
            content: "";
            position: absolute;
            background: #f9f9f9;
            width: 2px;
            height: 1px;
            right: 0;
            bottom: 0;
        }*/
    </style>
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
                <li class="@if(!Session::has('active_tab')) active @endif col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding">
                    <a href="#tab-social" data-toggle="tab">  {{ Lang::get('core.social') }} </a></li>
                <li class="@if(Session::has('message') && Session::get('active_tab') == 1) active @endif col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding">
                    <a href="#tab-sign-in" data-toggle="tab">  {{ Lang::get('core.signin') }} </a></li>
            </ul>
            <div class="tab-content col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                <div class="tab-pane @if(!Session::has('active_tab')) active @endif m-t" id="tab-social">
                    <div class="animated fadeInUp delayp1">
                        <div class="form-group has-feedback text-center">
                            @if($socialize['google']['client_id'] !='' || $socialize['twitter']['client_id'] !='' || $socialize['facebook'] ['client_id'] !='')
                                <br/>
                                <p class="text-muted text-center"><b> {{ Lang::get('core.loginsocial') }} </b></p>
                            @endif
                            <div style="padding: 0;">
                                @if($socialize['facebook']['client_id'] !='')
                                    <a href="{{ URL::to('user/socialize/facebook')}}" class="btn btn-primary"><i
                                                class="icon-facebook"></i> Facebook </a>
                                @endif
                                @if($socialize['google']['client_id'] !='')
                                    <a href="{{ URL::to('user/socialize/google')}}"
                                       style="background-color:#DD4B39; border-color: #DD4B39; color: #ffffff;"
                                       class="btn btn-block"><i class="icon-google-plus"></i> Google </a>
                                @endif
                                @if($socialize['twitter']['client_id'] !='')
                                    <a href="{{ URL::to('user/socialize/twitter')}}" class="btn btn-info"><i
                                                class="icon-twitter"></i> Twitter </a>
                                @endif
                            </div>
                        </div>
                        <div class="form-group has-feedback text-center">
                            <a target="_blank" class="btn darkblue-btn btn-sm btn-block" href="https://accounts.google.com/signin/usernamerecovery?sarp=1&scc=1&hl=en&continue=https%3A%2F%2Faccounts.google.com%2Fsignin%2Foauth%2Fconsent%3Fauthuser%3Dunknown%26part%3DAJi8hAN7J4tqycmOqVPKoJvLsJYU6cTP7hx3N4jTc7GLD1YGfi5aSUPrdKH_3OvtAO6R-RmRAU5MnPZULLnOw8qjTt56fKCFF9ulMLGUJMm0TuPFl8edBidxkyt73XPLev8IwQujhMaPTAJWfPl0SBJ4Q9lZylLNUGH-XOzUl3b7aEH-z3AFEGt_nujBz-oP8Goui2-hZ9n-iGhyJa0kkiJTHRA5zeOh42ysLnSUP6ucGYEcHMh3jtO0QQsafqiZ_IbSH1eWOynB0Bl6Y2PNZKi4NqiayRUVgt9khlSEzJg7Rh8NN3YnzYWVqfPIyxmHYUIutrbuKgPNwL5ei1hrahYRSO1IMrGAzpUDTo9rner6tuw2wRsNRR5hm6VcoMdiTAAYOBPYy4AVLlLraPr2SO0dOlFkAu1FMtTpjdbzBPo8kLCmnJ67qGU%26as%3D-68d309b5ebb3555%23"> {{ Lang::get('core.forgotpassword') }} ? </a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane m-t @if(Session::has('message') && Session::get('active_tab') == 1) active @endif"
                     id="tab-sign-in">
                    <form method="post" action="{{ url('user/signin')}}" class="form-vertical">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group has-feedback animated fadeInLeft delayp1">
                            <label>{{ Lang::get('core.email') }}    </label>
                            <input type="text" name="email" placeholder="Email Address" class="form-control"
                                   required="email"/>

                            <i class="icon-users form-control-feedback"></i>
                        </div>

                        <div class="form-group has-feedback  animated fadeInRight delayp1">
                            <label>{{ Lang::get('core.password') }}    </label>
                            <input type="password" name="password" placeholder="Password" class="form-control"
                                   required="true"/>
                            <i class="icon-lock form-control-feedback"></i>
                        </div>

                        <div class="form-group has-feedback  animated fadeInRight delayp1">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label> Remember Me ?... </label>
                                    <input type="checkbox" name="remember" value="1"/>
                                </div>
                            </div>
                        </div>


                        @if(CNF_RECAPTCHA =='true')
                            <div class="form-group has-feedback  animated fadeInLeft delayp1">
                                <label class="text-left"> Are u human ? </label>
                                <br/>
                                {!! captcha_img() !!} <br/><br/>
                                <input type="text" name="captcha" placeholder="Type Security Code" class="form-control"
                                       required/>

                                <div class="clr"></div>
                            </div>
                        @endif

                        @if(CNF_MULTILANG =='1')
                            <div class="form-group has-feedback  animated fadeInLeft delayp1">
                                <label class="text-left"> {{ Lang::get('core.language') }} </label>
                                <select class="form-control" name="language">
                                    @foreach(SiteHelpers::langOption() as $lang)
                                        <option value="{{ $lang['folder'] }}"
                                                @if(Session::get('lang') ==$lang['folder']) selected @endif>  {{  $lang['name'] }}</option>
                                    @endforeach

                                </select>

                                <div class="clr"></div>
                            </div>
                        @endif


                        <div class="form-group  has-feedback text-center  animated fadeInLeft delayp1"
                             style=" margin-bottom:20px;">

                            <button type="submit" class="btn darkblue-btn btn-sm btn-block" id="loginBtn"><i
                                        class="fa fa-sign-in"></i> {{ Lang::get('core.signin') }}</button>

                            <div class="clr"></div>

                        </div>
                        <a style="background-color:#DD4B39; margin-bottom: 15px; border-color: #DD4B39; color: #ffffff;"
                           class="btn btn-block" target="_blank" href="{{url('forget-password')}}"> {{ Lang::get('core.forgotpassword') }} ? </a>

                    </form>
                </div>
                <div style="padding: 15px 0px 10px 0px;  text-align: center;" class="tabs-footer">
                    <div style="margin-bottom:2px; center">
                        <a style="color:#012c5f !important;" href="{{ Route('termsofservice') }}" target="_blank">Terms and Conditions</a> | <a style="color:#012c5f !important;" href="{{ Route('privacyplicy') }}" target="_blank">Privacy</a>
                    </div>
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
        if (!!window.performance && window.performance.navigation.type === 2) {
            // page has been hit using back or forward buttons
            document.body.style.display = "none";
            location.reload();
        }
    </script>
@stop