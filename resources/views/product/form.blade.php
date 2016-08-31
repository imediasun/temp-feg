
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">
			<h4>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content">
@endif
			{!! Form::open(array('url'=>'product/save/'.$row['id'], 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'productFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> FEG Store Products</legend>


				  <div class="form-group  " >
					<label for="Item Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Item Name', (isset($fields['vendor_description']['language'])? $fields['vendor_description']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('vendor_description', $row['vendor_description'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'required'  )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="Item Description" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Item Description', (isset($fields['item_description']['language'])? $fields['item_description']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='item_description' rows='5' id='item_description' class='form-control '
				         >{{ $row['item_description'] }}</textarea>
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
					<label for="Addl Details" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Add\'l Details', (isset($fields['details']['language'])? $fields['details']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='details' rows='5' id='details' class='form-control '>{{ $row['details'] }}</textarea>
					 </div>

				  </div>


							<div class="form-group  " >
								<label for="Quantity Per Case" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Quantity Per Case', (isset($fields['num_items']['language'])? $fields['num_items']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('num_items', $row['num_items'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>






				  <div class="form-group  " >
					<label for="Ticket Value" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])? $fields['ticket_value']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
						<div class="input-group">
							<span class="input-group-addon">$</span>
							{!! Form::text('ticket_value', number_format((double)$row['ticket_value'],2),array('class'=>'form-control', 'placeholder'=>'','required'=>'required','type'=>'number','min' => '0','step'=>'1' )) !!}
						</div>
					</div>
					 <div class="col-md-2">

					 </div>
				  </div>
							<div class="form-group  " >
								<label for="Prod Type Id" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Prod Type Id', (isset($fields['prod_type_id']['language'])? $fields['prod_type_id']['language'] : array())) !!}
								</label>
								<div class="col-md-6">

									<select name='prod_type_id' rows='5' id='prod_type_id' class='select2 ' required='required'  ></select>
								</div>
								<div class="col-md-2">

								</div>
							</div>


							<div class="form-group  " >
								<label for="Prod Sub Type Id" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Prod Sub Type Id', (isset($fields['prod_sub_type_id']['language'])? $fields['prod_sub_type_id']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									<select name='prod_sub_type_id' rows='5' id='prod_sub_type_id' class='select2 '   ></select>
								</div>
								<div class="col-md-2">

								</div>
							</div>
				  <div class="form-group  " >
					<label for="Is Reserved" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Is Reserved', (isset($fields['is_reserved']['language'])? $fields['is_reserved']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $is_reserved = explode(",",$row['is_reserved']); ?>
					 <label class='checked checkbox-inline'>
					<input type="hidden" name="is_reserved" value="0"/>
                         <input type='checkbox' name='is_reserved' value ='1'   class=''
					@if(in_array('1',$is_reserved))checked @endif
					 />  </label>
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="Reserved Qty" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Reserved Qty', (isset($fields['reserved_qty']['language'])? $fields['reserved_qty']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('reserved_qty', $row['reserved_qty'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="Img" class=" control-label col-md-4 text-left">
						{!! SiteHelpers::activeLang('Img', (isset($fields['img']['language'])? $fields['img']['language'] : array())) !!}
					</label>
					<div class="col-md-6">

						<!--<a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles('img')"><i class="fa fa-plus"></i></a>-->

						<div class="imgUpl">
							<input  type='file' name='img'  />
						</div>

						<div class="col-md-2" style="padding-top: 3px;">
							<?php
							echo SiteHelpers::showUploadedFile($row['img'],'/uploads/products/', 30,false)
							?>
						</div>
					</div>
					</div> </fieldset>









				  <div class="form-group  " >
					<label for="Inactive" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Inactive', (isset($fields['inactive']['language'])? $fields['inactive']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $inactive = explode(",",$row['inactive']); ?>
					 <label class='checked checkbox-inline'>
                         <input type="hidden" name="inactive" value="0"/>
					<input type='checkbox' name='inactive' value ='1'   class=''
					@if(in_array('1',$inactive))checked @endif
					 />  </label>
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
                            <div class="form-group  " >
                                <label for="In Development" class=" control-label col-md-4 text-left">
                                    {!! SiteHelpers::activeLang('In Development', (isset($fields['in_development']['language'])? $fields['in_development']['language'] : array())) !!}
                                </label>
                                <div class="col-md-6">
                                    <?php $indevelopment = explode(",",$row['in_development']); ?>
                                    <label class='checked checkbox-inline'>
                                        <input type="hidden" name="in_development" value="0"/>
                                        <input type='checkbox' name='in_development' value ='1'   class=''
                                               @if(in_array('1',$indevelopment))checked @endif
                                                />  </label>
                                </div>
                                <div class="col-md-2">

                                </div>
                            </div>
				  <div class="form-group  " >
					<label for="Hot Item" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Hot Item', (isset($fields['hot_item']['language'])? $fields['hot_item']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $hot_item = explode(",",$row['hot_item']); ?>
					 <label class='checked checkbox-inline'>
					<input type="hidden" name="hot_item" value="0"/>
                         <input type='checkbox' name='hot_item' value ='1'   class=''
					@if(in_array('1',$hot_item))checked @endif
					 />  </label>
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




<script type="text/javascript">
$(document).ready(function() {

        $("#vendor_id").jCombo("{{ URL::to('product/comboselect?filter=vendor:id:vendor_name') }}",
        {  selected_value : '{{ $row["vendor_id"] }}' });

	$("#prod_type_id").jCombo("{{ URL::to('product/comboselect?filter=order_type:id:order_type:can_request:1') }}",
			{  selected_value : '{{ $row["prod_type_id"] }}' });

	$("#prod_sub_type_id").jCombo("{{ URL::to('product/comboselect?filter=product_type:id:product_type') }}&parent=request_type_id:",
			{  parent: '#prod_type_id' ,selected_value : '{{ $row["prod_sub_type_id"] }}' });


	$('.editor').summernote();
	$('.previewImage').fancybox();
	$('.tips').tooltip();
	$(".select2").select2({ width:"98%"});
	$('.date').datepicker({format:'mm/dd/yyyy',autoClose:true})
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