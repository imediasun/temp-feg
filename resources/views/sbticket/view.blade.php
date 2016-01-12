@if($setting['view-method'] =='native')
<div class="sbox">
	<div class="sbox-title">
		<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
			<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')">
			<i class="fa fa fa-times"></i></a>
		</h4>
	 </div>

	<div class="sbox-content">
@endif
		<div class="container">
			<div class="row m-b-lg ">
				<div class="col-lg-4 animated fadeInLeft delayp1">
					<h3>Ticket Info</h3>
					<table class="table table-striped table-bordered" >
						<tbody>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Date Open', (isset($fields['Created']['language'])? $fields['Created']['language'] : array())) }}
							</td>
							<td><?php echo date("m-d-Y", strtotime($row->Created));  ?></td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Last Event date', (isset($fields['updated']['language'])? $fields['updated']['language'] : array())) }}
							</td>
							<td>
								<?php
								if($row->updated != '0000-00-00 00:00:00')
									echo date("m-d-Y", strtotime($row->updated));
								else
									echo 'N/A';
								?>
							</td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Title', (isset($fields['Subject']['language'])? $fields['Subject']['language'] : array())) }}
							</td>
							<td>{{ $row->Subject }} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Location', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}
							</td>
							<td>{!! SiteHelpers::gridDisplayView($row->location_id,'location_id','1:location:id:location_name') !!} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Game Name', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) }}
							</td>
							<td>{!! SiteHelpers::gridDisplayView($row->game_id,'game_id','1:game:id:game_name') !!} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Assign To', (isset($fields['assign_to']['language'])? $fields['assign_to']['language'] : array())) }}
							</td>
							<td>{!! SiteHelpers::gridDisplayView($row->assign_to,'assign_to','1:employees:id:first_name|last_name') !!} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Priority', (isset($fields['Priority']['language'])? $fields['Priority']['language'] : array())) }}
							</td>
							<td>{{ $row->Priority }} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Status', (isset($fields['Status']['language'])? $fields['Status']['language'] : array())) }}
							</td>
							<td>{{ $row->Status }} </td>

						</tr>
						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Issue Type', (isset($fields['issue_type']['language'])? $fields['issue_type']['language'] : array())) }}
							</td>
							<td>{{ $row->issue_type }} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Department', (isset($fields['department_id']['language'])? $fields['department_id']['language'] : array())) }}
							</td>
							<td>{!! SiteHelpers::gridDisplayView($row->department_id,'department_id','1:departments:id:name') !!} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Close', (isset($fields['closed']['language'])? $fields['closed']['language'] : array())) }}
							</td>
							<td>
								<?php
								if($row->closed != '0000-00-00 00:00:00')
									echo date("m-d-Y", strtotime($row->closed));
								else
									echo 'N/A';
								?>
							</td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Attached file list', (isset($fields['file_path']['language'])? $fields['file_path']['language'] : array())) }}
							</td>
							<td>

								<?php
								if(!empty($row->file_path))
								{
								$files = explode(',', $row->file_path);
								foreach($files as $index => $file_name) :
								?>
								<a href="<?php echo url().'/'.$file_name; ?>" target="_blank"><?php echo $file_name; ?></a></br>
								<?php
								endforeach;
								}

								?>

							</td>

						</tr>

						</tbody>
					</table>
				</div>
				<div class="col-lg-6 col-md-offset-1 animated fadeInRight delayp1">
					<div class="blog-post ">
						<div class="post-item">

							<div class="blog-info-small">
								<i class="fa fa-calendar icon-muted"></i>  <span> Feb 23, 2014  </span>
								<i class="fa fa-comment-o icon-muted"></i>   <span>  0 comment(s)  </span>


							</div>
							</br>
							<div class="summary">

								<p><span style="color:rgb(113,113,113);"><?php echo $row->Description; ?></span></div>

						</div>
					</div>
					<hr>
					<h5 id="comments" class="text-success"> ( 0 )  Comment(s) </h5>
					<hr>

								{!! Form::open(array('url'=>'sbticket/save/'.SiteHelpers::encryptID($row['TicketID']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'sbticketFormAjax')) !!}
								<div class="col-md-12">
									<fieldset><legend> New Reply</legend>

										<div class="form-group hidethis " style="display:none;">
											<label for="TicketID" class=" control-label col-md-3 text-left">
												{!! SiteHelpers::activeLang('TicketID', (isset($fields['TicketID']['language'])? $fields['TicketID']['language'] : array())) !!}
											</label>
											<div class="col-md-9">
												{!! Form::text('TicketID', $row['TicketID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
											</div>
										</div>
										<div class="form-group  " >
											<label for="Description" class=" control-label col-md-3 text-left">
												{!! SiteHelpers::activeLang('Message', (isset($fields['Description']['language'])? $fields['Description']['language'] : array())) !!}
											</label>
											<div class="col-md-9">
					 	 	<textarea name='Description' rows='5' id='Description' class='form-control 'required  ></textarea>
											</div>
										</div>
										<div class="form-group  " >
											<label for="Assign To" class=" control-label col-md-3 text-left">
												{!! SiteHelpers::activeLang('Re assign to user', (isset($fields['assign_to']['language'])? $fields['assign_to']['language'] : array())) !!}
											</label>
											<div class="col-md-9">
												<select name='assign_to[]' multiple rows='5' id='assign_to' class='select2 ' required  ></select>
											</div>
										</div>
										<div class="form-group  " >
											<label for="Department" class=" control-label col-md-3 text-left">
												{!! SiteHelpers::activeLang('Re assign to department', (isset($fields['department_id']['language'])? $fields['department_id']['language'] : array())) !!}
											</label>
											<div class="col-md-9">
												<select name='department_id' rows='5' id='department_id' class='select2 ' required  ></select>
											</div>
										</div>
										<div class="form-group  " >
											<label for="Priority" class=" control-label col-md-3 text-left">
												{!! SiteHelpers::activeLang('Change priority', (isset($fields['Priority']['language'])? $fields['Priority']['language'] : array())) !!}
											</label>
											<div class="col-md-9">

												<?php $Priority = explode(',',$row['Priority']);
												$Priority_opt = array( 'critical' => 'Critical' ,  'high' => 'High' ,  'medium' => 'Medium' ,  'low' => 'Low' , ); ?>
												<select name='Priority' rows='5' required  class='select2 '  >
													<?php
													foreach($Priority_opt as $key=>$val)
													{
														echo "<option  value ='$key' ".($row['Priority'] == $key ? " selected='selected' " : '' ).">$val</option>";
													}
													?></select>
											</div>
										</div>
										<div class="form-group  " >
											<label for="Status" class=" control-label col-md-3 text-left">
												{!! SiteHelpers::activeLang('Change status', (isset($fields['Status']['language'])? $fields['Status']['language'] : array())) !!}
											</label>
											<div class="col-md-9">

												<?php $Status = explode(',',$row['Status']);
												$Status_opt = array( 'open' => 'Open' ,  'inqueue' => 'In Queue' ,  'close' => 'Close' , ); ?>
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
											<label for="File Path" class=" control-label col-md-3 text-left">
												{!! SiteHelpers::activeLang('File Path', (isset($fields['file_path']['language'])? $fields['file_path']['language'] : array())) !!}
											</label>
											<div class="col-md-9">

												<a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles('file_path')"><i class="fa fa-plus"></i></a>
												<div class="file_pathUpl">
													<input  type='file' name='file_path[]'  />
												</div>

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
				</div>
			</div>
		</div>


@if($setting['form-method'] =='native')
	</div>
</div>
@endif

<script type="text/javascript">
	$(document).ready(function() {

		$("#location_id").jCombo("{{ URL::to('sbticket/comboselect?filter=location:id:location_name') }}",
				{  selected_value : '{{ $row["location_id"] }}' });

		$("#game_id").jCombo("{{ URL::to('sbticket/comboselect?filter=game:id:game_name') }}&parent=location_id:",
				{  parent: '#location_id', selected_value : '{{ $row["game_id"] }}' });

		$("#department_id").jCombo("{{ URL::to('sbticket/comboselect?filter=departments:id:name') }}",
				{  selected_value : '{{ $row["department_id"] }}' });

		$("#assign_to").jCombo("{{ URL::to('sbticket/comboselect?filter=employees:id:first_name|last_name') }}",
				{  selected_value : '{{ $row["assign_to"] }}' });


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
		var form = $('#sbticketFormAjax');
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