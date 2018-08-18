@extends('layouts.app')

@section("beforeheadend")
	<!-- include summernote css/js -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
	<link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
	<link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
	<script src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
	<style>
		.note-popover .popover-content .dropdown-menu, .card-header.note-toolbar .dropdown-menu {
			min-width: 140px;
		}
		.note-popover .popover-content .dropdown-menu.note-check a i, .card-header.note-toolbar .dropdown-menu.note-check a{
			color:black;
			display: block;
		}
		.note-popover .popover-content .dropdown-menu.note-check a i, .card-header.note-toolbar .dropdown-menu.note-check a i {
			color: black;
		}
		.note-toolbar .btn-sm, .note-toolbar .btn-group-sm > .btn {
			font-size: 14px !important;
		}

	</style>
	<script>
		$(function(){
			$(".note-recent-color").css({"background-color":"#dddddd"});
			$('.note-btn.btn.btn-light.btn-sm.dropdown-toggle[data-original-title="More Color"]').html('<i class="fa fa-arrow-down"></i>');
			var button = '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
			$(".modal button.close").remove();
			$(".modal .modal-header").prepend(button);
		});
	</script>
	@endsection
@section('content')
	<style>
		.resizing{
			cursor: col-resize;
		}
		.note-editor table.table th,.note-editor table.table td{
			word-break: break-all;
			width:10%;
		}
	</style>
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
									<textarea class="pageContentEditor" name="content">{!! html_entity_decode($content) !!}</textarea>
									<script>
										$(".pageContentEditor").summernote();
									</script>
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

  {{--Upload File --}}

  <div class="note-link-dialog modal" aria-hidden="false" id="pdf_modal">
	  <div class="modal-dialog">
		  <div class="modal-content">
			  <div class="modal-header">
				  <button type="button" class="close" aria-hidden="true" tabindex="-1" onclick="cancelFileUpload();">×</button>
				  <h4>Attach File</h4>
			  </div>
			  <div class="modal-body">
				  <div class="row-fluid">
					  <form method="post" enctype="multipart/form-data" name="pdf_form">
					  <div class="form-group">
						  <label>Browse File</label>
						  <input type="file" class="form-control" required name="upload_file" id="pdf_file"  />
						  <label style="color:red;font-size:14px;margin-top:5px;" id="pdf_error"></label>
					  </div>
						  <div class="form-group">
							  <input type="radio"  name="file_behaviour" value="download" id="file_behaviour1" checked  /> <label for="file_behaviour1">Force Download</label>
							  &nbsp;&nbsp;<input type="radio"  name="file_behaviour" value="open" id="file_behaviour2"   /> <label for="file_behaviour2">Open in New Tab</label>
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
				  <button href="#" onclick="uploadFile();" class="btn btn-primary" id="pdf_upload">Insert</button>
			  </div>
		  </div>
	  </div>
  </div>
  <script>
	  var file_upload_request;
	  function uploadFile() {
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
				  $('.note-insert .note-btn.btn.btn-light.btn-sm[data-original-title="Link (CTRL+K)"]').trigger('click');
				  //note-link-text
				  if(data.file_behaviour == 'open'){
					  $('.note-link-url').val(data.FileOpenUrl);
				  }
				  if(data.file_behaviour == "download"){
					  $('.note-link-url').val(data.FileDownloadUrl);
				  }
				  $(".note-link-text").val(data.filename);
				 // $('.note-insert .note-btn.btn.btn-light.btn-sm[data-original-title="Link (CTRL+K)"]').trigger('click');
				  $(".note-link-btn").trigger("click");
                  if(data.file_behaviour == 'open'){
				  	$("a[href='"+data.FileOpenUrl+"']").attr("target","_blank");
				  }
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

		superAdmin = {{\App\Models\Core\Groups::SUPPER_ADMIN}};
        $(document).ready(function(){
			var model_obj = "{backdrop:'static',keyboard:false}";
			$('.note-toolbar').append('<div class="note-attach btn-group"><button type="button" class="btn btn-sm btn-small" data-toggle="tooltip" title="Attach File" data-placement="bottom" tabindex="-1" onclick=$("#pdf_modal").modal('+model_obj+');$(".upload_progress_container").hide();>&nbsp;&nbsp;<i class="fa fa-paperclip"></i>&nbsp;&nbsp;</button></div>');
			$('[data-toggle="tooltip"]').tooltip();

			$('.note-editor .note-editable').css('height', $('#cms_bar_id').height()-208);
			//$('.upload_progress_container').toggle();
        	$(document).on("click",".note-toolbar.btn-toolbar div button.btn",function(){
				$('.note-editor .note-editable').css('height', $('#cms_bar_id').height()-208);
			});
			$(document).on("click","button.btn-codeview",function(){
				$('.note-editor .note-editable').css('height', $(document).height()-324);
			});




            $("#iGroups").jCombo("{{ URL::to('pages/comboselect?filter=tb_groups:group_id:name') }}",
                    {selected_value: "{{ is_object($row)?$row->direct_edit_groups:'' }}"});
            $("#iUsers").jCombo("{{ URL::to('pages/comboselect?filter=users:id:first_name|last_name') }}",
                    {selected_value: "{{ is_object($row)?$row->direct_edit_users:'' }}"});
            $("#eUsers").jCombo("{{ URL::to('pages/comboselect?filter=users:id:first_name|last_name') }}",
                    {selected_value: "{{ is_object($row)?$row->direct_edit_users_exclude:'' }}"});
            @if(Session::get('gid') != \App\Models\Core\Groups::SUPPER_ADMIN)
				$('#group_id'+superAdmin).css('display','none');
				$( document ).ajaxStop(function() {
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

	/*  $(function(){
		  $(document).on("mouseup",".note-editor p a",function(){
			  var element = $(this).offset();

			// $(".note-link-popover").css("top",(Number($(this).position().top)+20)+"px");

		  });


		  var pressed = false;
		  var start = undefined;
		  var startX, startWidth;

		  $(document).on("mousedown",".note-editor table.table th,.note-editor table.table td",function(e) {
			  start = $(this);
			  pressed = true;
			  startX = e.pageX;
			  startWidth = $(this).width();
			  $(start).addClass("resizing");
		  });

		  $(document).mousemove(function(e) {
			  if(pressed) {
				  $(start).width(startWidth+(e.pageX-startX));
			  }
		  });

		  $(document).mouseup(function() {
			  if(pressed) {
				  $(start).removeClass("resizing");
				  pressed = false;
			  }
		  });

	  });*/
	  (function($) {
		  var origAppend = $.fn.append;

		  $.fn.append = function () {
			  return origAppend.apply(this, arguments).trigger("append");
		  };
	  })(jQuery);

    </script>
@stop
