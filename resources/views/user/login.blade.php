@extends('layouts.login')

@section('content')
	<style>
		/*.nav.nav-tabs li:last-child:after {
			content: "";
			position: absolute;
			background: #f9f9f9;
			width: 5px;
			height: 1px;
			right: -3px;
			bottom: 0;
		}*/
	</style>
    <div class="text-center">
    <img src="{{ asset('sximo/images/feg_new_logo.png') }}" />
        <img id="logo-bar"  src="{{ asset('sximo/images/logo_bar.png') }}" class="full-width"/>
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
		
	<ul class="nav nav-tabs" >
	  <li class="active"><a href="#tab-social" data-toggle="tab">  {{ Lang::get('core.social') }} </a></li>
	  <li><a href="#tab-sign-in" data-toggle="tab">  {{ Lang::get('core.signin') }} </a></li>
	   <li ><a href="#tab-forgot" data-toggle="tab"> {{ Lang::get('core.forgotpassword') }} </a></li>
	</ul>
	<div class="tab-content" >
		<div class="tab-pane active m-t" id="tab-social">
			<div class="animated fadeInUp delayp1">
				<div class="form-group has-feedback text-center">
					@if($socialize['google']['client_id'] !='' || $socialize['twitter']['client_id'] !='' || $socialize['facebook'] ['client_id'] !='')
						<br />
						<p class="text-muted text-center"><b> {{ Lang::get('core.loginsocial') }} </b>	  </p>
					@endif
					<div style="padding: 0;">
						@if($socialize['facebook']['client_id'] !='')
							<a href="{{ URL::to('user/socialize/facebook')}}" class="btn btn-primary"><i class="icon-facebook"></i> Facebook </a>
						@endif
						@if($socialize['google']['client_id'] !='')
							<a href="{{ URL::to('user/socialize/google')}}" style="background-color:#DD4B39; border-color: #DD4B39; color: #ffffff;" class="btn btn-block"><i class="icon-google-plus"></i> Google </a>
						@endif
						@if($socialize['twitter']['client_id'] !='')
							<a href="{{ URL::to('user/socialize/twitter')}}" class="btn btn-info"><i class="icon-twitter"></i> Twitter </a>
						@endif
					</div>
				</div>

			</div>
		</div>
		<div class="tab-pane m-t" id="tab-sign-in">
		<form method="post" action="{{ url('user/signin')}}" class="form-vertical">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
		
			<div class="form-group has-feedback animated fadeInLeft delayp1">
				<label>{{ Lang::get('core.email') }}	</label>
				<input type="text" name="email" placeholder="Email Address" class="form-control" required="email" />
				
				<i class="icon-users form-control-feedback"></i>
			</div>
			
			<div class="form-group has-feedback  animated fadeInRight delayp1">
				<label>{{ Lang::get('core.password') }}	</label>
				<input type="password" name="password" placeholder="Password" class="form-control" required="true" />
				<i class="icon-lock form-control-feedback"></i>
			</div>

			<div class="form-group has-feedback  animated fadeInRight delayp1">
				<label> Remember Me ?...	</label>
				<input type="checkbox" name="remember" value="1" />


			</div>


			@if(CNF_RECAPTCHA =='true') 
			<div class="form-group has-feedback  animated fadeInLeft delayp1">
				<label class="text-left"> Are u human ? </label>	
				<br />
				{!! captcha_img() !!} <br /><br />
				<input type="text" name="captcha" placeholder="Type Security Code" class="form-control" required/>
				
				<div class="clr"></div>
			</div>	
		 	@endif	

			@if(CNF_MULTILANG =='1') 
			<div class="form-group has-feedback  animated fadeInLeft delayp1">
				<label class="text-left"> {{ Lang::get('core.language') }} </label>	
				<select class="form-control" name="language">
					@foreach(SiteHelpers::langOption() as $lang)
					<option value="{{ $lang['folder'] }}" @if(Session::get('lang') ==$lang['folder']) selected @endif>  {{  $lang['name'] }}</option>
					@endforeach

				</select>	
				
				<div class="clr"></div>
			</div>	
		 	@endif




            <div class="form-group  has-feedback text-center  animated fadeInLeft delayp1" style=" margin-bottom:20px;" >

                <button type="submit"  class="btn darkblue-btn btn-sm btn-block" id="loginBtn"><i class="fa fa-sign-in"></i> {{ Lang::get('core.signin') }}</button>



                <div class="clr"></div>

            </div>
		   </form>			
		</div>
	
	

	<div class="tab-pane  m-t" id="tab-forgot">	

		
		<form method="post" action="{{ url('user/request')}}" class="form-vertical box" id="fr">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
		   <div class="form-group has-feedback">
		   <div class="">
				<label>{{ Lang::get('core.enteremailforgot') }}</label>
				<input type="text" name="credit_email" placeholder="{{ Lang::get('core.email') }}" class="form-control" required/>
				<i class="icon-envelope form-control-feedback"></i>
			</div> 	
			</div>
			<div class="form-group has-feedback">
		      <button type="submit" class="btn darkblue-btn pull-right"> {{ Lang::get('core.sb_submit') }} </button>
		  </div>

		  <div class="clr"></div>

		  
		</form>

	
	</div>


	</div>  

  </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#or').click(function(){
		$('#fr').toggle();
		});
	});
</script>
@stop
