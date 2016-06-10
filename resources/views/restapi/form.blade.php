
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
		<li><a href="{{ URL::to('restapi?md='.$filtermd) }}">{{ $pageTitle }}</a></li>
        <li class="active">{{ Lang::get('core.addedit') }} </li>
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
		 {{ Form::open(array('url'=>'restapi/save/'.SiteHelpers::encryptID($row['id']).'?md='.$filtermd.$trackUri, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) }}
<div class="col-md-12">
						<fieldset><legend> Rest API Client</legend>
									
		   {{ Form::hidden('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) }}  					
		 
		  <div class="form-group  " >
			<label for="Apiuser" class=" control-label col-md-4 text-left"> 
			Users</label>
			<div class="col-md-6">
			  <select name='apiuser' rows='5' id='apiuser' code='{$apiuser}' 
							class='select2 '    ></select> 
			 </div> 
			 <div class="col-md-2">
			 	
			 </div>
		  </div> 					
										
		  <div class="form-group  " >
			<label for="Modules" class=" control-label col-md-4 text-left"> 
			Allowed Modules</label>
			<div class="col-md-6">
			  <textarea name='modules' rows='2' id='modules' class='form-control '>{{ $row['modules'] }}</textarea> 
			  <p ><i> Please register your current modules here . All registering modules , will available for API access </i></p>
			  <p> Example : <br />
			   <code>employee</code> , <code>users</code> , <code>customers</code> </p> 
			 </div> 
			 <div class="col-md-2">
			 	
			 </div>
		  </div> 
		  @if($row['id'] !='')
		  <div class="form-group  " >
			<label for="Apikey" class=" control-label col-md-4 text-left"> 
			Api Key </label>
			<div class="col-md-6">
			  {{ Form::text('apikey', $row['apikey'],array('class'=>'form-control', 'placeholder'=>'','readonly'=>'1' ,'style'=>'background : #fff !important;'   )) }} 
			 <p><i>  Use this apikey with useremail as basic authorization access to all your registered modules </i> </p>
			 </div> 
			 <div class="col-md-2">
			 	
			 </div>
		  </div> 
		  @endif
		  </fieldset>
			</div>
			
			
			<div style="clear:both"></div>	
				
			  <div class="form-group">
				<label class="col-sm-4 text-right">&nbsp;</label>
				<div class="col-sm-8">	
				<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>
				<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
				<button type="button" onclick="location.href='{{ URL::to('restapi?md='.$masterdetail["filtermd"].$trackUri) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
				</div>	  
		
			  </div> 
		 
		 {{ Form::close() }}
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		$("#apiuser").jCombo("{{ URL::to('restapi/comboselect?filter=tb_users:id:first_name|last_name') }}",
		{  selected_value : '{{ $row["apiuser"] }}' });
		 
	});
	</script>		 