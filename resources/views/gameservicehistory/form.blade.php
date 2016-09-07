
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'gameservicehistory/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'gameservicehistoryFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Game Service History</legend>
				
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
					<label for="Game Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Id', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='game_id' rows='5' id='game_id' class='form-control '
				           >{{ $row['game_id'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Down" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Down', (isset($fields['date_down']['language'])? $fields['date_down']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='date_down' rows='5' id='date_down' class='form-control '
				           >{{ $row['date_down'] }}</textarea>
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
					<label for="Down User Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Down User Id', (isset($fields['down_user_id']['language'])? $fields['down_user_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='down_user_id' rows='5' id='down_user_id' class='form-control '
				           >{{ $row['down_user_id'] }}</textarea>
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
					<label for="Date Up" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Up', (isset($fields['date_up']['language'])? $fields['date_up']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='date_up' rows='5' id='date_up' class='form-control '
				           >{{ $row['date_up'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Up User Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Up User Id', (isset($fields['up_user_id']['language'])? $fields['up_user_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='up_user_id' rows='5' id='up_user_id' class='form-control '
				           >{{ $row['up_user_id'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Game Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Name', (isset($fields['game_name']['language'])? $fields['game_name']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='game_name' rows='5' id='game_name' class='form-control '
				           >{{ $row['game_name'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Daysdiff" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Daysdiff', (isset($fields['daysdiff']['language'])? $fields['daysdiff']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='daysdiff' rows='5' id='daysdiff' class='form-control '
				           >{{ $row['daysdiff'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> </fieldset>
			</div>
			
												
				

	@if($subgrid['access']['is_add'] == '1')				
	<hr /><div class="clr clear"></div>	
	
	<h5> test </h5>
	
	<div class="table-responsive">
	    <table class="table table-striped ">
	        <thead>
				<tr>
					@foreach ($subgrid['tableGrid'] as $t)
						@if($t['view'] =='1' && $t['field'] !='game_id')
							<th>
							{{ SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) }}
							</th>
						@endif
					@endforeach
					<th></th>	
				  </tr>

	        </thead>

        <tbody>
        @if(count($subgrid['rowData'])>=1)
            @foreach ($subgrid['rowData'] as $rows)
	            <tr class="clone clonedInput">									
					 @foreach ($subgrid['tableGrid'] as $field)
						 @if($field['view'] =='1' && $field['field'] !='game_id')
						 <td>					 
						 	{!! SiteHelpers::bulkForm($field['field'] , $subgrid['tableForm'] , $rows->$field['field']) !!}							 
						 </td>
						 @endif					 
					 
					 @endforeach
					 <td>
					 	<a onclick=" $(this).parents('.clonedInput').remove(); return false" href="#" class="remove btn btn-xs btn-danger">-</a>
					 	<input type="hidden" name="counter[]">
					 </td>
				</tr>  
			@endforeach
			

		@else

            <tr class="clone clonedInput">								
			 @foreach ($subgrid['tableGrid'] as $field)

				 @if($field['view'] =='1' && $field['field'] !='game_id')
				 <td>					 
				 	{!! SiteHelpers::bulkForm($field['field'] , $subgrid['tableForm'] ) !!}							 
				 </td>
				 @endif					 
			 
			 @endforeach
				 <td>
				 	<a onclick=" $(this).parents('.clonedInput').remove(); return false" href="#" class="remove btn btn-xs btn-danger">-</a>
				 	<input type="hidden" name="counter[]">
				 </td>
			
			</tr> 	
		@endif	


        </tbody>	

     	</table>  
    	<input type="hidden" name="enable-masterdetail" value="true">
    </div><br /><br />
     
     <a href="javascript:void(0);" class="addC btn btn-xs btn-info" rel=".clone"><i class="fa fa-plus"></i> New Item</a>
     <hr />		
	@endif
     					
						
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
	 
	$('.addC').relCopy({});
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	$(".select2").select2({ width:"98%"});	
	$('.date').datepicker({format:'mm/dd/yyyy',autoclose:true})
	$('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
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
	var form = $('#gameservicehistoryFormAjax'); 
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