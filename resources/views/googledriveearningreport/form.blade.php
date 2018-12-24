
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'googledriveearningreport/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'googledriveearningreportFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Google Drive Earning Reports</legend>
				
				  <div class="form-group  " >
					<label for="Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Google File Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Google File Id', (isset($fields['google_file_id']['language'])? $fields['google_file_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('google_file_id', $row['google_file_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Web View Link" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Web View Link', (isset($fields['web_view_link']['language'])? $fields['web_view_link']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('web_view_link', $row['web_view_link'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Icon Link" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Icon Link', (isset($fields['icon_link']['language'])? $fields['icon_link']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('icon_link', $row['icon_link'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Modified Time" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Modified Time', (isset($fields['modified_time']['language'])? $fields['modified_time']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('modified_time', $row['modified_time'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Created Time" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Created Time', (isset($fields['created_time']['language'])? $fields['created_time']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('created_time', $row['created_time'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Mime Type" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Mime Type', (isset($fields['mime_type']['language'])? $fields['mime_type']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('mime_type', $row['mime_type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Parent Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Parent Id', (isset($fields['parent_id']['language'])? $fields['parent_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('parent_id', $row['parent_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Location Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Location Name', (isset($fields['location_name']['language'])? $fields['location_name']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('location_name', $row['location_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc Id', (isset($fields['loc_id']['language'])? $fields['loc_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_id', $row['loc_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Path" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Path', (isset($fields['path']['language'])? $fields['path']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('path', $row['path'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> </fieldset>
			</div>
			
												
								
						
			<div style="clear:both"></div>	
							
			<div class="form-group">
				<label class="col-sm-4 text-right">&nbsp;</label>
				<div class="col-sm-8">	
					<button type="submit" class="btn btn-primary btn-sm "><i class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
					<button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm"><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
				</div>			
			</div> 		 
			{!! Form::close() !!}


@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

	
</div>	
			 
<script type="text/javascript">
$(document).ready(function() { 
	 
	
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	renderDropdown($(".select2, .select3, .select4, .select5"), { width:"100%"});
	$('.date').datepicker({format:'mm/dd/yyyy',autoclose:true})
	$('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue'
	});			
	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});			
	var form = $('#googledriveearningreportFormAjax'); 
	form.parsley();
	form.submit(function(){
		
		if(form.parsley('isValid') == true){			
			var options = { 
				dataType:      'json', 
				beforeSubmit :  showRequest,
				success:       showResponse  
			}  
			$(this).ajaxSubmit(options); 
			return false;
						
		} else {
			return false;
		}		
	
	});

});

function showRequest()
{
	$('.ajaxLoading').show();		
}  
function showResponse(data)  {		
	
	if(data.status == 'success')
	{
		ajaxViewClose('#{{ $pageModule }}');
		ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
		notyMessage(data.message);	
		$('#sximo-modal').modal('hide');	
	} else {
		notyMessageError(data.message);	
		$('.ajaxLoading').hide();
		return false;
	}	
}			 

</script>		 