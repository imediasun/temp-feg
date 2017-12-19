
@extends('layouts.app')

@section('content')
	<div class="page-content row">
		<!-- Page header -->
		<div class="page-header ">
			<div class="page-title">
				<h3>  {{ Lang::get('core.t_module') }} <small>{{ Lang::get('core.t_modulesmall') }}</small></h3>
			</div>
		</div>
		<div class="page-content-wrapper">
			<div class="ribon-sximo">
				<section >

					<div class="row m-l-none m-r-none m-t  white-bg shortcut " >
						<div class="col-sm-3 b-r  p-sm ">
							<span class="pull-left m-r-sm text-info"><i class="fa fa-plus-circle"></i></span>

							<a href="{{ URL::to('feg/module/create') }}" class="clear">
							<span class="h3 block m-t-xs"><strong> {{ Lang::get('core.btn_create') }} Module </strong>
							</span> <small class="text-muted text-uc"> {{ Lang::get('core.fr_createmodule') }}  </small>
							</a>
						</div>
						<div class="col-sm-3 b-r  p-sm">
							<span class="pull-left m-r-sm text-success"><i class="fa  fa-cloud-upload"></i></span>
							<a href="javascript:void(0)" class="clear " onclick="$('.unziped').toggle()">
							<span class="h3 block m-t-xs"><strong>{{ Lang::get('core.btn_install') }} Module </strong>
							</span> <small class="text-muted text-uc">{{ Lang::get('core.fr_installmodule') }} </small>
							</a>
						</div>
						<div class="col-sm-3 b-r  p-sm">
							<span class="pull-left m-r-sm text-warning"><i class="fa fa-download"></i></span>
							<a href="{{ URL::to('feg/module/package') }}" class="clear post_url">
							<span class="h3 block m-t-xs"><strong>{{ Lang::get('core.btn_backup') }} Module</strong>
							</span> <small class="text-muted text-uc"> {{ Lang::get('core.fr_backupmodule') }} </small>
							</a>
						</div>
						<div class="col-sm-6 col-md-3 b-r  p-sm">
							<span class="pull-left m-r-sm text-danger"><i class="icon-database"></i></span>
							<a href="{{ URL::to('feg/tables') }}" >
							<span class="h3 block m-t-xs"><strong>Database</strong>
							</span> <small class="text-muted text-uc"> Manage Database Tables </small>
							</a>
						</div>


					</div>

				</section>
			</div>
			@if(Session::has('message'))
				{{ Session::get('message') }}
			@endif
			<div class="white-bg p-sm m-b unziped" style=" border:solid 1px #ddd; display:none;">
				{!! Form::open(array('url'=>'feg/module/install/', 'class'=>'breadcrumb-search','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
				<h3>Select File ( Module zip installer ) </h3>
				<p>  <input type="file" name="installer" required style="float:left;">  <button type="submit" class="btn btn-primary btn-xs" style="float:left;"  ><i class="icon-upload"></i> Install</button></p>
				</form>
				<div class="clr"></div>
			</div>

			<ul class="nav nav-tabs" style="margin-bottom:10px;">
				<li  @if($type =='addon') class="active" @endif><a href="{{ URL::to('feg/module')}}"> {{ Lang::get('core.tab_installed') }}  </a></li>
				<li @if($type =='core') class="active" @endif><a href="{{ URL::to('feg/module?t=core')}}">{{ Lang::get('core.tab_core') }}</a></li>
			</ul>

			{!! Form::open(array('url'=>'feg/module/package#', 'class'=>'form-horizontal' ,'ID' =>'SximoTable' )) !!}
			<div class="table-responsive ibox-content" style="min-height:400px;">
				@if(count($rowData) >=1)
					<table class="table table-striped ">
						<thead>
						<tr>
							<th>Action</th>
							<th><input type="checkbox" class="checkall" /></th>
							<th>Module</th>
							<th>Controller</th>
							<th>Database</th>
							<th>PRI</th>
							<th>Created</th>

						</tr>
						</thead>
						<tbody>
						<?php $dropDownId=1; ?>
						@foreach ($rowData as $row)
							<tr>
								<td>
									<div class="btn-group module-dropdown-list" style="padding: 3px;background-color: #1c84c6;padding-right: 8px;">
										<button class="btn btn-primary btn-xs " onclick="checkElementPosition(this,'dropdown_{!! $dropDownId !!}'); return false;" >
											<I class="icon-cogs"></I> <span class="caret"></span>
										</button>
										<ul style="display: none;" id="dropdown_{!! $dropDownId !!}" class="dropdown-menu module-dropdown-list-child icons-right">
											@if($type != 'core')
												<li><a href="{{ URL::to($row->module_name)}}"><i class="icon-grid"></i> {{ Lang::get('core.btn_view') }} Module </a></li>
											@endif
											<li><a href="{{ URL::to('feg/module/config/'.$row->module_name)}}"><i class="icon-pencil3"></i> {{ Lang::get('core.btn_edit') }}</a></li>
											@if($type != 'core')
												<li><a href="javascript://ajax" onclick="SximoConfirmDelete('{{ URL::to('feg/module/destroy/'.$row->module_id)}}')"><i class="icon-bubble-trash"></i> {{ Lang::get('core.btn_remove') }}</a></li>
												<li class="divider"></li>
												<li><a href="{{ URL::to('feg/module/rebuild/'.$row->module_id)}}"><i class="icon-spinner7"></i> Rebuild All Codes</a></li>
											@endif
										</ul>
									</div>
								</td>
								<td>

									<input type="checkbox" class="ids" name="id[]" value="{{ $row->module_id }}" /> </td>
								<td>{{ $row->module_title }} </td>
								<td>{{ $row->module_name }} </td>
								<td>{{ $row->module_db }} </td>
								<td>{{ $row->module_db_key }} </td>
								<td>{{ $row->module_created }} </td>
							</tr>
							<?php $dropDownId++; ?>
						@endforeach
						</tbody>
					</table>

				@else

					<p class="text-center" style="padding:50px 0;">{{ Lang::get('core.norecord') }}
						<br /><br />
						<a href="{{ URL::to('feg/module/create')}}" class="btn btn-default "><i class="icon-plus-circle2"></i> New module </a>
					</p>
				@endif
			</div>
			{!! Form::close() !!}


		</div>

	</div>

	<style>
		.btn-xs, .btn-group-xs > .btn {
			padding: 0px 3px;
			font-size: 12px;
			line-height: 1.5;
			border-radius: 3px;
		}

		.btn-group:hover{
			background-color: #14457a !important;
		}
		.btn-group:active{
			background-color: #14457a !important;
		}
	</style>
	<script language='javascript' >
		jQuery(document).ready(function($){
			$('.post_url').click(function(e){
				e.preventDefault();
				if( ( $('.ids',$('#SximoTable')).is(':checked') )==false ){
					alert( $(this).attr('data-title') + " not selected");
					return false;
				}
				$('#SximoTable').attr({'action' : $(this).attr('href') }).submit();
			})
		});
		$(document).click(function(event) {
			if(!$(event.target).closest('.module-dropdown-list').length) {
				$('.module-dropdown-list-child').each(function(){
				if($(this).is(":visible")) {
					$(this).hide();
				}
				});
			}
		});
		function checkElementPosition(element,dropdown){
			$('.module-dropdown-list-child').each(function(){
				if($(this).attr("id")!==dropdown)
				{
					$(this).css("display", "none");
				}

			});
			var object = element.getBoundingClientRect();
			var top = object.top;

			if(top>250){

				$("#"+dropdown).css("top","-160px");
			}else{

				$("#"+dropdown).css("top","100%");
			}
			$("#"+dropdown).toggle();
			console.log(object.top, object.right, object.bottom, object.left);
		}
	</script>

@stop