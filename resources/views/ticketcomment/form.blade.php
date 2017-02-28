
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'ticketcomment/save/'.SiteHelpers::encryptID($row['CommentID']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'ticketcommentFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> TicketComment</legend>
									
				  <div class="form-group  " > 
					<label for="CommentID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('CommentID', (isset($fields['CommentID']['language'])? $fields['CommentID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('CommentID', $row['CommentID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="TicketID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('TicketID', (isset($fields['TicketID']['language'])? $fields['TicketID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('TicketID', $row['TicketID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Comments" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Comments', (isset($fields['Comments']['language'])? $fields['Comments']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='Comments' rows='5' id='Comments' class='form-control '  
				           >{{ $row['Comments'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Posted" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Posted', (isset($fields['Posted']['language'])? $fields['Posted']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('Posted', $row['Posted'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="UserID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('UserID', (isset($fields['UserID']['language'])? $fields['UserID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('UserID', $row['UserID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Attachments" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Attachments', (isset($fields['Attachments']['language'])? $fields['Attachments']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles('Attachments')"><i class="fa fa-plus"></i></a>
					<div class="AttachmentsUpl">	
					 	<input  type='file' name='Attachments[]'  />			
					</div>
					<ul class="uploadedLists " >
					<?php $cr= 0; 
					$row['Attachments'] = explode(",",$row['Attachments']);
					?>
					@foreach($row['Attachments'] as $files)
						@if(file_exists('.'.$files) && $files !='')
						<li id="cr-<?php echo $cr;?>" class="">							
							<a href="{{ url('/'.$files) }}" target="_blank" >{{ $files }}</a> 
							<span class="pull-right" rel="cr-<?php echo $cr;?>" onclick=" $(this).parent().remove();"><i class="fa fa-trash-o  btn btn-xs btn-danger"></i></span>
							<input type="hidden" name="currAttachments[]" value="{{ $files }}"/>
							<?php ++$cr;?>
						</li>
						@endif
					
					@endforeach
					</ul>
					 
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
	var form = $('#ticketcommentFormAjax'); 
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