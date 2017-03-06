
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'course/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'courseFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> coursemodule</legend>

				  <div class="form-group  " >
					<label for="Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Name', (isset($fields['name']['language'])? $fields['name']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('name', $row['name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Town" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Town', (isset($fields['town']['language'])? $fields['town']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('town', $row['town'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Description" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Description', (isset($fields['description']['language'])? $fields['description']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea class='editor form-control' name='description' rows='5' id='description'
				           >{{ $row['description'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Price" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Price', (isset($fields['price']['language'])? $fields['price']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('price', $row['price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Start Date" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Start Date', (isset($fields['start_date']['language'])? $fields['start_date']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('start_date', $row['start_date'],array('class'=>'form-control date', 'style'=>'width:150px !important;')) !!}

					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>

					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="End Date" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('End Date', (isset($fields['end_date']['language'])? $fields['end_date']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('end_date', $row['end_date'],array('class'=>'form-control date', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Advertisement Start Date" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Advertisement Start Date', (isset($fields['advertisement_start_date']['language'])? $fields['advertisement_start_date']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('advertisement_start_date', $row['advertisement_start_date'],array('class'=>'form-control date', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Advertisement End Date" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Advertisement End Date', (isset($fields['advertisement_end_date']['language'])? $fields['advertisement_end_date']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('advertisement_end_date', $row['advertisement_end_date'],array('class'=>'form-control date', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Total Seats" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Total Seats', (isset($fields['total_seats']['language'])? $fields['total_seats']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('total_seats', $row['total_seats'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Available Seats" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Available Seats', (isset($fields['available_seats']['language'])? $fields['available_seats']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('available_seats', $row['available_seats'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Minimum Number Signups" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Minimum Number Signups', (isset($fields['minimum_number_signups']['language'])? $fields['minimum_number_signups']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('minimum_number_signups', $row['minimum_number_signups'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Status" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Status', (isset($fields['status']['language'])? $fields['status']['language'] : array())) !!}
					</label>
					<div class="col-md-6">

							<select name="status" class=" select2" id="status_id">


							</select>

					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Created By" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Created By', (isset($fields['created_by']['language'])? $fields['created_by']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name="created_by" class="select2" id="createdby_id">

					  </select>
					 </div>
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 



				  </fieldset>
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

	$("#status_id").jCombo("{{ URL::to('course/comboselect?filter=yes_no:id:yesno') }}",
			{  selected_value : '{{ $row["status"] }}',initial_text:'Select Status' });
	$("#createdby_id").jCombo("{{ URL::to('course/comboselect?filter=users:id:username') }}",
			{  selected_value : '{{ $row["created_by"] }}',initial_text:'Select Username' });
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	$(".select2").select2({ width:"98%"});	
	$('.date').datepicker({format:'mm/dd/yyyy',autoclose:true})
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
	var form = $('#courseFormAjax'); 
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