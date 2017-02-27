@extends('layouts.app')


@section('content')

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">

	  
	 
	  <ul class="breadcrumb">
		<li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
		<li><a href="{{ URL::to('config') }}">{{ Lang::get('core.t_generalsetting') }}</a></li>
	  </ul>	  
	 
    </div>
 	<div class="page-content-wrapper">   
	@if(Session::has('message'))
	  
		   {{ Session::get('message') }}
	   
	@endif
	<ul class="parsley-error-list">
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>		
<div class="block-content">
	@include('sximo.config.tab')	
<div class="tab-content m-t">
  <div class="tab-pane active use-padding" id="info">	
  <div class="sbox  "> 
  <div class="sbox-title"></div>
  <div class="sbox-content"> 
		 {!! Form::open(array('url'=>'feg/config/save/', 'id' => 'formSximoConfigMain', 'class'=>'form-horizontal row', 'files' => true)) !!}

		<div class="col-sm-6 animated fadeInRight ">
		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_appname') }} </label>
			<div class="col-md-8">
			<input name="cnf_appname" type="text" id="cnf_appname" class="form-control input-sm" required  value="{{ $options['CNF_APPNAME'] }}" />
			 </div> 
		  </div>  
		  
		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_appdesc') }} </label>
			<div class="col-md-8">
			<input name="cnf_appdesc" type="text" id="cnf_appdesc" class="form-control input-sm" value="{{ $options['CNF_APPDESC'] }}" />
			 </div> 
		  </div>  
		  
		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_comname') }} </label>
			<div class="col-md-8">
			<input name="cnf_comname" type="text" id="cnf_comname" class="form-control input-sm" value="{{ $options['CNF_COMNAME'] }}" />
			 </div> 
		  </div>

		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_emailsys') }} </label>
			<div class="col-md-8">
			<input name="cnf_email" type="text" id="cnf_email" class="form-control input-sm" value="{{ $options['CNF_EMAIL'] }}" />
			 </div> 
		  </div>   
		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4"> Muliti language <br /> <small> Only Layout Interface </small> </label>
			<div class="col-md-8">
				<div class="checkbox">
					<input name="cnf_multilang" type="checkbox" id="cnf_multilang" value="1"
					@if(CNF_MULTILANG ==1) checked @endif
					  />  {{ Lang::get('core.fr_enable') }} 
				</div>	
			 </div> 
		  </div> 
		     
		   <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_mainlanguage') }} </label>
			<div class="col-md-8">

					<select class="form-control" name="cnf_lang">

					@foreach(SiteHelpers::langOption() as $lang)
						<option value="{{  $lang['folder'] }}"
						@if(CNF_LANG ==$lang['folder']) selected @endif
						>{{  $lang['name'] }}</option>
					@endforeach
				</select>
			 </div> 
		  </div>   
		      

		   <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4"> Frontend Template </label>
			<div class="col-md-8">

					<select class="form-control" name="cnf_theme">
					@foreach(SiteHelpers::themeOption() as $t)
						<option value="{{  $t['folder'] }}"
						@if($options['CNF_THEME'] ==$t['folder']) selected @endif
						>{{  $t['name'] }}</option>
					@endforeach
				</select>
			 </div> 
		  </div>


			<div class="form-group hide">
		    <label for="ipt" class=" control-label col-md-4"> Development Mode ?   </label>
			<div class="col-md-8">
				<div class="checkbox">
					<input name="cnf_mode" type="checkbox" id="cnf_mode" value="1"
					@if (defined($options['CNF_MODE']) &&  $options['CNF_MODE'] =='production') checked @endif
					  />  Production
				</div>
				<small> If you need to debug mode , please unchecked this option </small>	
			 </div> 
		  </div>
		  
		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">&nbsp;</label>
			<div class="col-md-8">
				<button class="btn btn-primary" type="submit">{{ Lang::get('core.sb_savechanges') }} </button>
			 </div> 
		  </div> 
		</div>

		<div class="col-sm-6 animated fadeInRight ">

		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">Metakey </label>
			<div class="col-md-8">
				<textarea class="form-control input-sm" name="cnf_metakey">{{ $options['CNF_METAKEY'] }}</textarea>
			 </div> 
		  </div> 

		   <div class="form-group">
		    <label  class=" control-label col-md-4">Meta Description</label>
			<div class="col-md-8">
				<textarea class="form-control input-sm"  name="cnf_metadesc">  {{$options['CNF_METADESC'] }}</textarea>
			 </div> 
		  </div>  

		   <div class="form-group">
		    <label  class=" control-label col-md-4">Backend Logo</label>
			<div class="col-md-8">
				<input type="file" name="logo">
				<p> <i>Please use image dimension 155px * 30px </i> </p>
				<div style="padding:5px; border:solid 1px #ddd; background:#f5f5f5; width:auto;">
				 	@if(file_exists(public_path().'/sximo/images/'.$options['CNF_LOGO']) && $options['CNF_LOGO'] !='')
				 	<img src="{{ asset('sximo/images/'.$options['CNF_LOGO'])}}" alt="{{ $options['CNF_APPNAME'] }}" />
				 	@else
					<img src="{{ asset('sximo/images/logo.png')}}" alt="{{ $options['CNF_APPNAME'] }}" />
					@endif	
				</div>				
			 </div> 
		  </div>



			<div class="form-group  int-link" >
				<label for="ipt" class=" control-label col-md-4 text-right"> Login Start Page </label>
				<div class="col-md-8">
					<select name="cnf_redireclink" rows='5' type="text" id="cnf_redireclink"   style="width:100%"  class='select-liquid ' value="{{ $options['CNF_REDIRECTLINK'] }}" >
						<option value=""> -- Select Module or Page -- </option>
						<optgroup label="Module ">
							@foreach($modules as $mod)
                                <?php                                
                                    $moduleConfig = \SiteHelpers::CF_decode_json($mod->module_config);
                                    $moduleRoute = $mod->module_name;
                                    if (isset($moduleConfig['setting']['module_route'])) {
                                        $moduleRoute = $moduleConfig['setting']['module_route'];
                                    }                                    
                                ?>                                            
                                <option value="{{ $moduleRoute }}"                            
                                    @if($options['CNF_REDIRECTLINK'] === $moduleRoute )   selected="selected" @endif
								>{{ $mod->module_title}}</option>
							@endforeach
						</optgroup>
                        <optgroup label="Dashboards">
                            <option value="dashboard">Dashboard</option>
                        </optgroup>                        
						<optgroup label="Page CMS ">
							@foreach($pages as $page)
								<option value="{{ $page->alias}}"
										@if($options['CNF_REDIRECTLINK'] === $page->alias ) selected="selected" @endif
								>Page : {{ $page->title}}</option>
							@endforeach
						</optgroup>
					</select>
				</div>
			</div>




			<div class="form-group">
				<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_email') }} </label>
				<div class="col-md-8">
					<input name="cnf_reply_to" type="text" id="cnf_reply_to" class="form-control input-sm" value="{{ $options['CNF_REPLY_TO'] }}" />
				</div>
			</div>
			<div class="form-group">
				<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_password') }} </label>
				<div class="col-md-8">
					<input name="cnf_reply_to_password" type="text" id="cnf_reply_to_password" class="form-control input-sm" value="{{ $options['CNF_REPLY_TO_PASSWORD'] }}" />
				</div>
			</div>


		</div>

		 {!! Form::close() !!}
	</div>
	</div>	 
</div>
</div>
</div>
</div>
</div>

@stop

@section ('beforebodyend')

  

<script type="text/javascript">
    
    jQuery(document).ready(function() {
        
        var $= jQuery, form = $('#formSximoConfigMain');
        form.submit(function(){

            var options = {
                dataType:      'json',
                success:       showResponse
            };
            form.find('button[type=submit]').prop('disabled', true);
            showProgress();
            $(this).ajaxSubmit(options);
            return false;

        });

        function showProgress () {
            $('.ajaxLoading').show();
        }
        function hideProgress () {
            $('.ajaxLoading').hide();
        }
		function showResponse(data)  {
            hideProgress();
			if(data.status == 'success') {				
                form.find('button[type=submit]').prop('disabled', false);
				notyMessage(data.message);
                //window.setTimeout(function () {window.location.reload()}, 1000);
                location.reload();
			}
            else {
                form.find('button[type=submit]').prop('disabled', false);
				notyMessageError(data.message);
				return false;
			}
		}        
        
    });    
</script>

    
@endsection
