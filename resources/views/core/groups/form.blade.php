@extends('layouts.app')

@section('content')

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
		<li><a href="{{ URL::to('core/groups?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active">{{ Lang::get('core.addedit') }} </li>
      </ul>
	  	  
    </div>
 
 	<div class="page-content-wrapper">

		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small></h4></div>
	<div class="sbox-content"> 	

		 {!! Form::open(array('url'=>'core/groups/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Users Group</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Group Id" class=" control-label col-md-4 text-left"> Group Id </label>
									<div class="col-md-6">
									  {!! Form::text('group_id', $row['group_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Name" class=" control-label col-md-4 text-left"> Name <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('name', $row['name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Description" class=" control-label col-md-4 text-left"> Description </label>
									<div class="col-md-6">
									  <textarea name='description' rows='2' id='description' class='form-control '  
				           >{{ $row['description'] }}</textarea> 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " >
									<label for="Level" class=" control-label col-md-4 text-left"> Level <span class="asterix"> * </span></label>
									<div class="col-md-6">
									  {!! Form::text('level', $row['level'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div>


							<div class="form-group  int-link" >
								<label for="ipt" class=" control-label col-md-4 text-right"> Login Start Page </label>
								<div class="col-md-6">
									<select name="redirect_link"  rows='5' type="text" id="redirect_link"  style="width:100%"  class='select-liquid ' value="{{ $row['redirect_link'] }}" >
										<option value=""> -- Select Module or Page -- </option>
										<optgroup label="Module ">
											@foreach($modules as $mod)
                                                <?php                                
                                                    $moduleConfig = \SiteHelpers::CF_decode_json($mod->module_config);
                                                    $moduleRoute = $mod->module_name;
                                                    if (isset($moduleConfig['setting']['module_route'])) {
                                                        $moduleRoute = $moduleConfig['setting']['module_route'];
                                                    }
                                                    $modulePublicAccess = isset($moduleConfig['setting']['publicaccess'])?
                                                            $moduleConfig['setting']['publicaccess']:true;
                                                ?>
                                                @if($modulePublicAccess)
												<option value="{{ $moduleRoute }}"
														@if($row['redirect_link'] === $moduleRoute )   selected="selected" @endif
												>{{ $mod->module_title}}</option>
                                                @endif
											@endforeach
										</optgroup>
                                        {{--<optgroup label="Dashboards">
                                            <option value="dashboard">Dashboard</option>
                                        </optgroup>   --}}
										<optgroup label="Page CMS ">
											@foreach($pages as $page)
												<option value="{{ $page->alias}}"
														@if($row['redirect_link']=== $page->alias ) selected="selected" @endif
												>Page : {{ $page->title}}</option>
											@endforeach
										</optgroup>
									</select>
								</div>
								</div>
						</fieldset>

			</div>
			
			
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					
					<div class="col-sm-12 text-center">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
					<button type="button" onclick="location.href='{{ URL::to('core/groups?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		 
	});
	</script>		 
@stop
