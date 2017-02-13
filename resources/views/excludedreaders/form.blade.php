{!! $isEdit = !empty($row['id']) !!}
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'excludedreaders/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'excludedreadersFormAjax')) !!}
			<div class="col-md-12">
                  <fieldset><legend>@if($isEdit) Edit @else Add @endif Excluded Readers</legend>
				  <div class="form-group  " >
					<label for="Reader Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Reader Id', (isset($fields['reader_id']['language'])? $fields['reader_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('reader_id', $row['reader_id'],array('class'=>'form-control', 'placeholder'=>'', 'required' => 'required' )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Location" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc Id', (isset($fields['loc_id']['language'])? $fields['loc_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
                       <select required="required" class='select2'
                               name='loc_id'  id='location_id' >
                            <option value=''>-- Select Location --</option>
                            @foreach($myLocations as $location)
                                <option value='{{ $location->id }}' 
                                    data-debit-type="{{ $location->debit_type_id }}"
                                    @if($location->id == $row['loc_id']) selected @endif
                                >{{ $location->id }} | {{ $location->location_name }}</option>
                            @endforeach                               
                        </select>					  
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Debit Type" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Debit Type Id', (isset($fields['debit_type_id']['language'])? $fields['debit_type_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
                        <select class='select2'
                              name='debit_type_id'  id='debit_type_id' >
                            <option value=''>-- Select Debit Company --</option>
                            @foreach($debitTypes as $debitType)
                                <option value='{{ $debitType->id }}' 
                                    @if($debitType->id == $row['debit_type_id']) selected @endif
                                >{{ $debitType->company }}</option>
                            @endforeach                               
                       </select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Reason" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Reason', (isset($fields['reason']['language'])? $fields['reason']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('reason', $row['reason'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
	
    
    $("#location_id").on('change', function(){
        var debitType = $(this).find("option::selected").data('debit-type'),
            debitTypeField = $("#debit_type_id");
        if (debitType) {
            if (debitTypeField.data('select2')) {
                debitTypeField.select2('val', debitType);
            }
            else {
                debitTypeField.val(debitType);
            }            
        }
    });
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	renderDropdown($(".select2, .select3, .select4, select5"), { width:"100%"});
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
	var form = $('#excludedreadersFormAjax'); 
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