
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'product/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'productFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> FEG Store Products</legend>
									
				  <div class="form-group  " > 
					<label for="Vendor Description" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Vendor Description', (isset($fields['vendor_description']['language'])? $fields['vendor_description']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='vendor_description' rows='5' id='vendor_description' class='form-control '  
				           >{{ $row['vendor_description'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Size" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Size', (isset($fields['size']['language'])? $fields['size']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('size', $row['size'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Vendor Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Vendor Id', (isset($fields['vendor_id']['language'])? $fields['vendor_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='vendor_id' rows='5' id='vendor_id' class='select2 '   ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Unit Price" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Unit Price', (isset($fields['unit_price']['language'])? $fields['unit_price']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('unit_price', $row['unit_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Case Price" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Case Price', (isset($fields['case_price']['language'])? $fields['case_price']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('case_price', $row['case_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Retail Price" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Retail Price', (isset($fields['retail_price']['language'])? $fields['retail_price']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('retail_price', $row['retail_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Ticket Value" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])? $fields['ticket_value']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $ticket_value = explode(',',$row['ticket_value']);
					$ticket_value_opt = array( '0' => 'No' , ); ?>
					<select name='ticket_value' rows='5'   class='select2 '  > 
						<?php 
						foreach($ticket_value_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['ticket_value'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Prod Type Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Prod Type Id', (isset($fields['prod_type_id']['language'])? $fields['prod_type_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='prod_type_id' rows='5' id='prod_type_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Prod Sub Type Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Prod Sub Type Id', (isset($fields['prod_sub_type_id']['language'])? $fields['prod_sub_type_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='prod_sub_type_id' rows='5' id='prod_sub_type_id' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Img" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Img', (isset($fields['img']['language'])? $fields['img']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <input  type='file' name='img' id='img' @if($row['img'] =='') class='required' @endif style='width:150px !important;'  />
					 	<div >
						{!! SiteHelpers::showUploadedFile($row['img'],'') !!}
						
						</div>					
					 
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
	
        $("#vendor_id").jCombo("{{ URL::to('product/comboselect?filter=vendor:id:vendor_name') }}",
        {  selected_value : '{{ $row["vendor_id"] }}' });
        
        $("#prod_type_id").jCombo("{{ URL::to('product/comboselect?filter=product_type:id:product_type') }}",
        {  selected_value : '{{ $row["prod_type_id"] }}' });
        
        $("#prod_sub_type_id").jCombo("{{ URL::to('product/comboselect?filter=product_type:id:product_type') }}",
        {  selected_value : '{{ $row["prod_sub_type_id"] }}' });
         
	
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
	var form = $('#productFormAjax'); 
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