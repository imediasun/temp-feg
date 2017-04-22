
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'throwreportpayout/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'throwreportpayoutFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Throw Report Payout</legend>
				
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
					<label for="User Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('User Id', (isset($fields['user_id']['language'])? $fields['user_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('user_id', $row['user_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					<label for="Location Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Location Id', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('location_id', $row['location_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Price Per Play" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Price Per Play', (isset($fields['price_per_play']['language'])? $fields['price_per_play']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('price_per_play', $row['price_per_play'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Id', (isset($fields['product_id']['language'])? $fields['product_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='product_id' rows='5' id='product_id' class='form-control '
				           >{{ $row['product_id'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Qty 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Qty 1', (isset($fields['product_qty_1']['language'])? $fields['product_qty_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_qty_1', $row['product_qty_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Cogs 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Cogs 1', (isset($fields['product_cogs_1']['language'])? $fields['product_cogs_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_cogs_1', $row['product_cogs_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Throw 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Throw 1', (isset($fields['product_throw_1']['language'])? $fields['product_throw_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_throw_1', $row['product_throw_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Id 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Id 2', (isset($fields['product_id_2']['language'])? $fields['product_id_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_id_2', $row['product_id_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Qty 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Qty 2', (isset($fields['product_qty_2']['language'])? $fields['product_qty_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_qty_2', $row['product_qty_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Cogs 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Cogs 2', (isset($fields['product_cogs_2']['language'])? $fields['product_cogs_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_cogs_2', $row['product_cogs_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Throw 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Throw 2', (isset($fields['product_throw_2']['language'])? $fields['product_throw_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_throw_2', $row['product_throw_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Id 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Id 3', (isset($fields['product_id_3']['language'])? $fields['product_id_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_id_3', $row['product_id_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Qty 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Qty 3', (isset($fields['product_qty_3']['language'])? $fields['product_qty_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_qty_3', $row['product_qty_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Cogs 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Cogs 3', (isset($fields['product_cogs_3']['language'])? $fields['product_cogs_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_cogs_3', $row['product_cogs_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Throw 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Throw 3', (isset($fields['product_throw_3']['language'])? $fields['product_throw_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_throw_3', $row['product_throw_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Id 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Id 4', (isset($fields['product_id_4']['language'])? $fields['product_id_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_id_4', $row['product_id_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Qty 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Qty 4', (isset($fields['product_qty_4']['language'])? $fields['product_qty_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_qty_4', $row['product_qty_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Cogs 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Cogs 4', (isset($fields['product_cogs_4']['language'])? $fields['product_cogs_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_cogs_4', $row['product_cogs_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Throw 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Throw 4', (isset($fields['product_throw_4']['language'])? $fields['product_throw_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_throw_4', $row['product_throw_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Id 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Id 5', (isset($fields['product_id_5']['language'])? $fields['product_id_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_id_5', $row['product_id_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Qty 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Qty 5', (isset($fields['product_qty_5']['language'])? $fields['product_qty_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_qty_5', $row['product_qty_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Cogs 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Cogs 5', (isset($fields['product_cogs_5']['language'])? $fields['product_cogs_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_cogs_5', $row['product_cogs_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Throw 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Throw 5', (isset($fields['product_throw_5']['language'])? $fields['product_throw_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_throw_5', $row['product_throw_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Game Earnings" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Earnings', (isset($fields['game_earnings']['language'])? $fields['game_earnings']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_earnings', $row['game_earnings'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Game Throw" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Throw', (isset($fields['game_throw']['language'])? $fields['game_throw']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_throw', $row['game_throw'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Notes" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='notes' rows='5' id='notes' class='form-control '
				           >{{ $row['notes'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Reasons" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Reasons', (isset($fields['reasons']['language'])? $fields['reasons']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='reasons' rows='5' id='reasons' class='form-control '
				           >{{ $row['reasons'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Meter" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Meter', (isset($fields['meter']['language'])? $fields['meter']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='meter' rows='5' id='meter' class='form-control '
				           >{{ $row['meter'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Flag" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Flag', (isset($fields['flag']['language'])? $fields['flag']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('flag', $row['flag'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
	var form = $('#throwreportpayoutFormAjax'); 
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