@extends('layouts.app')

@section('content')
<div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
		<li><a href="{{ URL::to('core/users?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {{ Lang::get('core.detail') }} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper">   
	   <div class="toolbar-line">
	   		<a href="{{ URL::to('core/users?return='.$return) }}" class="tips btn btn-xs btn-default" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('core/users/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{{ Lang::get('core.btn_edit') }}</a>
			@endif  		   	  
		</div>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-eye"></i> <?php echo $pageTitle ;?></h4></div>
	<div class="sbox-content"> 	


	
	<table class="table table-striped table-bordered" >
		<tbody>	
	
					<tr>
						<td width='30%' class='label-view text-right'>Avatar</td>
						<td>
							<?php if( file_exists( './uploads/users/'.$row->avatar) && $row->avatar !='') { ?>
							<img src="{{ URL::to('uploads/users').'/'.$row->avatar }} " border="0" width="40" class="img-circle" />
							<?php  } else { ?> 
							<img alt="" src="http://www.gravatar.com/avatar/{{ md5($row->email) }}" width="40" class="img-circle" />
							<?php } ?>	
						</td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Group</td>
						<td>{{ SiteHelpers::gridDisplayView($row->group_id,'group_id','1:tb_groups:group_id:name',$nodata['group_id']) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Username</td>
						<td>{{ \DateHelpers::formatStringValue($row->username) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>First Name</td>
						<td>{{ \DateHelpers::formatStringValue($row->first_name) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Last Name</td>
						<td>{{ \DateHelpers::formatStringValue($row->last_name) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Email</td>
						<td>{{ \DateHelpers::formatStringValue($row->email) }} </td>
						
					</tr>
				

					<tr>
						<td width='30%' class='label-view text-right'>Last Login</td>
						<td>	{{  \DateHelpers::formatDateTime($row->last_login)  }} </td>


					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Updated On</td>
						<td>	{{  \DateHelpers::formatDateTime($row->updated_at)  }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Active</td>
						<td>{!! ($row->active ==1 ? '<lable class="label label-success">Active</label>' : '<lable class="label label-danger">Inactive</label>')  !!} </td>
						
					</tr>
				
		</tbody>

	</table>
        <h3>Locations:</h3>

        <table class="table table-striped table-bordered table-hover" >
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Name</th>
                    <th>Street</th>
                    <th>City</th>
                    <th width="50">State</th>
                    <th width="60">Zip</th>
                </tr>
            </thead>
            <tbody>
            @if(count($user_locations) > 0)
            @foreach($user_locations as $locations)
            <tr>
                <td>{{ \DateHelpers::formatZeroValue($locations->id) }}</td>
                <td>{{ \DateHelpers::formatStringValue($locations->location_name_short) }}</td>
                <td>{{ \DateHelpers::formatStringValue($locations->street1) }}</td>
                <td>{{ \DateHelpers::formatStringValue($locations->city) }}</td>
                <td>{{ \DateHelpers::formatStringValue($locations->state) }}</td>
                <td>{{ \DateHelpers::formatStringValue($locations->zip) }}</td>
            </tr>
                @endforeach
            @else
                <tr><td colspan="6" style="text-align: center"> No Data </td></tr>
            @endif

            </tbody>
        </table>

	</div>
</div>	

	</div>
</div>
	  
@stop
