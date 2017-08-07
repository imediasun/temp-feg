
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> @if($id)
					<i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Product In Development Report
				@else
					<i class="fa fa-plus"></i>&nbsp;&nbsp;Create New Product In Development Report
				@endif
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'productsindevelopmentreport/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'productsindevelopmentreportFormAjax')) !!}
			<div class="col-md-12">
						<fieldset>
				
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
					<label for="DateAdded" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('DateAdded', (isset($fields['DateAdded']['language'])? $fields['DateAdded']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('DateAdded', $row['DateAdded'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Description" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Description', (isset($fields['Description']['language'])? $fields['Description']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('Description', $row['Description'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Size" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Size', (isset($fields['Size']['language'])? $fields['Size']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('Size', $row['Size'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Items" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Items', (isset($fields['Items']['language'])? $fields['Items']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('Items', $row['Items'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="CasePrice" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('CasePrice', (isset($fields['CasePrice']['language'])? $fields['CasePrice']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('CasePrice', $row['CasePrice'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Details" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Details', (isset($fields['Details']['language'])? $fields['Details']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('Details', $row['Details'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="ETA" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('ETA', (isset($fields['ETA']['language'])? $fields['ETA']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('ETA', $row['ETA'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Start Date" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Start Date', (isset($fields['start_date']['language'])? $fields['start_date']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('start_date', $row['start_date'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="End Date" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('End Date', (isset($fields['end_date']['language'])? $fields['end_date']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('end_date', $row['end_date'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> </fieldset>
			</div>
			
												
								
						
			<div style="clear:both"></div>	
							
			<div class="form-group">
				<div class="col-sm-12 text-center">	
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
	renderDropdown($(".select2 "), { width:"100%"});
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
	var form = $('#productsindevelopmentreportFormAjax'); 
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
