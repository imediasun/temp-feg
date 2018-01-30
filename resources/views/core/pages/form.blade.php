@extends('layouts.app')

@section('content')

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>


		  <ul class="breadcrumb">
			<li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
			<li><a href="{{ URL::to('core/pages') }}">{{ $pageTitle }}</a></li>
			<li class="active"> Add </li>
		  </ul>


    </div>

<div class="page-content-wrapper">
	@if(Session::has('message'))
		   {{ Session::get('message') }}
	@endif

		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
		 {!! Form::open(array('url'=>'core/pages/save/'.$row['pageID'], 'class'=>'form-vertical row ','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id' => 'pageCMS')) !!}

			<div class="col-sm-8">
				<div class="sbox containerBox" style="overflow: hidden;">
					<div class="sbox-title">
						@if($id)
							<i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Page
						@else
							<i class="fa fa-plus"></i>&nbsp;&nbsp;Create New Page
						@endif</div>
					<div class="sbox-content">

						<ul class="nav nav-tabs" >
						  <li class="active"><a href="#info" data-toggle="tab"> Page Content </a></li>
						  <li ><a href="#meta" data-toggle="tab"> Meta & Description </a></li>
						</ul>


						<div class="tab-content">
						  <div class="tab-pane active m-t" id="info">
							  <div class="form-group  " >

								<div class="" style="background:#fff;">
								  <textarea name='content' rows='35' id='content'    class='form-control markItUp editor note-editor note-editable'
									 >{{ htmlentities($content) }}</textarea>
								 </div>
							  </div>

						  </div>

						  <div class="tab-pane m-t" id="meta">

					  		<div class="form-group  " >
								<label class=""> Metakey </label>
								<div class="" style="background:#fff;">
								  <textarea name='metakey' rows='5' id='metakey' class='form-control markItUp'>{{ $row['metakey'] }}</textarea>
								 </div>
							  </div>

				  			<div class="form-group  " >
								<label class=""> Meta Description </label>
								<div class="" style="background:#fff;">
								  <textarea name='metadesc' rows='10' id='metadesc' class='form-control markItUp'>{{ $row['metadesc'] }}</textarea>
								 </div>
							  </div>

						  </div>

						</div>


					 </div>
				</div>
		 	</div>

		 <div class="col-sm-4" id="cms_bar_id">

			<div class="sbox">

				<div class="sbox-title">Page Info </div>
				<div class="sbox-content">
				  <div class="form-group hidethis " style="display:none;">
					<label for="ipt" class=""> PageID </label>

					  {!! Form::text('pageID', $row['pageID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}

				  </div>
				  <div class="form-group  " >
					<label for="ipt" > Title </label>

					  {!! Form::text('title', $row['title'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!}

				  </div>
				  <div class="form-group  " >
					<label for="ipt" > Alias </label>

					  {!! Form::text('alias', $row['alias'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'   )) !!}

				  </div>
				  <div class="form-group  " >
					<label for="ipt" > Filename </label>

					  <input name="filename" type="text" class="form-control" value="{{ $row['filename']}}"
					  @if($row['pageID'] !='') readonly="1" @endif required
					  />

				  </div>
				  <div class="form-group  " >
				  <label for="ipt"> Who can view this page ? </label>
					@foreach($groups as $group)
					<label class="checkbox" id="group_id{{$group['id']}}">
					  <input  type='checkbox' name='group_id[{{ $group['id'] }}]'    value="{{ $group['id'] }}"
					  @if($group['access'] ==1 or $group['id'] ==1)
					  	checked
					  @endif
					   />
					  {{ $group['name'] }}
					</label>
					@endforeach

				  </div>
				  <div class="form-group  " >
					<label> Show for Guest ? unlogged  </label>
					<label class="checkbox"><input  type='checkbox' name='allow_guest'
 						@if($row['allow_guest'] ==1 ) checked  @endif
					   value="1"	/> Allow Guest ?  </lable>
				  </div>


				  <div class="form-group hidethis " style="display:none;">
					<label for="ipt" class=" control-label col-md-4 text-right"> Created </label>
					<div class="col-md-8">
					  {!! Form::text('created', $row['created'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
				  </div>
				  <div class="form-group hidethis " style="display:none;">
					<label for="ipt" > Updated </label>

					  {!! Form::text('updated', $row['updated'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}

				  </div>

				  <div class="form-group  " >
					<label> Status </label>
					<label class="radio">
					  <input  type='radio' name='status'  value="enable" required
					  @if( $row['status'] =='enable')  	checked	  @endif
					   />
					  Enable
					</label>
					<label class="radio">
					  <input  type='radio' name='status'  value="disabled" required
					   @if( $row['status'] =='disabled')  	checked	  @endif
					   />
					  Disabled
					</label>
				  </div>

				  <div class="form-group  " >
					<label> Template </label>
					<label class="radio">
					  <input  type='radio' name='template'  value="frontend" required
					  @if( $row['template'] =='frontend')  	checked	  @endif
					   />
					  Frontend
					</label>
					<label class="radio">
					  <input  type='radio' name='template'  value="backend" required
					   @if( $row['template'] =='backend')  	checked	  @endif
					   />
					  Backend
					</label>
				  </div>


				  <div class="form-group  userShowEditLinkInPagePermission" >
					<label>Show edit link in page view for: </label>
                    <p>
                        <label>Group: </label>
                        <select name='direct_edit_groups[]' multiple id="iGroups" class='select2 '></select>
                    </p>
                    <p>
					<label>User: </label>
                     <select name='direct_edit_users[]' multiple id="iUsers" class='select2 '></select>
                    </p>
                    <p>
					<label>Exclude User: </label>
                     <select name='direct_edit_users_exclude[]' multiple id="eUsers" class='select2 '></select>
                    </p>
				  </div>



			  <div class="form-group">
                <input type="hidden" name="return" value="{!! $return !!}" />
				<button type="submit" class="btn btn-primary ">  Submit </button>
			  </div>
			  </div>
			  </div>

			</div>

		 {!! Form::close() !!}
	</div>
</div>

  {{--Upload PDF --}}

  <div class="note-link-dialog modal" aria-hidden="false" id="pdf_modal">
	  <div class="modal-dialog">
		  <div class="modal-content">
			  <div class="modal-header">
				  <button type="button" class="close" aria-hidden="true" tabindex="-1" onclick="cancelFileUpload();">×</button>
				  <h4>Upload PDF</h4>
			  </div>
			  <div class="modal-body">
				  <div class="row-fluid">
					  <form method="post" enctype="multipart/form-data" name="pdf_form">
					  <div class="form-group">
						  <label>Browse File</label>
						  <input type="file" name="upload_file" id="pdf_file" accept="application/pdf, application/msword" />
						  <label style="color:red;font-size:14px;margin-top:5px;" id="pdf_error"></label>
					  </div>
					  </form>
					  <div class="progress upload_progress_container">
						  <div class="progress-bar" id="upload_file_progress_bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
							  <span id="progress_complete">0</span>%
						  </div>
					  </div>
				  </div>
			  </div>
			  <div class="modal-footer">
				  <button href="#" onclick="upload_pdf();" class="btn btn-primary" id="pdf_upload">Insert</button>
			  </div>
		  </div>
	  </div>
  </div>
<style>
	.page-content-wrapper.m-t,.sbox-content {
		float: left;
		width: 100%;
		height: 100%;
	}
	.clearfix
	{
		width: 0;
		height:0;
		display: none;
	}
	.page-content-wrapper.m-t
	{
		margin-top: 0;
	}
	.note-editable > *:not(.page-content-wrapper),.page-content-wrapper.m-t > *:not(.sbox),.sbox.animated.fadeInRight > *:not(.sbox-content) {
		display: none;
	}
</style>
  <script>
	  var file_upload_request;
	  function upload_pdf() {
		  $(window).bind('beforeunload', function(){
			  return 'You are not finished uploading your file, do you wish to continue?';
		  });

		  $('#pdf_upload').text('Uploading...');
		  $('#pdf_upload').prop('disabled', true);
		  $('.upload_progress_container').toggle();
		  var fd = new FormData(document.forms.namedItem("pdf_form"));
		  //fd.append("CustomField", "This is some extra data");
		  file_upload_request = $.ajax({
			  url: "{{url()}}/pages/upload",
			  type: "POST",
			  data: fd,
			  processData: false,  // tell jQuery not to process the data
			  contentType: false,   // tell jQuery not to set contentType
			  xhr: function() {
				  var xhr = new window.XMLHttpRequest();
				  xhr.upload.addEventListener("progress", function(evt) {
					  if (evt.lengthComputable) {
						  var percentComplete = evt.loaded / evt.total;
						  percentComplete = parseInt(percentComplete * 100);
						  $('#upload_file_progress_bar').attr('aria-valuenow', percentComplete);
						  $('#upload_file_progress_bar').css('width', percentComplete+'%');
						  $('#progress_complete').html(percentComplete);

						  if (percentComplete === 100) {
							  console.log("File Uploaded!");
							  $('.upload_progress_container').toggle();
							  $('#upload_file_progress_bar').attr('aria-valuenow', '0');
							  $('#upload_file_progress_bar').css('width', '0%');
						  }

					  }
				  }, false);

				  return xhr;
			  },
			  success: function (data) {
				  console.log(data);
				  $('#pdf_modal').modal('toggle');
				  $('.icon-link').trigger('click');
				  $('.note-link-url').val("{{url('')}}/upload/pageCmsPDF/"+data);
				  $('.note-link-btn').trigger('click');
				  $('#pdf_file').val('');
				  $('#pdf_upload').text('Insert');
				  $('#pdf_upload').prop('disabled', false);
				  $(window).unbind('beforeunload');
			  },
			  error: function (xhr) {
					if(xhr.status=='422'){
						var error= JSON.parse(xhr.responseText);
						console.log("error is "+error.upload_file);
						$('#pdf_error').text(error.upload_file);
					}else{
						$('#pdf_error').text('Something went wrong. Try again.');
					}
				  $('#pdf_file').val('');
				  $('#pdf_upload').text('Insert');
				  $('#pdf_upload').prop('disabled', false);
				  $(window).unbind('beforeunload');
			  }
		  });
	  }

	  function cancelFileUpload(){
		  if($('#pdf_upload').text() == 'Uploading...'){
			  var choice = confirm("You are not finished uploading your file, do you wish to continue?");
			  if(choice){
				  file_upload_request.abort();
				  $('#pdf_error').hide();
				  $('#pdf_modal').modal('toggle');
			  }
		  }else{
			  $('#pdf_modal').modal('toggle');
		  }
	  }


	  $( document ).ready(function() {
		  var model_obj = "{backdrop:'static',keyboard:false}";
		  $('.note-toolbar').append('<div class="note-attach btn-group"><button type="button" class="btn btn-default btn-sm btn-small" data-toggle="tooltip" title="Attach File" data-placement="bottom" tabindex="-1" onclick=$("#pdf_modal").modal('+model_obj+');$(".upload_progress_container").hide();><i class="fa fa-file-o"></i></button></div>');
		  $('[data-toggle="tooltip"]').tooltip();

		  $('.note-editor .note-editable').css('height', $('#cms_bar_id').height()-208);
		  //$('.upload_progress_container').toggle();
	  });

  </script>


  <!--<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>-->
<!--  <script>tinymce.init({
          selector: '#content4',
          plugins: ["advlist autolink lists link image charmap print preview anchor", "searchreplace" +
          " visualblocks code fullscreen", "insertdatetime media table contextmenu paste",
              "advlist","textcolor colorpicker","imagetools"],
          toolbar: "insertfile undo redo | forecolor backcolor | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image", theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect"
           });
  </script>-->


    <script>
		superAdmin = {{\App\Models\Core\Groups::SUPPER_ADMIN}};
		$(document).on("keyup",".note-editable",function(e){
			var key = e.keyCode || e.charCode;
			if (key == 8 || key == 46) {
				var text = $(".note-editable .page-content-wrapper .sbox-content .col-md-12").text();
				if($.trim(text)=="" || text.length==1) {
					$(".note-editable .page-content-wrapper .sbox-content .col-md-12").text('');
					$(".note-editable .page-content-wrapper .sbox-content .col-md-12").empty();
					var html = '<div class="page-content-wrapper m-t">';
					html += '<div class="sbox animated fadeInRight">';
					html += '<div class="sbox-content">';
					html += '<div class="col-md-12" style="height: auto; min-height:50px; margin-top: -15px; line-height: normal; background-color: #ffffff;">';
					html +='</div><div class="clearfix">&nbsp;</div></div></div></div>';
							$(".note-editable").html("<p><br><p>"+html);
						return false;
					}
				}
		})
        $(document).ready(function(){

        	$('button.btn[data-event="codeview"]').remove();
			var html = '<div class="page-content-wrapper m-t">';
			html += '<div class="sbox animated fadeInRight">';
			html += '<div class="sbox-content">';
			html += '<div class="col-md-12" style="height: auto; min-height:50px; margin-top: -15px; line-height: normal; background-color: #ffffff;">';
			html +='</div><div class="clearfix">&nbsp;</div></div></div></div>';
			setTimeout(function(){
				console.log("Length "+$(".note-editable .page-content-wrapper").length);
				if($(".note-editable .page-content-wrapper").length==0){
						$(".note-editable").html("<p><br><p>"+html);
					console.log("Length "+$(".note-editable .page-content-wrapper").length);
				}
			},700);


            $("#iGroups").jCombo("{{ URL::to('pages/comboselect?filter=tb_groups:group_id:name') }}",
                    {selected_value: "{{ is_object($row)?$row->direct_edit_groups:'' }}"});
            $("#iUsers").jCombo("{{ URL::to('pages/comboselect?filter=users:id:first_name|last_name') }}",
                    {selected_value: "{{ is_object($row)?$row->direct_edit_users:'' }}"});
            $("#eUsers").jCombo("{{ URL::to('pages/comboselect?filter=users:id:first_name|last_name') }}",
                    {selected_value: "{{ is_object($row)?$row->direct_edit_users_exclude:'' }}"});
            @if(Session::get('gid') != \App\Models\Core\Groups::SUPPER_ADMIN)
				$('#group_id'+superAdmin).css('display','none');
				$( document ).ajaxStop(function() {
					console.log( "Triggered ajaxStop handler." );
					/*$('#iGroups option[value="'+superAdmin+'"]').attr('disabled','disabled');*/
					$('#iGroups option[value="'+superAdmin+'"]').remove();
					$('#iGroups').trigger('change');
            	});
			@endif
			/*$('form#pageCMS').submit(function () {
                $('#iGroups option[value="'+superAdmin+'"]').removeAttr('disabled');
                $('#iGroups').trigger('change');
            })*/
        });
        var row = <?php echo json_encode($row) ; ?>;
    </script>
@stop
