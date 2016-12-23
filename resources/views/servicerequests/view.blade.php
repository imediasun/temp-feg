<?php
$commentsCount =  $comments->count();
?>
@if($setting['view-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')">
					<i class="fa fa fa-times"></i></a>
			</h4>
			</h4>
		</div>

		<div class="sbox-content">
			@endif
			<div class="container-fuild">
				<div class="row m-b-lg ">
					<div class="col-lg-5 animated fadeInLeft delayp1" style="margin-left: auto">
						<h3>Ticket Info</h3>
						<table class="table table-striped table-bordered" >
							<tbody>

							<tr>
								<td width='40%' class='label-view text-right'>
									{{ SiteHelpers::activeLang('Date Open', (isset($fields['Created']['language'])? $fields['Created']['language'] : array())) }}
								</td>
								<td><?php

									$date=date("m/d/Y", strtotime($row->Created));
									echo $date;
									?>

								</td>

							</tr>
							<tr>
								<td width='40%' class='label-view text-right'>
									{{ SiteHelpers::activeLang('Needed Date', (isset($fields['need_by_date']['language'])? $fields['need_by_date']['language'] : array())) }}
								</td>
								<td><?php echo $row->need_by_date;  ?></td>

							</tr>
							<tr>
								<td width='40%' class='label-view text-right'>
									{{ SiteHelpers::activeLang('Last Event date', (isset($fields['updated']['language'])? $fields['updated']['language'] : array())) }}
								</td>
								<td>
									<?php
									if($commentsCount!=0){
										foreach($comments as $comment)
										{
											$date=date("m/d/Y", strtotime($comment->Posted));
											echo $date;
											break;
										}
									}
									?>
								</td>

							</tr>

							<tr>
								<td width='40%' class='label-view text-right'>
									{{ SiteHelpers::activeLang('Title', (isset($fields['Subject']['language'])? $fields['Subject']['language'] : array())) }}
								</td>
								<td>{{ $row->Subject }} </td>

							</tr>

							<tr>
								<td width='40%' class='label-view text-right'>
									{{ SiteHelpers::activeLang('Location', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}
								</td>
								<td>{!! SiteHelpers::gridDisplayView($row->location_id,'location_id','1:location:id:location_name') !!} </td>

							</tr>

							<tr>
								<td width='40%' class='label-view text-right'>
									{{ SiteHelpers::activeLang('Game Name', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) }}
								</td>
								<td>{!! SiteHelpers::gridDisplayView($row->game_id,'game_id','1:game:id:game_name') !!} </td>

							</tr>

							<tr>
								<td width='40%' class='label-view text-right'>
									{{ SiteHelpers::activeLang('Assign To', (isset($fields['assign_to']['language'])? $fields['assign_to']['language'] : array())) }}
								</td>
								<td><?php

									foreach ($row->assign_employee_names as $index => $name) :
										echo (++$index) . '.  ' . isset($name[0]->first_name) ? $name[0]->first_name : "" . ' ' . isset($name[0]->last_name)?$name[0]->last_name:"" . '</br>';
									endforeach;


									?> </td>

							</tr>

							<tr>
								<td width='40%' class='label-view text-right'>
									{{ SiteHelpers::activeLang('Priority', (isset($fields['Priority']['language'])? $fields['Priority']['language'] : array())) }}
								</td>
								<td>{{ $row->Priority }} </td>

							</tr>

							<tr>
								<td width='40%' class='label-view text-right'>
									{{ SiteHelpers::activeLang('Status', (isset($fields['Status']['language'])? $fields['Status']['language'] : array())) }}
								</td>
								<td><?php
									if($row->Status=='inqueue') echo 'Pending';
									else echo $row->Status;
									?></td>

							</tr>
							<tr>
								<td width='40%' class='label-view text-right'>
									{{ SiteHelpers::activeLang('Issue Type', (isset($fields['issue_type']['language'])? $fields['issue_type']['language'] : array())) }}
								</td>
								<td>{{ $row->issue_type }} </td>

							</tr>

							{{--<tr>--}}
								{{--<td width='40%' class='label-view text-right'>--}}
									{{--{{ SiteHelpers::activeLang('Department', (isset($fields['department_id']['language'])? $fields['department_id']['language'] : array())) }}--}}
								{{--</td>--}}
								{{--<td>{!! SiteHelpers::gridDisplayView($row->department_id,'department_id','1:departments:id:name') !!} </td>--}}

							{{--</tr>--}}

							<tr>
								<td width='40%' class='label-view text-right'>
									{{ SiteHelpers::activeLang('Close', (isset($fields['closed']['language'])? $fields['closed']['language'] : array())) }}
								</td>
								<td>
									<?php
									if(!empty($row->closed)){
										$date=date("m/d/Y", strtotime($row->closed));
										echo $date;
									}
									?>
								</td>

							</tr>

							<tr>
								<td width='40%' class='label-view text-right'>
									{{ SiteHelpers::activeLang('Attached file list', (isset($fields['file_path']['language'])? $fields['file_path']['language'] : array())) }}
								</td>
								<td>

									<?php
									if(!empty($row->file_path))
									{
									$files = explode(',', $row->file_path);
									foreach($files as $index => $file_name) :
									$date=date("m/d/Y", strtotime($row->Created));
									echo $fid.' | '.$date.' | ';
									?>
									<a href="<?php echo url().'/uploads/tickets/'.$file_name; ?>" target="_blank">
										<?php echo strlen($file_name) > 20 ? substr($file_name,0,20).'.'.substr(strrchr($file_name,'.'),1) : $file_name; ?>

									</a></br>
									<?php
									endforeach;
									}
									?>
									<?php if($commentsCount!=0){foreach($comments as $comment):?>
									<?php
									if(!empty($comment->Attachments))
									{
									$files = explode(',', $comment->Attachments);
									foreach($files as $index => $file_name) :
									$date=date("m/d/Y", strtotime($comment->Posted));
									?>
									<?php echo $fid.' | '.$date.' | '; ?>
									<a href="<?php echo url().'/uploads/tickets/comments-attachments/'.$file_name; ?>" target="_blank">
										<?php echo strlen($file_name) > 20 ? substr($file_name,0,20).'.'.substr(strrchr($file_name,'.'),1) : $file_name; ?>
									</a></br>
									<?php
									endforeach;
									}
									endforeach;
									}
									?>
								</td>

							</tr>

							</tbody>
						</table>
					</div>
					<div class="col-lg-7 animated fadeInRight delayp1">
						<div class="blog-post ">
							<div class="post-item">

								<div class="row" style="padding: 2%;">
									<h3>Ticket History<span style="float: right; font-size: x-small" id="comments" class="text-success"> ( <?php echo $commentsCount ?> )  Comment(s)</span> </h3>
								</div>
								</br>
								<div class="summary">

									<p><span style="color:rgb(113,113,113);"><?php echo $row->Description; ?></span>
								</div>

							</div>
						</div>
						<hr>
						<hr>
						<?php if($commentsCount!=0){foreach($comments as $comment){?>
						<div class="row">
							<div class="col col-xs-12">
								<div class="cont">
									<div class="ticket-message message-left last first clearfix">
										<div class="profile-image" style="padding-bottom: 5px;">
											<strong><?php echo $fid.' '; ?>
												<?php $date=date("m/d/Y H:i:s", strtotime($comment->Posted)); echo $date; ?></strong>
										</div>
									</div>
								</div>
								<div class="text">
									<div class="message">
										<?php echo $comment->Comments; ?>																									</div>
									<div>
									</div>
								</div>
							</div>
						</div>
						<hr>
						<?php } }?>
						{!! Form::open(array('url'=>'servicerequests/comment/'.SiteHelpers::encryptID($row['TicketID']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'servicerequestsFormAjax')) !!}
						<div class="col-md-12">
							<fieldset><legend> New Reply</legend>

								<div class="form-group ">
									<label for="TicketID" class=" control-label col-md-2 text-left">
										{!! SiteHelpers::activeLang('TicketID', (isset($fields['TicketID']['language'])? $fields['TicketID']['language'] : array())) !!}
									</label>
									<div class="col-md-5">
										{!! Form::label('TicketID', $row['TicketID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
										{!! Form::hidden('TicketID', $row['TicketID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}

									</div>
								</div>
								<div class="form-group hidethis " style="display:none;">
									<label for="UserID" class=" control-label col-md-2 text-left">
										{!! SiteHelpers::activeLang('TicketID', (isset($fields['UserID']['language'])? $fields['UserID']['language'] : array())) !!}
									</label>
									<div class="col-md-5">
										{!! Form::text('UserID', $uid,array('class'=>'form-control', 'placeholder'=>'',   )) !!}
									</div>
								</div>
								<div class="form-group  " >
									<label for="Comments" class=" control-label col-md-2 text-left">
										{!! SiteHelpers::activeLang('Message', (isset($fields['Comments']['language'])? $fields['Comments']['language'] : array())) !!}
									</label>
									<div class="col-md-5">
										<textarea name='Comments' rows='5' id='Comments' class='form-control 'required  ></textarea>
									</div>
								</div>
								<div class="form-group  " >
									<label for="Assign To" class=" control-label col-md-2 text-left">
										{!! SiteHelpers::activeLang('Re assign to user', (isset($fields['assign_to']['language'])? $fields['assign_to']['language'] : array())) !!}
									</label>
									<div class="col-md-5">
										<select name='assign_to[]' multiple rows='5' id='assign_to' class='select2 ' ></select>
									</div>
								</div>
								{{--<div class="form-group  " >--}}
									{{--<label for="Department" class=" control-label col-md-2 text-left">--}}
										{{--{!! SiteHelpers::activeLang('Re assign to department', (isset($fields['department_id']['language'])? $fields['department_id']['language'] : array())) !!}--}}
									{{--</label>--}}
									{{--<div class="col-md-5">--}}
										{{--<select name='department_id' rows='5' id='department_id' class='select2 ' required  ></select>--}}
									{{--</div>--}}
								{{--</div>--}}
								<div class="form-group  " >
									<label for="Priority" class=" control-label col-md-2 text-left">
										{!! SiteHelpers::activeLang('Change priority', (isset($fields['Priority']['language'])? $fields['Priority']['language'] : array())) !!}
									</label>
									<div class="col-md-5">

										<?php $Priority = explode(',',$row['Priority']);
                                        $Priority_opt = array('normal' => 'Normal' ,  'emergency' => 'Emergency'); ?>
										<select name='Priority' rows='5' required  class='select2 '  >
											<?php
											foreach($Priority_opt as $key=>$val)
											{
												echo "<option  value ='$key' ".($row['Priority'] == $key ? " selected='selected' " : '' ).">$val</option>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="form-group  " >
									<label for="Status" class=" control-label col-md-2 text-left">
										{!! SiteHelpers::activeLang('Change status', (isset($fields['Status']['language'])? $fields['Status']['language'] : array())) !!}
									</label>
									<div class="col-md-5">

										<?php $Status = explode(',',$row['Status']);
										$Status_opt = array( 'open' => 'Open' ,  'inqueue' => 'Pending' ,  'closed' => 'Closed' , ); ?>
										<select name='Status' rows='5' required  class='select2 '  >
											<?php
											foreach($Status_opt as $key=>$val)
											{
												echo "<option  value ='$key' ".($row['Status'] == $key ? " selected='selected' " : '' ).">$val</option>";
											}
											?></select>
									</div>
								</div>

								<div class="form-group  " >
									<label for="File Path" class=" control-label col-md-2 text-left">
										{!! SiteHelpers::activeLang('File Path', (isset($fields['Attachments']['language'])? $fields['Attachments']['language'] : array())) !!}
									</label>
									<div class="col-md-5">

										<a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles('Attachments')"><i class="fa fa-plus"></i></a>
										<div class="AttachmentsUpl">
											<input  type='file' name='Attachments[]'  />
										</div>

									</div>
								</div>
							</fieldset>
						</div>




						<div style="clear:both"></div>

						<div class="form-group">
							<label class="col-sm-3 text-right">&nbsp;</label>
							<div class="col-sm-8">
								<button type="submit" class="btn btn-primary btn-sm "><i class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
								<button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm"><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
							</div>
						</div>

						{!! Form::hidden('Description', $row->Description) !!}
						{!! Form::hidden('issue_type', $row->issue_type) !!}
						{!! Form::hidden('location_id', $row->location_id) !!}
						{!! Form::hidden('game_id', $row->game_id) !!}
						{!! Form::hidden('debit_card', $row->debit_card) !!}
						{!! Form::close() !!}
					</div>
				</div>
			</div>


			@if($setting['form-method'] =='native')
		</div>
	</div>
@endif

<script type="text/javascript">
	$(document).ready(function() {

		$("#location_id").jCombo("{{ URL::to('servicerequests/comboselect?filter=location:id:location_name') }}",
				{  selected_value : '{{ $row["location_id"] }}' });

		$("#game_id").jCombo("{{ URL::to('servicerequests/comboselect?filter=game:id:game_name') }}&parent=location_id:",
				{  parent: '#location_id', selected_value : '{{ $row["game_id"] }}' });

		$("#department_id").jCombo("{{ URL::to('servicerequests/comboselect?filter=departments:id:name') }}",
				{  selected_value : '{{ $row["department_id"] }}' });

		$("#assign_to").jCombo("{{ URL::to('servicerequests/comboselect?filter=users:id:first_name|last_name') }}",
				{  selected_value : '{{ $row["assign_to"] }}' });


		$('.editor').summernote();
		$('.previewImage').fancybox();
		$('.tips').tooltip();
		$(".select2").select2({ width:"98%"});
		$('.date').datepicker({format:'mm/dd/yyyy',autoClose:true})
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
		var form = $('#servicerequestsFormAjax');
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