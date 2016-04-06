@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>

    </div>


	<div class="page-content-wrapper m-t">

	<ul class="nav nav-tabs" style="margin-bottom:10px;">
	  <li class="active"><a href="{{ URL::to('core/users')}}"><i class="fa fa-user"></i> Users </a></li>
	  <li ><a href="{{ URL::to('core/groups')}}"><i class="fa fa-users"></i> Groups</a></li>
	  <li class=""><a href="{{ URL::to('core/users/blast')}}"><i class="fa fa-envelope"></i> Send Email </a></li>
	</ul>

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small></h5>
<div class="sbox-tools" >
		<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> Clear Search </a>
		@if(Session::get('gid') ==1)
			<a href="{{ URL::to('sximo/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
		@endif
		</div>
	</div>
	<div class="sbox-content">
	    <div class="toolbar-line ">
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('core/users/update') }}" class="tips btn btn-sm btn-white"  title="{{ Lang::get('core.btn_create') }}">
			<i class="fa fa-plus-circle "></i>&nbsp;{{ Lang::get('core.btn_create') }}</a>
			@endif
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="SximoDelete();" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_remove') }}">
			<i class="fa fa-minus-circle "></i>&nbsp;{{ Lang::get('core.btn_remove') }}</a>
			@endif
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('core/users/download') }}" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_download') }}">
			<i class="fa fa-download "></i>&nbsp;{{ Lang::get('core.btn_download') }} </a>
			@endif
			<a href="{{ URL::to( 'core/users/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advance Search'); return false;" ><i class="text-danger fa fa-search"></i> Search</a>

                @if(SiteHelpers::isModuleEnabled('users'))
                    <a href="{{ URL::to('tablecols/arrange-cols/users') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Column Selector'); return false;" ><i class="fa fa-bars"></i> Arrange Columns</a>
                 <?php   $colconfigs=SiteHelpers::getRequiredConfigs($module_id);  ?>
                    @if(!empty($colconfigs))
                        <select class="form-control" style="width:25%!important;display:inline;" name="col-config"
                                id="col-config">
                            <option value="0">Select Configuraton</option>
                            @foreach( $colconfigs as $configs )
                                <option @if($config_id == $configs['config_id']) selected
                                                                                 @endif value={{ $configs['config_id'] }}> {{ $configs['config_name'] }}   </option>
                            @endforeach
                        </select>
                    @endif
                @endif
		</div>



	 {!! Form::open(array('url'=>'core/users/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable' )) !!}
	 <div class="table-responsive" style="min-height:300px;">
    <table class="table" style="table-layout: fixed;width:100%">
        <thead>
			<tr>
				<th class="number" width="30"> No </th>
				<th width="60"> <input type="checkbox" class="checkall" /></th>
				<th width="100">Login</th>
                <th width="150"> Block User</th>
				@foreach ($tableGrid as $t)
					@if($t['view'] =='1')
						<th width="150">{{ $t['label'] }}</th>
					@endif
				@endforeach

				<th width="100">{{ Lang::get('core.btn_action') }}</th>
                <th width="100"> Upload Image</th>
			  </tr>

        </thead>

        <tbody>

            @foreach ($rowData as $row)

                <tr>
					<td width="30"> {{ ++$i }} </td>
					<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" />  </td>
					<td>
						<a href="{{ URL::to('core/users/play/'.$row->id)}}">Login</a>
					</td>
                    <td>
                        @if($row->banned=='Yes')
                            <a href="{{ URL::to('core/users/unblock/'.$row->id)}}">Unblock</a>
                        @else
                            <a href="{{ URL::to('core/users/block/'.$row->id)}}">Block</a>
                        @endif
                    </td>
				 @foreach ($tableGrid as $field)

					 @if($field['view'] =='1')
					 <td>
						@if($field['field'] == 'avatar')
							<?php if( file_exists( './uploads/users/'.$row->avatar) && $row->avatar !='') { ?>
							<img src="{{ URL::to('uploads/users').'/'.$row->avatar }} " border="0" width="40" class="img-circle" />
							<?php  } else { ?>
							<img alt="" src="http://www.gravatar.com/avatar/{{ md5($row->email) }}" width="40" class="img-circle" />
							<?php } ?>
					 	@elseif($field['field'] =='active')
							{!! ($row->active ==1 ? '<lable class="label label-success">Active</label>' : '<lable class="label label-danger">Inactive</label>')  !!}

						@else
							{{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array() ) /*--}}
							{!! SiteHelpers::gridDisplay($row->$field['field'],$field['field'],$conn) !!}
						@endif
					 </td>
					 @endif
				 @endforeach

				 <td>
					 	@if($access['is_detail'] ==1)
						<a href="{{ URL::to('core/users/show/'.$row->id.'?return='.$return)}}" class="tips btn btn-xs btn-white" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-search "></i></a>
						@endif
						@if($access['is_edit'] ==1)
						<a  href="{{ URL::to('core/users/update/'.$row->id.'?return='.$return) }}" class="tips btn btn-xs btn-white" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit "></i></a>
						@endif


				</td>
                <td><a href="{{ URL::to('core/users/upload/'.$row->id)}}">Upload Image</a></td>
                </tr>

            @endforeach


        </tbody>

    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
	@include('footer')
	</div>
</div>
	</div>
</div>
<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#SximoTable').attr('action','{{ URL::to("core/users/multisearch")}}');
		$('#SximoTable').submit();
	});
});
$("#col-config").change(function(){
    var config_id=$('#col-config').val();
        location.href = "/sximo/public/core/users?config_id=" + config_id;


});

</script>
@stop