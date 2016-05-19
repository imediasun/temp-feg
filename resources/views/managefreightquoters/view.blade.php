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

		<table class="table table-striped table-bordered" >
			<tbody>	
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}
						</td>
						<td>{{ $row->id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Submitted', (isset($fields['date_submitted']['language'])? $fields['date_submitted']['language'] : array())) }}
						</td>
						<td>{{ $row->date_submitted }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Booked', (isset($fields['date_booked']['language'])? $fields['date_booked']['language'] : array())) }}
						</td>
						<td>{{ $row->date_booked }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date Paid', (isset($fields['date_paid']['language'])? $fields['date_paid']['language'] : array())) }}
						</td>
						<td>{{ $row->date_paid }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Vend From', (isset($fields['vend_from']['language'])? $fields['vend_from']['language'] : array())) }}
						</td>
						<td>{{ $row->vend_from }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Vend To', (isset($fields['vend_to']['language'])? $fields['vend_to']['language'] : array())) }}
						</td>
						<td>{{ $row->vend_to }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc From', (isset($fields['loc_from']['language'])? $fields['loc_from']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_from }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc To 1', (isset($fields['loc_to_1']['language'])? $fields['loc_to_1']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_to_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc To 2', (isset($fields['loc_to_2']['language'])? $fields['loc_to_2']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_to_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc To 3', (isset($fields['loc_to_3']['language'])? $fields['loc_to_3']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_to_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc To 4', (isset($fields['loc_to_4']['language'])? $fields['loc_to_4']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_to_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc To 5', (isset($fields['loc_to_5']['language'])? $fields['loc_to_5']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_to_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc To 6', (isset($fields['loc_to_6']['language'])? $fields['loc_to_6']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_to_6 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc To 7', (isset($fields['loc_to_7']['language'])? $fields['loc_to_7']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_to_7 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc To 8', (isset($fields['loc_to_8']['language'])? $fields['loc_to_8']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_to_8 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc To 9', (isset($fields['loc_to_9']['language'])? $fields['loc_to_9']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_to_9 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc To 10', (isset($fields['loc_to_10']['language'])? $fields['loc_to_10']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_to_10 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Add Name', (isset($fields['from_add_name']['language'])? $fields['from_add_name']['language'] : array())) }}
						</td>
						<td>{{ $row->from_add_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Add Street', (isset($fields['from_add_street']['language'])? $fields['from_add_street']['language'] : array())) }}
						</td>
						<td>{{ $row->from_add_street }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Add City', (isset($fields['from_add_city']['language'])? $fields['from_add_city']['language'] : array())) }}
						</td>
						<td>{{ $row->from_add_city }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Add State', (isset($fields['from_add_state']['language'])? $fields['from_add_state']['language'] : array())) }}
						</td>
						<td>{{ $row->from_add_state }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Add Zip', (isset($fields['from_add_zip']['language'])? $fields['from_add_zip']['language'] : array())) }}
						</td>
						<td>{{ $row->from_add_zip }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Contact Name', (isset($fields['from_contact_name']['language'])? $fields['from_contact_name']['language'] : array())) }}
						</td>
						<td>{{ $row->from_contact_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Contact Email', (isset($fields['from_contact_email']['language'])? $fields['from_contact_email']['language'] : array())) }}
						</td>
						<td>{{ $row->from_contact_email }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Contact Phone', (isset($fields['from_contact_phone']['language'])? $fields['from_contact_phone']['language'] : array())) }}
						</td>
						<td>{{ $row->from_contact_phone }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From Loading Info', (isset($fields['from_loading_info']['language'])? $fields['from_loading_info']['language'] : array())) }}
						</td>
						<td>{{ $row->from_loading_info }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Add Name', (isset($fields['to_add_name']['language'])? $fields['to_add_name']['language'] : array())) }}
						</td>
						<td>{{ $row->to_add_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Add Street', (isset($fields['to_add_street']['language'])? $fields['to_add_street']['language'] : array())) }}
						</td>
						<td>{{ $row->to_add_street }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Add City', (isset($fields['to_add_city']['language'])? $fields['to_add_city']['language'] : array())) }}
						</td>
						<td>{{ $row->to_add_city }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Add State', (isset($fields['to_add_state']['language'])? $fields['to_add_state']['language'] : array())) }}
						</td>
						<td>{{ $row->to_add_state }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Add Zip', (isset($fields['to_add_zip']['language'])? $fields['to_add_zip']['language'] : array())) }}
						</td>
						<td>{{ $row->to_add_zip }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Contact Name', (isset($fields['to_contact_name']['language'])? $fields['to_contact_name']['language'] : array())) }}
						</td>
						<td>{{ $row->to_contact_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Contact Email', (isset($fields['to_contact_email']['language'])? $fields['to_contact_email']['language'] : array())) }}
						</td>
						<td>{{ $row->to_contact_email }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Contact Phone', (isset($fields['to_contact_phone']['language'])? $fields['to_contact_phone']['language'] : array())) }}
						</td>
						<td>{{ $row->to_contact_phone }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To Loading Info', (isset($fields['to_loading_info']['language'])? $fields['to_loading_info']['language'] : array())) }}
						</td>
						<td>{{ $row->to_loading_info }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Description 1', (isset($fields['description_1']['language'])? $fields['description_1']['language'] : array())) }}
						</td>
						<td>{{ $row->description_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Dimensions 1', (isset($fields['dimensions_1']['language'])? $fields['dimensions_1']['language'] : array())) }}
						</td>
						<td>{{ $row->dimensions_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) }}
						</td>
						<td>{{ $row->notes }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Description 2', (isset($fields['description_2']['language'])? $fields['description_2']['language'] : array())) }}
						</td>
						<td>{{ $row->description_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Dimensions 2', (isset($fields['dimensions_2']['language'])? $fields['dimensions_2']['language'] : array())) }}
						</td>
						<td>{{ $row->dimensions_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Description 3', (isset($fields['description_3']['language'])? $fields['description_3']['language'] : array())) }}
						</td>
						<td>{{ $row->description_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Dimensions 3', (isset($fields['dimensions_3']['language'])? $fields['dimensions_3']['language'] : array())) }}
						</td>
						<td>{{ $row->dimensions_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Description 4', (isset($fields['description_4']['language'])? $fields['description_4']['language'] : array())) }}
						</td>
						<td>{{ $row->description_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Dimensions 4', (isset($fields['dimensions_4']['language'])? $fields['dimensions_4']['language'] : array())) }}
						</td>
						<td>{{ $row->dimensions_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Description 5', (isset($fields['description_5']['language'])? $fields['description_5']['language'] : array())) }}
						</td>
						<td>{{ $row->description_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Dimensions 5', (isset($fields['dimensions_5']['language'])? $fields['dimensions_5']['language'] : array())) }}
						</td>
						<td>{{ $row->dimensions_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Num Games Per Destination', (isset($fields['num_games_per_destination']['language'])? $fields['num_games_per_destination']['language'] : array())) }}
						</td>
						<td>{{ $row->num_games_per_destination }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 1 Game 1', (isset($fields['loc_1_game_1']['language'])? $fields['loc_1_game_1']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_1_game_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 1 Game 2', (isset($fields['loc_1_game_2']['language'])? $fields['loc_1_game_2']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_1_game_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 1 Game 3', (isset($fields['loc_1_game_3']['language'])? $fields['loc_1_game_3']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_1_game_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 1 Game 4', (isset($fields['loc_1_game_4']['language'])? $fields['loc_1_game_4']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_1_game_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 1 Game 5', (isset($fields['loc_1_game_5']['language'])? $fields['loc_1_game_5']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_1_game_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 2 Game 1', (isset($fields['loc_2_game_1']['language'])? $fields['loc_2_game_1']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_2_game_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 2 Game 2', (isset($fields['loc_2_game_2']['language'])? $fields['loc_2_game_2']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_2_game_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 2 Game 3', (isset($fields['loc_2_game_3']['language'])? $fields['loc_2_game_3']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_2_game_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 2 Game 4', (isset($fields['loc_2_game_4']['language'])? $fields['loc_2_game_4']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_2_game_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 2 Game 5', (isset($fields['loc_2_game_5']['language'])? $fields['loc_2_game_5']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_2_game_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 3 Game 1', (isset($fields['loc_3_game_1']['language'])? $fields['loc_3_game_1']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_3_game_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 3 Game 2', (isset($fields['loc_3_game_2']['language'])? $fields['loc_3_game_2']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_3_game_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 3 Game 3', (isset($fields['loc_3_game_3']['language'])? $fields['loc_3_game_3']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_3_game_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 3 Game 4', (isset($fields['loc_3_game_4']['language'])? $fields['loc_3_game_4']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_3_game_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 3 Game 5', (isset($fields['loc_3_game_5']['language'])? $fields['loc_3_game_5']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_3_game_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 4 Game 1', (isset($fields['loc_4_game_1']['language'])? $fields['loc_4_game_1']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_4_game_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 4 Game 2', (isset($fields['loc_4_game_2']['language'])? $fields['loc_4_game_2']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_4_game_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 4 Game 3', (isset($fields['loc_4_game_3']['language'])? $fields['loc_4_game_3']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_4_game_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 4 Game 4', (isset($fields['loc_4_game_4']['language'])? $fields['loc_4_game_4']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_4_game_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 4 Game 5', (isset($fields['loc_4_game_5']['language'])? $fields['loc_4_game_5']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_4_game_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 5 Game 1', (isset($fields['loc_5_game_1']['language'])? $fields['loc_5_game_1']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_5_game_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 5 Game 2', (isset($fields['loc_5_game_2']['language'])? $fields['loc_5_game_2']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_5_game_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 5 Game 3', (isset($fields['loc_5_game_3']['language'])? $fields['loc_5_game_3']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_5_game_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 5 Game 4', (isset($fields['loc_5_game_4']['language'])? $fields['loc_5_game_4']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_5_game_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 5 Game 5', (isset($fields['loc_5_game_5']['language'])? $fields['loc_5_game_5']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_5_game_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 6 Game 1', (isset($fields['loc_6_game_1']['language'])? $fields['loc_6_game_1']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_6_game_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 6 Game 2', (isset($fields['loc_6_game_2']['language'])? $fields['loc_6_game_2']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_6_game_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 6 Game 3', (isset($fields['loc_6_game_3']['language'])? $fields['loc_6_game_3']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_6_game_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 6 Game 4', (isset($fields['loc_6_game_4']['language'])? $fields['loc_6_game_4']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_6_game_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 6 Game 5', (isset($fields['loc_6_game_5']['language'])? $fields['loc_6_game_5']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_6_game_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 7 Game 1', (isset($fields['loc_7_game_1']['language'])? $fields['loc_7_game_1']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_7_game_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 7 Game 2', (isset($fields['loc_7_game_2']['language'])? $fields['loc_7_game_2']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_7_game_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 7 Game 3', (isset($fields['loc_7_game_3']['language'])? $fields['loc_7_game_3']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_7_game_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 7 Game 4', (isset($fields['loc_7_game_4']['language'])? $fields['loc_7_game_4']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_7_game_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 7 Game 5', (isset($fields['loc_7_game_5']['language'])? $fields['loc_7_game_5']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_7_game_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 8 Game 1', (isset($fields['loc_8_game_1']['language'])? $fields['loc_8_game_1']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_8_game_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 8 Game 2', (isset($fields['loc_8_game_2']['language'])? $fields['loc_8_game_2']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_8_game_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 8 Game 3', (isset($fields['loc_8_game_3']['language'])? $fields['loc_8_game_3']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_8_game_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 8 Game 4', (isset($fields['loc_8_game_4']['language'])? $fields['loc_8_game_4']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_8_game_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 8 Game 5', (isset($fields['loc_8_game_5']['language'])? $fields['loc_8_game_5']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_8_game_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 9 Game 1', (isset($fields['loc_9_game_1']['language'])? $fields['loc_9_game_1']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_9_game_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 9 Game 2', (isset($fields['loc_9_game_2']['language'])? $fields['loc_9_game_2']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_9_game_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 9 Game 3', (isset($fields['loc_9_game_3']['language'])? $fields['loc_9_game_3']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_9_game_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 9 Game 4', (isset($fields['loc_9_game_4']['language'])? $fields['loc_9_game_4']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_9_game_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 9 Game 5', (isset($fields['loc_9_game_5']['language'])? $fields['loc_9_game_5']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_9_game_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 10 Game 1', (isset($fields['loc_10_game_1']['language'])? $fields['loc_10_game_1']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_10_game_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 10 Game 2', (isset($fields['loc_10_game_2']['language'])? $fields['loc_10_game_2']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_10_game_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 10 Game 3', (isset($fields['loc_10_game_3']['language'])? $fields['loc_10_game_3']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_10_game_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 10 Game 4', (isset($fields['loc_10_game_4']['language'])? $fields['loc_10_game_4']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_10_game_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 10 Game 5', (isset($fields['loc_10_game_5']['language'])? $fields['loc_10_game_5']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_10_game_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 1 Pro', (isset($fields['loc_1_pro']['language'])? $fields['loc_1_pro']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_1_pro }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 2 Pro', (isset($fields['loc_2_pro']['language'])? $fields['loc_2_pro']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_2_pro }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 3 Pro', (isset($fields['loc_3_pro']['language'])? $fields['loc_3_pro']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_3_pro }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 4 Pro', (isset($fields['loc_4_pro']['language'])? $fields['loc_4_pro']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_4_pro }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 5 Pro', (isset($fields['loc_5_pro']['language'])? $fields['loc_5_pro']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_5_pro }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 6 Pro', (isset($fields['loc_6_pro']['language'])? $fields['loc_6_pro']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_6_pro }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 7 Pro', (isset($fields['loc_7_pro']['language'])? $fields['loc_7_pro']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_7_pro }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 8 Pro', (isset($fields['loc_8_pro']['language'])? $fields['loc_8_pro']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_8_pro }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 9 Pro', (isset($fields['loc_9_pro']['language'])? $fields['loc_9_pro']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_9_pro }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 10 Pro', (isset($fields['loc_10_pro']['language'])? $fields['loc_10_pro']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_10_pro }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 1 Quote', (isset($fields['loc_1_quote']['language'])? $fields['loc_1_quote']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_1_quote }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 2 Quote', (isset($fields['loc_2_quote']['language'])? $fields['loc_2_quote']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_2_quote }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 3 Quote', (isset($fields['loc_3_quote']['language'])? $fields['loc_3_quote']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_3_quote }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 4 Quote', (isset($fields['loc_4_quote']['language'])? $fields['loc_4_quote']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_4_quote }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 5 Quote', (isset($fields['loc_5_quote']['language'])? $fields['loc_5_quote']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_5_quote }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 6 Quote', (isset($fields['loc_6_quote']['language'])? $fields['loc_6_quote']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_6_quote }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 7 Quote', (isset($fields['loc_7_quote']['language'])? $fields['loc_7_quote']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_7_quote }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 8 Quote', (isset($fields['loc_8_quote']['language'])? $fields['loc_8_quote']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_8_quote }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 9 Quote', (isset($fields['loc_9_quote']['language'])? $fields['loc_9_quote']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_9_quote }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 10 Quote', (isset($fields['loc_10_quote']['language'])? $fields['loc_10_quote']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_10_quote }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 1 Trucking Co', (isset($fields['loc_1_trucking_co']['language'])? $fields['loc_1_trucking_co']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_1_trucking_co }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 2 Trucking Co', (isset($fields['loc_2_trucking_co']['language'])? $fields['loc_2_trucking_co']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_2_trucking_co }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 3 Trucking Co', (isset($fields['loc_3_trucking_co']['language'])? $fields['loc_3_trucking_co']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_3_trucking_co }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 4 Trucking Co', (isset($fields['loc_4_trucking_co']['language'])? $fields['loc_4_trucking_co']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_4_trucking_co }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 5 Trucking Co', (isset($fields['loc_5_trucking_co']['language'])? $fields['loc_5_trucking_co']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_5_trucking_co }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 6 Trucking Co', (isset($fields['loc_6_trucking_co']['language'])? $fields['loc_6_trucking_co']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_6_trucking_co }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 7 Trucking Co', (isset($fields['loc_7_trucking_co']['language'])? $fields['loc_7_trucking_co']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_7_trucking_co }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 8 Trucking Co', (isset($fields['loc_8_trucking_co']['language'])? $fields['loc_8_trucking_co']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_8_trucking_co }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 9 Trucking Co', (isset($fields['loc_9_trucking_co']['language'])? $fields['loc_9_trucking_co']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_9_trucking_co }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Loc 10 Trucking Co', (isset($fields['loc_10_trucking_co']['language'])? $fields['loc_10_trucking_co']['language'] : array())) }}
						</td>
						<td>{{ $row->loc_10_trucking_co }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('External Ship Quote', (isset($fields['external_ship_quote']['language'])? $fields['external_ship_quote']['language'] : array())) }}
						</td>
						<td>{{ $row->external_ship_quote }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('External Ship Trucking Co', (isset($fields['external_ship_trucking_co']['language'])? $fields['external_ship_trucking_co']['language'] : array())) }}
						</td>
						<td>{{ $row->external_ship_trucking_co }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('External Ship Pro', (isset($fields['external_ship_pro']['language'])? $fields['external_ship_pro']['language'] : array())) }}
						</td>
						<td>{{ $row->external_ship_pro }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Freight Company 1', (isset($fields['freight_company_1']['language'])? $fields['freight_company_1']['language'] : array())) }}
						</td>
						<td>{{ $row->freight_company_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Freight Company 2', (isset($fields['freight_company_2']['language'])? $fields['freight_company_2']['language'] : array())) }}
						</td>
						<td>{{ $row->freight_company_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Freight Company 3', (isset($fields['freight_company_3']['language'])? $fields['freight_company_3']['language'] : array())) }}
						</td>
						<td>{{ $row->freight_company_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Freight Company 4', (isset($fields['freight_company_4']['language'])? $fields['freight_company_4']['language'] : array())) }}
						</td>
						<td>{{ $row->freight_company_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Freight Company 5', (isset($fields['freight_company_5']['language'])? $fields['freight_company_5']['language'] : array())) }}
						</td>
						<td>{{ $row->freight_company_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Freight Company 6', (isset($fields['freight_company_6']['language'])? $fields['freight_company_6']['language'] : array())) }}
						</td>
						<td>{{ $row->freight_company_6 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Freight Company 7', (isset($fields['freight_company_7']['language'])? $fields['freight_company_7']['language'] : array())) }}
						</td>
						<td>{{ $row->freight_company_7 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Freight Company 8', (isset($fields['freight_company_8']['language'])? $fields['freight_company_8']['language'] : array())) }}
						</td>
						<td>{{ $row->freight_company_8 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Freight Company 9', (isset($fields['freight_company_9']['language'])? $fields['freight_company_9']['language'] : array())) }}
						</td>
						<td>{{ $row->freight_company_9 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Freight Company 10', (isset($fields['freight_company_10']['language'])? $fields['freight_company_10']['language'] : array())) }}
						</td>
						<td>{{ $row->freight_company_10 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Email Notes', (isset($fields['email_notes']['language'])? $fields['email_notes']['language'] : array())) }}
						</td>
						<td>{{ $row->email_notes }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Status', (isset($fields['status']['language'])? $fields['status']['language'] : array())) }}
						</td>
						<td>{{ $row->status }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Ship Exception 1', (isset($fields['ship_exception_1']['language'])? $fields['ship_exception_1']['language'] : array())) }}
						</td>
						<td>{{ $row->ship_exception_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Ship Exception 2', (isset($fields['ship_exception_2']['language'])? $fields['ship_exception_2']['language'] : array())) }}
						</td>
						<td>{{ $row->ship_exception_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Ship Exception 3', (isset($fields['ship_exception_3']['language'])? $fields['ship_exception_3']['language'] : array())) }}
						</td>
						<td>{{ $row->ship_exception_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Ship Exception 4', (isset($fields['ship_exception_4']['language'])? $fields['ship_exception_4']['language'] : array())) }}
						</td>
						<td>{{ $row->ship_exception_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Ship Exception 5', (isset($fields['ship_exception_5']['language'])? $fields['ship_exception_5']['language'] : array())) }}
						</td>
						<td>{{ $row->ship_exception_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('New Ship Date 1', (isset($fields['new_ship_date_1']['language'])? $fields['new_ship_date_1']['language'] : array())) }}
						</td>
						<td>{{ $row->new_ship_date_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('New Ship Date 2', (isset($fields['new_ship_date_2']['language'])? $fields['new_ship_date_2']['language'] : array())) }}
						</td>
						<td>{{ $row->new_ship_date_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('New Ship Date 3', (isset($fields['new_ship_date_3']['language'])? $fields['new_ship_date_3']['language'] : array())) }}
						</td>
						<td>{{ $row->new_ship_date_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('New Ship Date 4', (isset($fields['new_ship_date_4']['language'])? $fields['new_ship_date_4']['language'] : array())) }}
						</td>
						<td>{{ $row->new_ship_date_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('New Ship Date 5', (isset($fields['new_ship_date_5']['language'])? $fields['new_ship_date_5']['language'] : array())) }}
						</td>
						<td>{{ $row->new_ship_date_5 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('New Ship Reason 1', (isset($fields['new_ship_reason_1']['language'])? $fields['new_ship_reason_1']['language'] : array())) }}
						</td>
						<td>{{ $row->new_ship_reason_1 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('New Ship Reason 2', (isset($fields['new_ship_reason_2']['language'])? $fields['new_ship_reason_2']['language'] : array())) }}
						</td>
						<td>{{ $row->new_ship_reason_2 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('New Ship Reason 3', (isset($fields['new_ship_reason_3']['language'])? $fields['new_ship_reason_3']['language'] : array())) }}
						</td>
						<td>{{ $row->new_ship_reason_3 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('New Ship Reason 4', (isset($fields['new_ship_reason_4']['language'])? $fields['new_ship_reason_4']['language'] : array())) }}
						</td>
						<td>{{ $row->new_ship_reason_4 }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('New Ship Reason 5', (isset($fields['new_ship_reason_5']['language'])? $fields['new_ship_reason_5']['language'] : array())) }}
						</td>
						<td>{{ $row->new_ship_reason_5 }} </td>
						
					</tr>
				
			</tbody>	
		</table>  
			
		 	

@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

<script>
$(document).ready(function(){

});
</script>	