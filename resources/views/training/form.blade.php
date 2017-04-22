
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">
			<h4>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
		</div>

		<div class="sbox-content">
			@endif
			{!! Form::open(array('url'=>'training/save/'.$row['id'], 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'trainingFormAjax')) !!}
			<div class="col-md-12">
				<fieldset><legend> Training Material</legend>
					<div class="form-group  " >
						<label for="Video Path" class=" control-label col-md-3 text-left">
							{!! SiteHelpers::activeLang('Video Title', (isset($fields['video_title']['language'])? $fields['video_title']['language'] : array())) !!}
						</label>
						<div class="col-md-6">
							{!! Form::text('video_title', $row['video_title'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
						</div>
						<div class="col-md-2">

						</div>
					</div>
					<div class="form-group  " >
						<label for="users" class=" control-label col-md-3 text-left">
							{!! SiteHelpers::activeLang('Users', (isset($fields['users']['language'])? $fields['users']['language'] : array())) !!}
						</label>
						<div class="col-md-6">
							{!! Form::text('users', $row['users'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
						</div>
						<div class="col-md-2">

						</div>
					</div>
					<div class="form-group  " >
						<label for="Video Path" class=" control-label col-md-3 text-left">
							{!! SiteHelpers::activeLang('Video Path', (isset($fields['video_path']['language'])? $fields['video_path']['language'] : array())) !!}
						</label>
						<div class="col-md-6">
							{!! Form::text('video_path', $row['video_path'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
						</div>
						<div class="col-md-2">

						</div>
					</div> </fieldset>
			</div>




			<div style="clear:both"></div>

			<div class="form-group">
				<label class="col-sm-5 text-right">&nbsp;</label>
				<div class="col-sm-7">
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
				radioClass: 'iradio_square-blue',
			});
			$('.removeCurrentFiles').on('click',function(){
				var removeUrl = $(this).attr('href');
				$.get(removeUrl,function(response){});
				$(this).parent('div').empty();
				return false;
			});
			var form = $('#trainingFormAjax');
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
