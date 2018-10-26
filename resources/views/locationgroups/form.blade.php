
<script type="text/javascript" src="{{ asset('sximo/js/plugins/select2/select2.min.js') }}"></script>
<link href="{{ asset('sximo/js/plugins/select2/select2.css')}}" rel="stylesheet">
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
		</div>

		<div class="sbox-content">
			@endif
			{!! Form::open(array('url'=>'locationgroups/save/'.$row['id'], 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'locationgroupsFormAjax')) !!}
			<div class="col-md-12">
				<fieldset><legend> Location Groups</legend>

					<div class="form-group  " >
						<label for="Name" class=" control-label col-md-4 text-left">
							{!! SiteHelpers::activeLang('Name', (isset($fields['name']['language'])? $fields['name']['language'] : array())) !!}
						</label>
						<div class="col-md-6">
							{!! Form::text('name', $row['name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
						</div>
						<div class="col-md-2">

						</div>
					</div>

					<div class="form-group  " >
						<label for="Name" class=" control-label col-md-4 text-left">
							{!! SiteHelpers::activeLang('Location', (isset($fields['name']['language'])? $fields['name']['language'] : array())) !!}
						</label>
						<div class="col-md-6">
							{!! Form::select('location_ids[]', $locations, isset($savedLocations) ? $savedLocations : null, array('class'=>'select2', 'id'=>'location_ids' ,'multiple'=>"multiple" )) !!}
						</div>
						<div class="col-md-2">

						</div>
					</div>

                    <div class="form-group  " >
                        <label for="Name" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Excluded Product Types', (isset($fields['name']['language'])? $fields['name']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::select('excluded_product_type_ids[]', $productTypes, isset($alreadyExcludedProductTypes) ? $alreadyExcludedProductTypes : null, array('class'=>'select2', 'id'=>'already_excluded_product_type_ids' ,'multiple'=>"multiple" )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    <div class="form-group  " >
                        <label for="Name" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Excluded Products', (isset($fields['name']['language'])? $fields['name']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::select('excluded_product_ids[]', $products, isset($alreadyExcludedProducts) ? $alreadyExcludedProducts : null, array('class'=>'select2', 'id'=>'already_excluded_product_ids' ,'multiple'=>"multiple" )) !!}
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
            // renderDropdown($(".select2, .select3, .select4, .select5"), { width:"100%"});
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
            var form = $('#locationgroupsFormAjax');
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

            updateDropdowns('location_ids[]');
            updateDropdowns('excluded_product_ids[]');
            updateDropdowns('excluded_product_type_ids[]');

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