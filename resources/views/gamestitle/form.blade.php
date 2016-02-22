
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'gamestitle/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'gamestitleFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Games Title</legend>
									
				  <div class="form-group  " > 
					<label for="Game Title" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Game Title', (isset($fields['game_title']['language'])? $fields['game_title']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_title', $row['game_title'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Mfg Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Mfg Id', (isset($fields['mfg_id']['language'])? $fields['mfg_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='mfg_id' rows='5' id='mfg_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Game Type Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Game Type Id', (isset($fields['game_type_id']['language'])? $fields['game_type_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='game_type_id' rows='5' id='game_type_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Has Manual" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Has Manual', (isset($fields['has_manual']['language'])? $fields['has_manual']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $has_manual = explode(',',$row['has_manual']);
					$has_manual_opt = array( '1' => 'Yes' ,  '0' => 'No' , ); ?>
					<select name='has_manual' rows='5'   class='select2 '  > 
						<?php 
						foreach($has_manual_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['has_manual'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Has Servicebulletin" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Has Servicebulletin', (isset($fields['has_servicebulletin']['language'])? $fields['has_servicebulletin']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
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
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Num Prize Meters" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Num Prize Meters', (isset($fields['num_prize_meters']['language'])? $fields['num_prize_meters']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $num_prize_meters = explode(',',$row['num_prize_meters']);
					$num_prize_meters_opt = array( '1' => 'Yes' , ); ?>
					<select name='num_prize_meters' rows='5'   class='select2 '  > 
						<?php 
						foreach($num_prize_meters_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['num_prize_meters'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
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
	
        $("#mfg_id").jCombo("{{ URL::to('gamestitle/comboselect?filter=game_title:id:mfg_id') }}",
        {  selected_value : '{{ $row["mfg_id"] }}' });
        
        $("#game_type_id").jCombo("{{ URL::to('gamestitle/comboselect?filter=game_type:id:game_type_short') }}",
        {  selected_value : '{{ $row["game_type_id"] }}' });
         
	
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	$(".select2").select2({ width:"98%"});	
	$('.date').datepicker({format:'yyyy-mm-dd',autoClose:true})
	$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'}); 
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