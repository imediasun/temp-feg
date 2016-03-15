
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'sbinovice/save/'.SiteHelpers::encryptID($row['InvoiceID']), 'class'=>'form-vertical','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'sbinoviceFormAjax')) !!}
			<div class="col-md-6">
						<fieldset><legend> Clients Info</legend>
									
								  <div class="form-group  " >
									<label for="ipt" class=" control-label ">
										{!! SiteHelpers::activeLang('Clients', (isset($fields['UserID']['language'])? $fields['UserID']['language'] : array())) !!}		
									   </label>									
									  <select name='UserID' rows='5' id='UserID' class='select2 '   ></select> 						
								  </div> 					
								 </fieldset>
			</div>
			
			<div class="col-md-6">
						<fieldset><legend> Invoice Detail</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="ipt" class=" control-label ">
										{!! SiteHelpers::activeLang('InvoiceID', (isset($fields['InvoiceID']['language'])? $fields['InvoiceID']['language'] : array())) !!}		
									   </label>									
									  {!! Form::text('InvoiceID', $row['InvoiceID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 						
								  </div> 					
								  <div class="form-group  " >
									<label for="ipt" class=" control-label ">
										{!! SiteHelpers::activeLang('Number', (isset($fields['Number']['language'])? $fields['Number']['language'] : array())) !!}		
									   </label>									
									  {!! Form::text('Number', $row['Number'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 						
								  </div> 					
								  <div class="form-group  " >
									<label for="ipt" class=" control-label ">
										{!! SiteHelpers::activeLang('Invoice Date', (isset($fields['DateIssued']['language'])? $fields['DateIssued']['language'] : array())) !!}		
									   </label>									
									  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('DateIssued', $row['DateIssued'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 						
								  </div> 					
								  <div class="form-group  " >
									<label for="ipt" class=" control-label ">
										{!! SiteHelpers::activeLang('Due Date', (isset($fields['DueDate']['language'])? $fields['DueDate']['language'] : array())) !!}		
									   </label>									
									  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('DueDate', $row['DueDate'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 						
								  </div> </fieldset>
			</div>
			
												
				

	@if($subgrid['access']['is_add'] == '1')				
	<hr /><div class="clr clear"></div>	
	
	<h5> Item Details </h5>
	
	<div class="table-responsive">
	    <table class="table table-striped ">
	        <thead>
				<tr class="invHeading">
					@foreach ($subgrid['tableGrid'] as $t)
						@if($t['view'] =='1' && $t['field'] !='InvoiceID')
							<th>
							{{ SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) }}
							</th>
						@endif
					@endforeach
					<th></th>	
				  </tr>

	        </thead>

        <tbody>
        @if(count($subgrid['rowData'])>=1)
            @foreach ($subgrid['rowData'] as $rows)
	            <tr class="clone clonedInput">									
					 @foreach ($subgrid['tableGrid'] as $field)
						 @if($field['view'] =='1' && $field['field'] !='InvoiceID')

	
							 <td>					 
							 	{!! SiteHelpers::bulkForm($field['field'] , $subgrid['tableForm'] , $rows->$field['field']) !!}							 
							 </td>

						 
						 @endif					 
					 
					 @endforeach
					 <td>
					 	<a onclick=" $(this).parents('.clonedInput').remove(); calculateSum(); return false" href="#" class="remove btn btn-xs btn-danger">-</a>
					 	<input type="hidden" name="counter[]">
					 </td>
				</tr>  
			@endforeach
			

		@else

            <tr class="clone clonedInput">								
			 @foreach ($subgrid['tableGrid'] as $field)

				 @if($field['view'] =='1' && $field['field'] !='InvoiceID')
				 <td>					 
				 	{!! SiteHelpers::bulkForm($field['field'] , $subgrid['tableForm'] ) !!}							 
				 </td>
				 @endif					 
			 
			 @endforeach
				 <td>
				 	<a onclick=" $(this).parents('.clonedInput').remove(); calculateSum(); return false" href="#" class="remove btn btn-xs btn-danger">-</a>
				 	<input type="hidden" name="counter[]">
				 </td>
			
			</tr> 	
		@endif	
			<tr>
				<td colspan="3" class="text-right"> Subtotal</td>
				<td ><input type="text" value="" class="form-control input-sm" name="Subtotal"></td>
				<td ></td>
			
			</tr>
        </tbody>	

     	</table>  
    	<input type="hidden" name="enable-masterdetail" value="true">
    </div><br /><br />
     
     <a href="javascript:void(0);" class="addC btn btn-xs btn-info" rel=".clone"><i class="fa fa-plus"></i> New Item</a>
     <hr />		
	@endif
     					
						
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

function calculateSum()
{
	var Subtotal = 0;
	$('table tr.clone ').each(function(i){
		Qty = $(this).find(" input[name*='bulk_Qty']").val();
		Price = $(this).find("input[name*='bulk_Price']").val();
		sum = Qty * Price ;
		Subtotal += sum;
	   $(this).find("input[name*='bulk_Total']").val(sum);
	})
	$('input[name=Subtotal]').val(Subtotal);	
}

$(document).ready(function() { 
	
	$("#UserID").jCombo("{{ URL::to('sbinovice/comboselect?filter=users:id:first_name|last_name') }}",
	{  selected_value : '{{ $row["UserID"] }}' });

	$("input[name*='bulk_Total'] ").attr('readonly','1');
	$("input[name*='bulk_Qty'] , input[name*='bulk_Price'] ").addClass('calculate');

	calculateSum();
	$(".calculate").keyup(function(){ calculateSum();})
	$('.remove').click(function(){ calculateSum()})


         
	$('.addC').relCopy({});
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
	var form = $('#sbinoviceFormAjax'); 
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
<style type="text/css">
	tr.invHeading  th{ background: #d9d9d9 !important; pading-top:10px !important; padding-bottom: 10px !important;}
</style>	 