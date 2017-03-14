
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'sbinvoiceitem/save/'.SiteHelpers::encryptID($row['ItemID']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'sbinvoiceitemFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Invoice Items</legend>
									
								  <div class="form-group  " > 
									<label for="ItemID" class=" control-label col-md-4 text-left"> 
									{!! SiteHelpers::activeLang('ItemID', (isset($fields['ItemID']['language'])? $fields['ItemID']['language'] : array())) !!}	
									</label>
									<div class="col-md-6">
									  {!! Form::text('ItemID', $row['ItemID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " > 
									<label for="InvoiceID" class=" control-label col-md-4 text-left"> 
									{!! SiteHelpers::activeLang('InvoiceID', (isset($fields['InvoiceID']['language'])? $fields['InvoiceID']['language'] : array())) !!}	
									</label>
									<div class="col-md-6">
									  {!! Form::text('InvoiceID', $row['InvoiceID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " > 
									<label for="Qty" class=" control-label col-md-4 text-left"> 
									{!! SiteHelpers::activeLang('Qty', (isset($fields['Qty']['language'])? $fields['Qty']['language'] : array())) !!}	
									</label>
									<div class="col-md-6">
									  {!! Form::text('Qty', $row['Qty'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " > 
									<label for="Price" class=" control-label col-md-4 text-left"> 
									{!! SiteHelpers::activeLang('Price', (isset($fields['Price']['language'])? $fields['Price']['language'] : array())) !!}	
									</label>
									<div class="col-md-6">
									  {!! Form::text('Price', $row['Price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									 </div> 
									 <div class="col-md-2">
									 	
									 </div>
								  </div> 					
								  <div class="form-group  " > 
									<label for="Total" class=" control-label col-md-4 text-left"> 
									{!! SiteHelpers::activeLang('Total', (isset($fields['Total']['language'])? $fields['Total']['language'] : array())) !!}	
									</label>
									<div class="col-md-6">
									  {!! Form::text('Total', $row['Total'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
	renderDropdown($(".select2 "), { width:"98%"});
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
	var form = $('#sbinvoiceitemFormAjax'); 
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