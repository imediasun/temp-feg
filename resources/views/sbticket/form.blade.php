<?php  ?>
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'sbticket/save/'.SiteHelpers::encryptID($row['TicketID']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'sbticketFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Sbticket</legend>
									

				  <div class="form-group  " > 
					<label for="Subject" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Subject', (isset($fields['Subject']['language'])? $fields['Subject']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Subject', $row['Subject'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Description" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Description', (isset($fields['Description']['language'])? $fields['Description']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='Description' rows='5' id='Description' class='form-control '  
				         required  >{{ $row['Description'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Priority" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Priority', (isset($fields['Priority']['language'])? $fields['Priority']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $Priority = explode(',',$row['Priority']);
					$Priority_opt = array('normal' => 'Normal' ,  'emergency' => 'Emergency'); ?>
					<select name='Priority' rows='5' required  class='select2 '  >
                        <option value="">Select Priority</option>
						<?php 
						foreach($Priority_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['Priority'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Status" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Status', (isset($fields['Status']['language'])? $fields['Status']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $Status = explode(',',$row['Status']);
					$Status_opt = array( 'open' => 'Open' ,  'inqueue' => 'Pending' ,  'closed' => 'Closed' , ); ?>
					<select name='Status' rows='5'   class='select2 ' @if(!$in_edit_mode) disabled @endif  >

						<?php
                            if($in_edit_mode)
                                {
						foreach($Status_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['Status'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}
                            }
                            else{
                                echo "<option  value ='open'>Open</option>";
                            }

						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Issue Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Issue Type', (isset($fields['issue_type']['language'])? $fields['issue_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $issue_type = explode(',',$row['issue_type']);
					$issue_type_opt = array('Order Request' => 'Order Request' ,'Support Issue' => 'Support Issue' ); ?>
					<select name='issue_type' rows='5'   class='select2 ' required >
                        <option value="">Select Issue Type</option>
						<?php 
						foreach($issue_type_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['issue_type'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Location" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Location', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='location_id' rows='5' id='location_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " style="display:none" >
					<label for="Game" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Game', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='game_id' rows='5' id='game_id' class='select2 '   ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " style="display:none" >
					<label for="Department" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Department', (isset($fields['department_id']['language'])? $fields['department_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='department_id' rows='5' id='department_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					

				  <div class="form-group  " style="display:none" >
					<label for="Assign To" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Assign To', (isset($fields['assign_to']['language'])? $fields['assign_to']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='assign_to[]' multiple rows='5' id='assign_to' class='select2 '  ></select>
					 </div>
					 <div class="col-md-2">
					 	
					 </div>
				  </div>

							<div class="form-group  " >
								<label for="Needed Date" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Needed Date', (isset($fields['need_by_date']['language'])? $fields['need_by_date']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									<div class="input-group datepicker" style="width:150px ">
									{!! Form::text('need_by_date', $row['need_by_date'],array('class'=>'form-control', 'id'=>'my-datepicker', 'style'=>'width:150px !important;'   )) !!}

									<span class="input-group-addon "><i class="fa fa-calendar" id="icon"></i></span>
								</div>
								<div class="col-md-2">

								</div>
									</div>
							</div>




						<!--	<div class="form-group  " >
							<label for="Needed Date" class=" control-label col-md-4 text-left">
							{{ SiteHelpers::activeLang('Needed Date', (isset($fields['need_by_date']['language'])? $fields['need_by_date']['language'] : array())) }}
							</label>
								<div class="col-md-6">
								<div class="input-group datepicker" style="width:150px ">
									{!! Form::text('Needed Date', date("m/d/Y", strtotime($row['need_by_date'])),array('class'=>'form-control ',  ))    !!}
									<span class="input-group-addon "><i class="fa fa-calendar" id="icon"></i></span>
								</div>
								<div class="col-md-2">

								</div>
							</div>
								</div>  -->


				  <div class="form-group  " > 
					<label for="File Path" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('File Path', (isset($fields['file_path']['language'])? $fields['file_path']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles('file_path')"><i class="fa fa-plus"></i></a>
					<div class="file_pathUpl">	
					 	<input  type='file' name='file_path[]'  />			
					</div>
					<ul class="uploadedLists " >
					<?php $cr= 0; 
					$row['file_path'] = explode(",",$row['file_path']);
					?>
					@foreach($row['file_path'] as $files)
						@if(file_exists('.'.$files) && $files !='')
						<li id="cr-<?php echo $cr;?>" class="">							
							<a href="{{ url('/'.$files) }}" target="_blank" >{{ $files }}</a> 
							<span class="pull-right" rel="cr-<?php echo $cr;?>" onclick=" $(this).parent().remove();"><i class="fa fa-trash-o  btn btn-xs btn-danger"></i></span>
							<input type="hidden" name="currfile_path[]" value="{{ $files }}"/>
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

@endif	
<?php
            if(!$in_edit_mode)
                {
                    $row['location_id']=\Session::get('selected_location');
                }

        ?>

</div>
			 
<script type="text/javascript">
$(document).ready(function() { 
	
        $("#location_id").jCombo("{{ URL::to('sbticket/comboselect?filter=location:id:location_name') }}",
        {  selected_value : '{{ $row["location_id"] }}','initial-text': "Select Location" });
        
      //  $("#game_id").jCombo("{{-- URL::to('sbticket/comboselect?filter=game:id:game_name') }}&limit=where:game_name:!=:''&parent=location_id:",
        //{  parent: '#location_id', selected_value : '{{ $row["game_id"] --}}' });
        
        //$("#department_id").jCombo("{{--URL::to('sbticket/comboselect?filter=departments:id:name') }}",
        //{  selected_value : '{{ $row["department_id"] --}}' });
        
     //   $("#debit_card").jCombo("{{-- URL::to('sbticket/comboselect?filter=debit_type:company:company') }}",
        {  selected_value : '{{ $row["debit_card"] }}','initial-text': "Select Debit Type" --});
        
      //  $("#assign_to").jCombo("{{-- URL::to('sbticket/comboselect?filter=employees:id:first_name|last_name') }}",
        //{  selected_value : '{{ $row["assign_to"] --}}' });


	$('#icon').click(function(){
		$(document).ready(function(){
			$("#my-datepicker").datepicker().focus();
		});
	});
	
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
	var form = $('#sbticketFormAjax'); 
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