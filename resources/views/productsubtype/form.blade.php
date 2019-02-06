
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'productsubtype/save/', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'productsubtypeFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Product Subtype</legend>

				  <div class="form-group  " >
					<div class="col-md-6">
					  {!! Form::text('id', $row['id'],array('class'=>'form-control hidden', 'placeholder'=>'',   )) !!}
					 </div>
				  </div>
				  <div class="form-group  " >
					<label for="Product Type" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Sub Type', (isset($fields['product_type']['language'])? $fields['product_type']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_type', $row['product_type'],array('class'=>'form-control', 'placeholder'=>'Product Sub Type',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  {{--<div class="form-group  " >--}}
					{{--<label for="Type Description" class=" control-label col-md-4 text-left">--}}
					{{--{!! SiteHelpers::activeLang('Type Description', (isset($fields['type_description']['language'])? $fields['type_description']['language'] : array())) !!}--}}
					{{--</label>--}}
					{{--<div class="col-md-6">--}}
					  {{--{!! Form::text('type_description', $row['type_description'],array('class'=>'form-control', 'placeholder'=>'Type Description',   )) !!}--}}
					 {{--</div> --}}
					 {{--<div class="col-md-2">--}}
					 	{{----}}
					 {{--</div>--}}
				  {{--</div>--}}
				  <div class="form-group  " >
					<label for="Order Type" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Order Type', (isset($fields['request_type_id']['language'])? $fields['request_type_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::select('request_type_id', \Illuminate\Support\Facades\DB::table('order_type')->where('can_request', 1)->orderBy('order_type', 'asc')->lists('order_type','id'), $row['request_type_id'],array('class'=>'select2', ($_SERVER['REQUEST_URI'] != '/productsubtype/update' ? 'disabled': ''), 'placeholder'=>'Select Order Type',   )) !!}
					</div>
					 <div class="col-md-2">
					 	
					 </div>
				  </div> </fieldset>
			</div>
			
												
								
						
			<div style="clear:both"></div>	
							
			<div class="form-group">
				<label class="col-sm-4 text-right">&nbsp;</label>
				<div class="col-sm-8">	
					<button type="button" class="btn btn-primary btn-sm " id="submitForm"><i class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
					<button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm"><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
				</div>			
			</div> 		 
			{!! Form::close() !!}


@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif

<div class="modal" id="reactivateDeletedSubTypeModal" role="dialog">
	<div class="modal-dialog" style="width: 650px">
		<!-- Modal content-->
		<div id="mycontent" class="modal-content">
			<div id="myheader" class="modal-header">
				<button type="button " class="btn-xs collapse-close btn btn-danger pull-right"
						data-dismiss="modal" aria-hidden="true"><i class="fa fa fa-times"></i>
				</button>
				<h4>Re-activate Product Subtype</h4>
			</div>
			<div class="modal-body col-md-12">
				{!! Form::open(array('url'=>'','class'=>'form-horizontal',
				'parsley-validate'=>'','novalidate'=>'', 'id'=>'reactivateProductSubtypeFormAjax')) !!}
					<div class="col-md-12">
						{{csrf_field()}}
						<p style="font-size: 140%">Do you want &nbsp;&nbsp;&nbsp;<b><span id="thisProductSubtype">this Product Sub type</span></b>&nbsp;&nbsp;&nbsp; to be re-activated?</p>
						<div class="form-group" style="margin-top:10px;">
							<button type="button" style="float: right" class=" btn  btn-lg btn-default" onclick="$('#reactivateDeletedSubTypeModal').modal('hide')" title="DONT REACTIVATE PRODUCT SUBTYPE" id="dont_reactivate_product_subtype">
								{{ Lang::get('core.sb_dont_reactivate_product_subtype') }}
							</button>
							&nbsp;
							<button type="button" onclick="reactivateTheDeletedProductSubtype()" name="submit"  style="margin-right: 10px; float: right" class=" btn  btn-lg btn-success" title="REACTIVATE PRODUCT SUBTYPE" id="reactivate_product_subtype">
								{{ Lang::get('core.sb_reactivate_product_subtype') }}
							</button>
						</div>
					</div>
				{!! Form::close() !!}
			</div>
			<div class="clearfix"></div>

		</div>

	</div>
</div>
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
	var form = $('#productsubtypeFormAjax'); 
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

$('#submitForm').on('click', function(){
    var productSubtypeName = $('input[name="product_type"]').val();
    var typeDescription = $('input[name="type_description"]').val();
    var orderType = $('select[name="request_type_id"]').val();

    var inputs = [
        {
            'title':'Product Subtype',
			'value':productSubtypeName
		},
		{
		    'title':'Type Description',
			'value':typeDescription
		},
		{
		    'title':'Order Type',
			'value':orderType
		}
	];

    if(!productSubtypeName || !orderType){
        $.each(inputs, function (key, val) {
            if(!val.value)
			{
			    setTimeout(function () {
                    notyMessage(val.title+' Required', [], 'error', 'Error');
                }, 200*(key+1));
			}
        });
	}
	else{
        var id = $('input[name="id"]').val();
        $.ajax({
            url: "{{ URL::to('productsubtype/productsubtypes-already-deleted') }}",
            type: "POST",
            data: {
                'productsubtype': productSubtypeName,
                'ordertype':orderType,
                'id':id
            },
            beforeSend: function(){
                $('.ajaxLoading').show();
            },
            success: function (data) {
                $('.ajaxLoading').hide();
                if(data.status == 'success'){
                    if(data.count >= 1){
                        populateTheAlreadyDeletedSubTypeModal(data.alreadyDeletedRecord, '#reactivateDeletedSubTypeModal');
                    }else{
                        $('#productsubtypeFormAjax').submit();
                    }
                }else{
                    notyMessage(data.message, [], data.status);
                }
            },
            error: function (exception) {
                $('.ajaxLoading').hide();
                console.log(exception);
            }
        });
	}
});

function populateTheAlreadyDeletedSubTypeModal(alreadyDeletedRecord, modalId){
	$('#thisProductSubtype').html(alreadyDeletedRecord.type_description);
	$('#reactivateProductSubtypeFormAjax').attr('action', '{{ URL::to('productsubtype/reactivate-product-subtype') }}/'+alreadyDeletedRecord.id);
	$(modalId).modal('show');
}
function reactivateTheDeletedProductSubtype(){
    var url = $('#reactivateProductSubtypeFormAjax').attr('action');
    $.ajax({
		url: url,
		type: "POST",
		success: function (data) {
            notyMessage(data.message, [], data.status);
            $('#reactivateDeletedSubTypeModal').modal('hide');
			window.location = "{{\Illuminate\Support\Facades\URL::to('productsubtype')}}";
        },
		error: function (exception) {
            $('.ajaxLoading').hide();
            $('#reactivateDeletedSubTypeModal').modal('hide');
            console.log(exception);
        }
	})
}
</script>		 