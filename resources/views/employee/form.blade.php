
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'employee/save/'.SiteHelpers::encryptID($row['employeeNumber']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'employeeFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Employee</legend>
									
				  <div class="form-group hidethis " style="display:none;"> 
					<label for="EmployeeNumber" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('EmployeeNumber', (isset($fields['employeeNumber']['language'])? $fields['employeeNumber']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('employeeNumber', $row['employeeNumber'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="LastName" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('LastName', (isset($fields['lastName']['language'])? $fields['lastName']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('lastName', $row['lastName'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="FirstName" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('FirstName', (isset($fields['firstName']['language'])? $fields['firstName']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('firstName', $row['firstName'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Extension" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Extension', (isset($fields['extension']['language'])? $fields['extension']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('extension', $row['extension'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Email" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Email', (isset($fields['email']['language'])? $fields['email']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'email'   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="ReportsTo" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ReportsTo', (isset($fields['reportsTo']['language'])? $fields['reportsTo']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='reportsTo' rows='5' id='reportsTo' class='select2 '   ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Foto" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Foto', (isset($fields['foto']['language'])? $fields['foto']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <input  type='file' name='foto' id='foto' @if($row['foto'] =='') class='required' @endif style='width:150px !important;'  />
					 	<div >
						{!! SiteHelpers::showUploadedFile($row['foto'],'/uploads/images/') !!}
						
						</div>					
					 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="JobTitle" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('JobTitle', (isset($fields['jobTitle']['language'])? $fields['jobTitle']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('jobTitle', $row['jobTitle'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
	
        $("#reportsTo").jCombo("{{ URL::to('employee/comboselect?filter=employees:employeeNumber:firstName|lastName') }}",
        {  selected_value : '{{ $row["reportsTo"] }}' });
         
	
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	$(".select2").select2({ width:"98%"});	
	$('.date').datepicker({format:'yyyy-mm-dd',autoClose:true})
	$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'}); 
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
	});			
	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});			
	var form = $('#employeeFormAjax'); 
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