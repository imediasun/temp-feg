{{--*/ $titleID = @$row['id'] /*--}}
{{--*/ $gameTitle = @$row['game_title'] /*--}}
{{--*/ $isEdit = !empty($titleID) /*--}}

@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'gamestitle/save/'.$titleID, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'gamestitleFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend>@if($isEdit) Edit @else Create New @endif Game Title</legend>
									
				  <div class="form-group  " > 
					<label for="Game Title" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Game Title', (isset($fields['game_title']['language'])? $fields['game_title']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_title', $row['game_title'],array(
                            'class'=>'form-control', 
                            'placeholder'=>'', 
                            'parsley-errors-container' => '.gameTitleValidatorMessage',
                            'required'=>'required',
                            'data-old-value' => $gameTitle
                        )) !!}
                        <div class="gameTitleValidatorMessage clearfix"></div>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Mfg Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Manufacturer', (isset($fields['mfg_id']['language'])? $fields['mfg_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
                        <input type="hidden" name="mfg_id" id="mfg_id" class="" style="width:98%" value="{{ $row['mfg_id'] }}"/>

					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Game Type Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Game Type', (isset($fields['game_type_id']['language'])? $fields['game_type_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='game_type_id' rows='5' id='game_type_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					




<div style="display:none">					<?php $has_manual = explode(',',$row['has_manual']);
					$has_manual_opt = array( '1' => 'Yes' ,  '0' => 'No' , ); ?>
					<select name='has_manual' rows='5'   class='select2 '  >
						<?php
						foreach($has_manual_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['has_manual'] == $key ? " selected='selected' " : '' ).">$val</option>";
						}
						?></select>
</div>
                            <div class="form-group  " >
                                <label for="Has Manual" class=" control-label col-md-4 text-left">
                                    {!! SiteHelpers::activeLang('Manual', (isset($fields['has_manual']['language'])? $fields['has_manual']['language'] : array())) !!}
                                </label>
                                <div class="col-md-6">
                                    <input type="file" name="manual" id="manual"/>
					 <div class="col-md-2">
					 	
					 </div>
				  </div>
                                </div>

				<div style="display:none">

					<?php $has_servicebulletin = explode(',',$row['has_servicebulletin']);
					$has_servicebulletin_opt = array( '0' => 'No' , ); ?>
					<select name='has_servicebulletin' rows='5'   class='select2 '  >
						<?php
						foreach($has_servicebulletin_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['has_servicebulletin'] == $key ? " selected='selected' " : '' ).">$val</option>";
						}
						?></select>
                </div>
                        <div class="form-group  " >
                            <label for="Has Servicebulletin" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Service Bulletin', (isset($fields['has_servicebulletin']['language'])? $fields['has_servicebulletin']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <input type="file" name="service_bulletin" id="service_bulletin"/>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div>
                            <div class="form-group  " >
                                <label for="img" class=" control-label col-md-4 text-left">
                                    {!! SiteHelpers::activeLang('Image', (isset($fields['img']['language'])? $fields['img']['language'] : array())) !!}
                                </label>
                                <div class="col-md-6">
                                    <input type="file"   name="img" id="img" @if(empty($row['img'])) required  @else style="color:white;width:23%;display:inline" @endif /><span>{{ isset($row['img'])?$row['img']:"" }}</span>
                                      <div style="margin-top:15px;">

                                        {!! SiteHelpers::showUploadedFile($row['img'],'/uploads/games/images/') !!}

                                    </div>

                                </div>
                                <div class="col-md-2">

                                </div>
                            </div>
                <!--div class="form-group  " >
					<label for="Num Prize Meters" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Num Prize Meters', (isset($fields['num_prize_meters']['language'])? $fields['num_prize_meters']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
    					{{--*/ $num_prize_meters = explode(',',$row['num_prize_meters']); /*--}}
        				{{--*/ $num_prize_meters_opt = array( '1' => 'Yes' ,'0'=> 'No' ); /*--}}
            			<select name='num_prize_meters' rows='5'   class='select2 '  > 
						@foreach($num_prize_meters_opt as $key=>$val)						
							<option  value ='$key' {{ $row['num_prize_meters'] == $key ? " selected='selected' " : '' }}">{{ $val }}</option>"; 						
						@endforeach						
						</select> 
					 </div> 
					 <div class="col-md-2"></div>
                </div--> 
            
        </fieldset>
    </div>
			
												
								
						
			<div style="clear:both"></div>	
							
			<div class="form-group">
				<label class="col-sm-4 text-right">&nbsp;</label>
				<div class="col-sm-8">	
					<button type="submit" id="submit_btn" class="btn btn-primary btn-sm submit_btn"><i class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
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
	

    $("#mfg_id").select2({
        data: <?php echo json_encode($vendor_options)?>,
        placeholder: "Select Manufacturer"
    });
        
    $("#game_type_id").jCombo("{{ URL::to('gamestitle/comboselect?filter=game_type:id:game_type') }}",
        {  selected_value : '{{ $row["game_type_id"] }}',initial_text:'Select Game Type' });
         
	
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	renderDropdown($(".select2, .select3, .select4, .select5"), { width:"98%"});
	$('.date').datepicker({format:'mm/dd/yyyy',autoClose:true})
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
	var form = $('#gamestitleFormAjax'); 
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
		} 
        else {
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

(function(){
    var promise, 
        form = $("#gamestitleFormAjax"),
        saveButton = form.find('.submit_btn'),
        pageUrl = '{{ $pageUrl }}';
    
    $('[name=game_title]').on("keyup",function (event) {
        validateGameTitle.call(this);   
    });
    
    function beforeValidateGameTitle (data) {
        saveButton.prop('disabled', true);
    }
    function postValidateGameTitle (data) {        
    }
    function successValidateGameTitle (data, msg) {
        if (data && data.valid) {
            saveButton.prop('disabled', false);
            msg = msg != "" ? "Game title is available" : msg;
            $(".gameTitleValidatorMessage").html('<b class="text-navy">' + msg + '</b>');
        }
        else {
            errValidateGameTitle();                        
        }        
    }
    function errValidateGameTitle (data, msg) {
        saveButton.prop('disabled', true);
        msg = msg || "Game title is not available";
        $(".gameTitleValidatorMessage").html('<b class="text-danger">' + msg +'</b>');
    }
    
    function validateGameTitle() {
        var elm = $(this),
            value = (elm.val() || '').replace(/^\s+?|\s+?$/g, ''),
            oldValue = elm.data('old-value'),
            data = { game_title : value },
            ajax;
    
        if (promise && promise.abort) {
            promise.abort();
        }
        if (!value) {
            return errValidateGameTitle({}, "Enter a Game Title");
        }
        if (value == oldValue) {
            return successValidateGameTitle({valid: true, local: true}, "");
        }
        
        saveButton.prop('disabled', true);
        promise = $.ajax({
            'type' : 'post',
            'url': pageUrl + '/gameexists',
            'data': data,
            'beforeSend': beforeValidateGameTitle
        });
        promise.done(asPromised);
        promise.fail(brokenPromise);
        promise.always(allTrust);     
    }
    
    function asPromised(data) {
        successValidateGameTitle(data);
    }    
    function brokenPromise(data, msg) {
        if (msg != 'abort') {
            errValidateGameTitle(data);
        }        
    }    
    function allTrust(data) {
    }
    
}());
</script>		 