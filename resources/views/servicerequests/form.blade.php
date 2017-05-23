{{--*/      use App\Library\FEG\System\FEGSystemHelper;                   /*--}}
@if($setting['form-method'] =='native')
<div class="sbox">
		<div class="sbox-title">  
			<h4>
				@if($id)
					<i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Ticket
				@else
					<i class="fa fa-plus"></i>&nbsp;&nbsp;Create New Ticket
				@endif
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
    {!! Form::open(array('url'=>'servicerequests/save/'.$row['TicketID'], 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'sbticketFormAjax')) !!}

		<input type="hidden" name='assign_to' value="{{ $row['assign_to']}}">        
		<input type="hidden" name='entry_by' value="{{ $entryBy }}">
		<div class="col-md-12 clearfix p-lg-f">
            <fieldset>
                <div class="form-group  " > 
					<label for="Subject" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Subject', (isset($fields['Subject']['language'])? $fields['Subject']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Subject', $row['Subject'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'required'  )) !!}
					 </div> 
					 <div class="col-md-2"></div>
                </div> 					
                <div class="form-group  " > 
					<label for="Description" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Description', (isset($fields['Description']['language'])? $fields['Description']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='Description' rows='5' id='Description' class='form-control '  
				         required  >{{ $row['Description'] }}</textarea> 
					 </div> 
					 <div class="col-md-2"></div>
                </div> 					
                <div class="form-group  " > 
					<label for="Priority" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Priority', (isset($fields['Priority']['language'])? $fields['Priority']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
                        <select name='Priority' required class='select2 ' data-current-date='{{ date('Y-m-d') }}'>
                            @foreach($priorityOptions as $key => $val)
                                <option  value ='{{ $key }}' 
                                    @if($priority == $key) selected='selected' @endif
                                >{{ $val }}</option>
                            @endforeach
                        </select>
					 </div> 
					 <div class="col-md-2"></div>
                </div> 					
                <div class="form-group  " > 
					<label for="Status" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Status', (isset($fields['Status']['language'])? $fields['Status']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">					  
                        @if($isAdd || !$canChangeStatus) 
                            <input type='hidden' name='oldStatus' value='{{ $status }}' />
                            <input type='hidden' name='Status' value='{{ empty($status) ? 'open' : $status }}' />
                            <input type="text" readonly class="form-control" value="{{ $ticketStatusLabel }}" /> 
                        @else
                            <input type='hidden' name='oldStatus' value='{{ $status }}' />
                            <select name='Status' required class='select2 '>
                            	@foreach($statusOptions as $key => $val)
                                    <option  value ='{{ $key }}' 
                                        @if($status == $key) selected='selected' @endif
                                    >{{ $val }}</option>
                                @endforeach
                            </select>
                        @endif
					 </div> 
					 <div class="col-md-2"></div>
                </div> 					
                <div class="form-group  " > 
					<label for="Issue Type" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Issue Type', (isset($fields['issue_type']['language'])? $fields['issue_type']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					<select name='issue_type' rows='5'   class='select2 ' required >
                        <option value="">Select Issue Type</option>
                           	@foreach($issueTypeOptions as $key=>$val)
                                    <option  value ='{{ $key }}' 
                                        @if($issueType == $key) selected='selected' @endif
                                    >{{ $val }}</option>
                            @endforeach                        
						</select> 
					 </div> 
					 <div class="col-md-2"></div>
                </div> 					
                <div class="form-group  " > 
					<label for="Location" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Location', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='location_id' rows='5' id='location_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2"></div>
                </div> 					
                <div class="form-group  " style="display:none" >
					<label for="Game" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Game', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='game_id' rows='5' id='game_id' class='select2 '   ></select> 
					 </div> 
					 <div class="col-md-2"></div>
                </div> 					
                <div class="form-group  " style="display:none" >
					<label for="Department" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Department', (isset($fields['department_id']['language'])? $fields['department_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='department_id' rows='5' id='department_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2"></div>
                </div> 					

                <div class="form-group  " style="display:none" >
					<label for="Assign To" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Assign To', (isset($fields['assign_to']['language'])? $fields['assign_to']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='assign_to[]' multiple  id='assign_to' class='select2 '  ></select>
					 </div>
					 <div class="col-md-2"></div>
                </div>

                <div class="form-group  " >
                    <label for="Date Needed" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('Date Needed', (isset($fields['need_by_date']['language'])? $fields['need_by_date']['language'] : array())) !!}
                    </label>
                    <div class="col-md-6">
                        <div class="input-group">
							<span class="input-group-addon datepickerHandleButton" style="width: 32px;"><i class="fa fa-calendar" id="icon"></i></span>
							{!! Form::text('need_by_date', $needByDate, array('class'=>'form-control date', 'id'=>'my-datepicker', 'style'=>'width:150px !important;'   )) !!}
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>

                <div class="form-group clearfix" > 
					<label for="Attach File" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Attach File', (isset($fields['file_path']['language'])? $fields['file_path']['language'] : array())) !!}
					</label>
					<div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="file_pathUpl">	
                            <input  type='file' name='file_path[]'  />			
                        </div>
                        <a href="javascript:void(0)" class="btn btn-xs btn-primary" onclick="addMoreFiles('file_path')"><i class="fa fa-plus"></i> Add more files</a>
                        <ul class="uploadedLists " >
                        @foreach($filePaths as $cr => $file)
                            @if($file !='')
                            <li id="cr-{!! $cr !!}" class="">							
                                <a href="{{ url('.'.$file) }}" target="_blank" >{{  FEGSystemHelper::getSanitizedFileNameForTicketAttachments($file, 50) }}</a> 
                                <span class="pull-right" rel="cr-{!! $cr !!}" onclick=" $(this).parent().remove();"><i class="fa fa-trash-o  btn btn-xs btn-danger"></i></span>
                                <input type="hidden" name="currfile_path[]" value="{{ $file }}"/>
                            </li>
                            @endif
                        @endforeach
                        </ul>					 
					 </div>
                </div>
            </fieldset>
        </div>
			
        @if (!$isAdd)
            {!! Form::hidden('Created', $row['Created']) !!}
            {!! Form::hidden('department_id', $row->department_id) !!}
            {!! Form::hidden('assign_to', $row->assign_to) !!}
            {!! Form::hidden('game_id', $row->game_id) !!}								
        @endif
							
        <div class="form-group clearfix">
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
			 
<script type="text/javascript">
$(document).ready(function() { 
	
    $("#location_id").jCombo("{{ URL::to('sbticket/comboselect?filter=location:id:id|location_name') }}" + "&delimiter=%20|%20",
        {  selected_value : '{{ $locationId }}','initial-text': "Select Location" , ready:addInactiveItem("#location_id", {{ $locationId  }} , 'Location', 'active' , 'location_name' , 1) });

	$('.datepickerHandleButton').click(function(){
        $("#my-datepicker").datepicker().focus();
	});
	$('select[name=Priority]').change(function(){
        var elm = $(this),
            val = elm.val(),
            isSameDay = val == 'sameday',
            date = elm.data('current-date'),
            formattedDate,
            datePicker = $("#my-datepicker"),
            datePickerVal = datePicker.val();

        if (isSameDay && !datePickerVal) {
            formattedDate = $.datepicker.formatDate('mm/dd/yy', new Date(date));
            datePicker.datepicker('update', formattedDate);
        }
	});
	
	$('.editor').summernote();
    
	$('.previewImage').fancybox();	
    
	$('.tips').tooltip();	
    
	renderDropdown($(".select2"), { width:"100%"});	
    console.log( new Date() );
	$('.date').datepicker({format:'mm/dd/yyyy',startDate:new Date(Date.now() - 864e5),autoclose:true});
    
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
		ajaxViewClose('#' + pageModule);
		//ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
		notyMessage(data.message);	
		$('#sximo-modal').modal('hide');
        //window.location.href=window.location;
        $(".reloadDataButton").click();
	} else {
		notyMessageError(data.message);	
		$('.ajaxLoading').hide();
		return false;
	}	
}

</script>		 

<style>
	.file_pathUpl input, .attachmentInputs input {
		margin-bottom: 14px;
		width: 100%;
		margin-top: 0px;
	}
</style>
