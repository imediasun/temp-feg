
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> @if($id)
					<i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Game Play Rank by Location
				@else
					<i class="fa fa-plus"></i>&nbsp;&nbsp;Create New Game Play Rank by Location
				@endif
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'gameplayrankbylocation/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'gameplayrankbylocationFormAjax')) !!}
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
					<label for="Debit Type Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Debit Type Id', (isset($fields['debit_type_id']['language'])? $fields['debit_type_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('debit_type_id', $row['debit_type_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<label for="Game Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Game Id', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_id', $row['game_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Reader Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Reader Id', (isset($fields['reader_id']['language'])? $fields['reader_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('reader_id', $row['reader_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Play Value" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Play Value', (isset($fields['play_value']['language'])? $fields['play_value']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('play_value', $row['play_value'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Total Notional Value" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Total Notional Value', (isset($fields['total_notional_value']['language'])? $fields['total_notional_value']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('total_notional_value', $row['total_notional_value'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Std Plays" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Std Plays', (isset($fields['std_plays']['language'])? $fields['std_plays']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('std_plays', $row['std_plays'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Std Card Credit" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Std Card Credit', (isset($fields['std_card_credit']['language'])? $fields['std_card_credit']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('std_card_credit', $row['std_card_credit'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Std Card Credit Bonus" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Std Card Credit Bonus', (isset($fields['std_card_credit_bonus']['language'])? $fields['std_card_credit_bonus']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('std_card_credit_bonus', $row['std_card_credit_bonus'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Std Actual Cash" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Std Actual Cash', (isset($fields['std_actual_cash']['language'])? $fields['std_actual_cash']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('std_actual_cash', $row['std_actual_cash'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Std Card Dollar" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Std Card Dollar', (isset($fields['std_card_dollar']['language'])? $fields['std_card_dollar']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('std_card_dollar', $row['std_card_dollar'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Std Card Dollar Bonus" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Std Card Dollar Bonus', (isset($fields['std_card_dollar_bonus']['language'])? $fields['std_card_dollar_bonus']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('std_card_dollar_bonus', $row['std_card_dollar_bonus'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Time Plays" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Time Plays', (isset($fields['time_plays']['language'])? $fields['time_plays']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('time_plays', $row['time_plays'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Time Play Dollar" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Time Play Dollar', (isset($fields['time_play_dollar']['language'])? $fields['time_play_dollar']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('time_play_dollar', $row['time_play_dollar'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Time Play Dollar Bonus" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Time Play Dollar Bonus', (isset($fields['time_play_dollar_bonus']['language'])? $fields['time_play_dollar_bonus']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('time_play_dollar_bonus', $row['time_play_dollar_bonus'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Product Plays" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Product Plays', (isset($fields['product_plays']['language'])? $fields['product_plays']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_plays', $row['product_plays'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Service Plays" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Service Plays', (isset($fields['service_plays']['language'])? $fields['service_plays']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('service_plays', $row['service_plays'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Courtesy Plays" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Courtesy Plays', (isset($fields['courtesy_plays']['language'])? $fields['courtesy_plays']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('courtesy_plays', $row['courtesy_plays'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Date Start" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Date Start', (isset($fields['date_start']['language'])? $fields['date_start']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('date_start', $row['date_start'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Date End" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Date End', (isset($fields['date_end']['language'])? $fields['date_end']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('date_end', $row['date_end'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Ticket Payout" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Ticket Payout', (isset($fields['ticket_payout']['language'])? $fields['ticket_payout']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('ticket_payout', $row['ticket_payout'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Ticket Value" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])? $fields['ticket_value']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('ticket_value', $row['ticket_value'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
	var form = $('#gameplayrankbylocationFormAjax'); 
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
