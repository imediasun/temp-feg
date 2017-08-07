<div class=""> 	

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
						<td>{{ $row->username }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>First Name</td>
						<td>{{ $row->first_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Last Name</td>
						<td>{{ $row->last_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Email</td>
						<td>{{ $row->email }} </td>
						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>Phone</td>
						<?php
                            $phones = [];
                            if (!empty($row->primary_phone)) {
                                $phones[] = $row->primary_phone;
                            }
                            if (!empty($row->secondary_phone)) {
                                $phones[] = $row->secondary_phone;
                            }
                            $phone = implode("<br/>", $phones);
                        ?>
						<td>{!! $phone !!} </td>

					</tr>
				

					<tr>
						<td width='30%' class='label-view text-right'>Last Login</td>
						<td>	{{  $row->last_login = date("m/d/Y H:i:s", strtotime($row->last_login))  }} </td>


					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Updated At</td>
						<td>	{{  $row->updated_at = date("m/d/Y H:i:s", strtotime($row->updated_at))  }} </td>
						
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
        @foreach($user_locations as $locations)
        <tr>
            <td>{{ $locations->id }}</td>
            <td>{{ $locations->location_name_short }}</td>
            <td>{{ $locations->street1 }}</td>
            <td>{{ $locations->city }}</td>
            <td>{{ $locations->state }}</td>
            <td>{{ $locations->zip }}</td>
        </tr>
            @endforeach
        </tbody>
    </table>
	
</div>
