
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'expensecategories/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'expensecategoriesFormAjax')) !!}
			<div class="col-md-12">
				<fieldset>

					<div class="form-group  ">
						<label for="Prod Type Id" class=" control-label col-md-4 text-left">
							Product Type:
						</label>

						<div class="col-md-6">

							<select name='order_type' rows='5' id='order_type' class='select2 '
									required='required'></select>
						</div>
						<div class="col-md-2">

						</div>
					</div>

					<div class="form-group  ">
						<label for="Prod Sub Type Id" class=" control-label col-md-4 text-left">
							Product Subtype:
						</label>

						<div class="col-md-6">
							<select name='product_type' rows='5' id='product_type' class='select2 '></select>
						</div>
						<div class="col-md-2">

						</div>
					</div>

					<div class="form-group">
						<label for="Expense Category" class=" control-label col-md-4 text-left">
							Expense Category
						</label>
						<div class="col-md-6">
							<input type="hidden" name="mapped_expense_category" value="{{ $row['mapped_expense_category'] }}" >
							<input class="form-control parsley-validated" placeholder="" parsley-type="number" required="true" id="expense_category" name="mapped_expense_category" type="text" value="{{ $row['mapped_expense_category'] }}">
						</div>
						<div class="col-md-2">
						</div>
					</div>

				</fieldset>
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
	renderDropdown($(".select2, .select3, .select4, .select5"), { width:"100%"});
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
	var form = $('#expensecategoriesFormAjax'); 
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



	$("#order_type").jCombo("{{ URL::to('product/comboselect?filter=order_type:id:order_type') }}",
			{selected_value: '{{ $row["order_type"] }}'});

	if('{{ $row["order_type"] }}')
	{
		$(document).ajaxStop(function() {
			console.log( "Triggered ajaxStop handler." );
			$("#product_type").jCombo("{{ URL::to('product/comboselect?filter=product_type:id:type_description') }}&parent=request_type_id:{{ $row["order_type"] }}",
					{selected_value: '{{ $row["product_type"] }}'});
			$(document).unbind('ajaxStop');
		});
	}

	$("#order_type").click(function () {
		$("#product_type").jCombo("{{ URL::to('product/comboselect?filter=product_type:id:type_description') }}&parent=request_type_id:"+$('#order_type').val()+"",
				{selected_value: '{{ $row["product_type"] }}'});
		if($(this).val()) {
			//need to uncomment after discussion
			getExpenseCategory($(this).val());
		}
	});



	function getExpenseCategory(order_type_id,product_type_id) {

		$("#expense_category").val('');
		if(product_type_id === null)
		{
			product_type_id="";
		}
		$.get('product/expense-category',{'order_type':order_type_id,'product_type':product_type_id},function(data){
			if(data.expense_category)
			{
				$("#expense_category").val(data.expense_category);
			}
		},'json');
	}
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