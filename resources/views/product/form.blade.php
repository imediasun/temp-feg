
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'product/save/'.SiteHelpers::encryptID($row['productId']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'productFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Products</legend>
									
				  <div class="form-group  " > 
					<label for="ProductId" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ProductId', (isset($fields['productId']['language'])? $fields['productId']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='productId' rows='5' id='productId' class='form-control '  
				           >{{ $row['productId'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="ProductCode" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ProductCode', (isset($fields['productCode']['language'])? $fields['productCode']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='productCode' rows='5' id='productCode' class='form-control '  
				           >{{ $row['productCode'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="ProductName" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ProductName', (isset($fields['productName']['language'])? $fields['productName']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='productName' rows='5' id='productName' class='form-control '  
				           >{{ $row['productName'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="ProductScale" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ProductScale', (isset($fields['productScale']['language'])? $fields['productScale']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='productScale' rows='5' id='productScale' class='form-control '  
				           >{{ $row['productScale'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="ProductVendor" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ProductVendor', (isset($fields['productVendor']['language'])? $fields['productVendor']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='productVendor' rows='5' id='productVendor' class='form-control '  
				           >{{ $row['productVendor'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="ProductDescription" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ProductDescription', (isset($fields['productDescription']['language'])? $fields['productDescription']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='productDescription' rows='5' id='productDescription' class='form-control '  
				           >{{ $row['productDescription'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="QuantityInStock" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('QuantityInStock', (isset($fields['quantityInStock']['language'])? $fields['quantityInStock']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='quantityInStock' rows='5' id='quantityInStock' class='form-control '  
				           >{{ $row['quantityInStock'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="BuyPrice" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('BuyPrice', (isset($fields['buyPrice']['language'])? $fields['buyPrice']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='buyPrice' rows='5' id='buyPrice' class='form-control '  
				           >{{ $row['buyPrice'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="MSRP" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('MSRP', (isset($fields['MSRP']['language'])? $fields['MSRP']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='MSRP' rows='5' id='MSRP' class='form-control '  
				           >{{ $row['MSRP'] }}</textarea> 
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