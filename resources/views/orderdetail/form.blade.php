
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'orderdetail/save/'.SiteHelpers::encryptID($row['productCode']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'orderdetailFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Order Details</legend>
									
				  <div class="form-group  " > 
					<label for="OrderNumber" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('OrderNumber', (isset($fields['orderNumber']['language'])? $fields['orderNumber']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='orderNumber' rows='5' id='orderNumber' class='select2 '   ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="ProductCode" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ProductCode', (isset($fields['productCode']['language'])? $fields['productCode']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='productCode' rows='5' id='productCode' class='select2 ' required  ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="QuantityOrdered" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('QuantityOrdered', (isset($fields['quantityOrdered']['language'])? $fields['quantityOrdered']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('quantityOrdered', $row['quantityOrdered'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="PriceEach" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('PriceEach', (isset($fields['priceEach']['language'])? $fields['priceEach']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('priceEach', $row['priceEach'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="OrderLineNumber" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('OrderLineNumber', (isset($fields['orderLineNumber']['language'])? $fields['orderLineNumber']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('orderLineNumber', $row['orderLineNumber'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
	
        $("#orderNumber").jCombo("{{ URL::to('orderdetail/comboselect?filter=orders:orderNumber:orderNumber') }}",
        {  selected_value : '{{ $row["orderNumber"] }}' });
        
        $("#productCode").jCombo("{{ URL::to('orderdetail/comboselect?filter=products:productCode:productName') }}",
        {  selected_value : '{{ $row["productCode"] }}' });
         
	
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	renderDropdown($(".select2 "), { width:"100%"});
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
	var form = $('#orderdetailFormAjax'); 
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