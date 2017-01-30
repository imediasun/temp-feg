
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'submitservicerequest/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'submitservicerequestFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Submit Service Request</legend>
				
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
					<label for="Priority Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Priority Id', (isset($fields['priority_id']['language'])? $fields['priority_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('priority_id', $row['priority_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Location Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Location Id', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('location_id', $row['location_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Requestor Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Requestor Id', (isset($fields['requestor_id']['language'])? $fields['requestor_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('requestor_id', $row['requestor_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Request Date" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Request Date', (isset($fields['request_date']['language'])? $fields['request_date']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('request_date', date("m/d/Y", strtotime($row['request_date'])),array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Problem" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Problem', (isset($fields['problem']['language'])? $fields['problem']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='problem' rows='5' id='problem' class='form-control '
				           >{{ $row['problem'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Needed By" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Needed By', (isset($fields['need_by_date']['language'])? $fields['need_by_date']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('need_by_date', date("m/d/Y", strtotime($row['need_by_date'])),array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Resolved" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Resolved', (isset($fields['solved_date']['language'])? $fields['solved_date']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('solved_date', date("m/d/Y", strtotime($row['solved_date'])),array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Solver Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Solver Id', (isset($fields['solver_id']['language'])? $fields['solver_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('solver_id', $row['solver_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Solution" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Solution', (isset($fields['solution']['language'])? $fields['solution']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='solution' rows='5' id='solution' class='form-control '
				           >{{ $row['solution'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Status Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Status Id', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('status_id', $row['status_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Request Title" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Request Title', (isset($fields['request_title']['language'])? $fields['request_title']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('request_title', $row['request_title'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Attachment Path" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Attachment Path', (isset($fields['attachment_path']['language'])? $fields['attachment_path']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='attachment_path' rows='5' id='attachment_path' class='form-control '
				           >{{ $row['attachment_path'] }}</textarea>
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
	$(".select2").select2({ width:"98%"});	
	$('.date').datepicker({format:'mm/dd/yyyy',autoClose:true})
	$('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
	});			
	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});			
	var form = $('#submitservicerequestFormAjax'); 
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