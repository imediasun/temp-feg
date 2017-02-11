
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'managefegrequeststore/save/'.$row['id'], 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'managefegrequeststoreFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Manage FEG Request Store</legend>
				  <div class="form-group  " > 
					<label for="Qty" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Qty', (isset($fields['qty']['language'])? $fields['qty']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('qty', $row['qty'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					

				  <div class="form-group  " > 
					<label for="Status Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Status ', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select class="select4" name="status_id" id="status_id"></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					

				  <div class="form-group  " > 
					<label for="Notes" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
                        <textarea rows="8" cols="45" name="notes" class="form-control">{{ $row['notes'] }}</textarea>
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

	

			 
<script type="text/javascript">
$(document).ready(function() {

    $("#status_id").jCombo("{{ URL::to('shopfegrequeststore/comboselect?filter=merch_request_status:id:status') }}",
            {selected_value: '{{ $row['status_id'] }}', initial_text: 'Select Status'});
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	renderDropdown($(".select2, .select3, .select4, select5"), { width:"98%"});
	$('.date').datepicker({format:'mm/dd/yyyy',autoClose:true})
	$('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square_green'
	});			
	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});			
	var form = $('#managefegrequeststoreFormAjax'); 
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
		ajaxFilter('#{{ $pageModule }}','{{ $pageModule }}/data',data.view);
		notyMessage(data.message);	
		$('#sximo-modal').modal('hide');	
	} else {
		notyMessageError(data.message);	
		$('.ajaxLoading').hide();
		return false;
	}	
}			 

</script>



