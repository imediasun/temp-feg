
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'managefreightquoters/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'managefreightquotersFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Manage Freight Quoters</legend>
				
				  <div class="form-group  " >
					<label for="Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Submitted" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Submitted', (isset($fields['date_submitted']['language'])? $fields['date_submitted']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('date_submitted', $row['date_submitted'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Booked" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Booked', (isset($fields['date_booked']['language'])? $fields['date_booked']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('date_booked', $row['date_booked'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Paid" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Paid', (isset($fields['date_paid']['language'])? $fields['date_paid']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('date_paid', $row['date_paid'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Vend From" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Vend From', (isset($fields['vend_from']['language'])? $fields['vend_from']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('vend_from', $row['vend_from'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Vend To" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Vend To', (isset($fields['vend_to']['language'])? $fields['vend_to']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('vend_to', $row['vend_to'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc From" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc From', (isset($fields['loc_from']['language'])? $fields['loc_from']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_from', $row['loc_from'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc To 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc To 1', (isset($fields['loc_to_1']['language'])? $fields['loc_to_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_to_1', $row['loc_to_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc To 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc To 2', (isset($fields['loc_to_2']['language'])? $fields['loc_to_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_to_2', $row['loc_to_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc To 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc To 3', (isset($fields['loc_to_3']['language'])? $fields['loc_to_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_to_3', $row['loc_to_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc To 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc To 4', (isset($fields['loc_to_4']['language'])? $fields['loc_to_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_to_4', $row['loc_to_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc To 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc To 5', (isset($fields['loc_to_5']['language'])? $fields['loc_to_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_to_5', $row['loc_to_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc To 6" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc To 6', (isset($fields['loc_to_6']['language'])? $fields['loc_to_6']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_to_6', $row['loc_to_6'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc To 7" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc To 7', (isset($fields['loc_to_7']['language'])? $fields['loc_to_7']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_to_7', $row['loc_to_7'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc To 8" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc To 8', (isset($fields['loc_to_8']['language'])? $fields['loc_to_8']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_to_8', $row['loc_to_8'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc To 9" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc To 9', (isset($fields['loc_to_9']['language'])? $fields['loc_to_9']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_to_9', $row['loc_to_9'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc To 10" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc To 10', (isset($fields['loc_to_10']['language'])? $fields['loc_to_10']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_to_10', $row['loc_to_10'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="From Add Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('From Add Name', (isset($fields['from_add_name']['language'])? $fields['from_add_name']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('from_add_name', $row['from_add_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="From Add Street" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('From Add Street', (isset($fields['from_add_street']['language'])? $fields['from_add_street']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('from_add_street', $row['from_add_street'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="From Add City" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('From Add City', (isset($fields['from_add_city']['language'])? $fields['from_add_city']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('from_add_city', $row['from_add_city'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="From Add State" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('From Add State', (isset($fields['from_add_state']['language'])? $fields['from_add_state']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('from_add_state', $row['from_add_state'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="From Add Zip" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('From Add Zip', (isset($fields['from_add_zip']['language'])? $fields['from_add_zip']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('from_add_zip', $row['from_add_zip'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="From Contact Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('From Contact Name', (isset($fields['from_contact_name']['language'])? $fields['from_contact_name']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('from_contact_name', $row['from_contact_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="From Contact Email" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('From Contact Email', (isset($fields['from_contact_email']['language'])? $fields['from_contact_email']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('from_contact_email', $row['from_contact_email'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="From Contact Phone" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('From Contact Phone', (isset($fields['from_contact_phone']['language'])? $fields['from_contact_phone']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('from_contact_phone', $row['from_contact_phone'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="From Loading Info" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('From Loading Info', (isset($fields['from_loading_info']['language'])? $fields['from_loading_info']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('from_loading_info', $row['from_loading_info'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="To Add Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('To Add Name', (isset($fields['to_add_name']['language'])? $fields['to_add_name']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('to_add_name', $row['to_add_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="To Add Street" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('To Add Street', (isset($fields['to_add_street']['language'])? $fields['to_add_street']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('to_add_street', $row['to_add_street'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="To Add City" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('To Add City', (isset($fields['to_add_city']['language'])? $fields['to_add_city']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('to_add_city', $row['to_add_city'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="To Add State" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('To Add State', (isset($fields['to_add_state']['language'])? $fields['to_add_state']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('to_add_state', $row['to_add_state'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="To Add Zip" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('To Add Zip', (isset($fields['to_add_zip']['language'])? $fields['to_add_zip']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('to_add_zip', $row['to_add_zip'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="To Contact Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('To Contact Name', (isset($fields['to_contact_name']['language'])? $fields['to_contact_name']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('to_contact_name', $row['to_contact_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="To Contact Email" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('To Contact Email', (isset($fields['to_contact_email']['language'])? $fields['to_contact_email']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('to_contact_email', $row['to_contact_email'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="To Contact Phone" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('To Contact Phone', (isset($fields['to_contact_phone']['language'])? $fields['to_contact_phone']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('to_contact_phone', $row['to_contact_phone'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="To Loading Info" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('To Loading Info', (isset($fields['to_loading_info']['language'])? $fields['to_loading_info']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('to_loading_info', $row['to_loading_info'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Description 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Description 1', (isset($fields['description_1']['language'])? $fields['description_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('description_1', $row['description_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Dimensions 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Dimensions 1', (isset($fields['dimensions_1']['language'])? $fields['dimensions_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('dimensions_1', $row['dimensions_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Notes" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('notes', $row['notes'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Description 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Description 2', (isset($fields['description_2']['language'])? $fields['description_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('description_2', $row['description_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Dimensions 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Dimensions 2', (isset($fields['dimensions_2']['language'])? $fields['dimensions_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('dimensions_2', $row['dimensions_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Description 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Description 3', (isset($fields['description_3']['language'])? $fields['description_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('description_3', $row['description_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Dimensions 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Dimensions 3', (isset($fields['dimensions_3']['language'])? $fields['dimensions_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('dimensions_3', $row['dimensions_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Description 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Description 4', (isset($fields['description_4']['language'])? $fields['description_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('description_4', $row['description_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Dimensions 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Dimensions 4', (isset($fields['dimensions_4']['language'])? $fields['dimensions_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('dimensions_4', $row['dimensions_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Description 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Description 5', (isset($fields['description_5']['language'])? $fields['description_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('description_5', $row['description_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Dimensions 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Dimensions 5', (isset($fields['dimensions_5']['language'])? $fields['dimensions_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('dimensions_5', $row['dimensions_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Num Games Per Destination" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Num Games Per Destination', (isset($fields['num_games_per_destination']['language'])? $fields['num_games_per_destination']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('num_games_per_destination', $row['num_games_per_destination'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 1 Game 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 1 Game 1', (isset($fields['loc_1_game_1']['language'])? $fields['loc_1_game_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_1_game_1', $row['loc_1_game_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 1 Game 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 1 Game 2', (isset($fields['loc_1_game_2']['language'])? $fields['loc_1_game_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_1_game_2', $row['loc_1_game_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 1 Game 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 1 Game 3', (isset($fields['loc_1_game_3']['language'])? $fields['loc_1_game_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_1_game_3', $row['loc_1_game_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 1 Game 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 1 Game 4', (isset($fields['loc_1_game_4']['language'])? $fields['loc_1_game_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_1_game_4', $row['loc_1_game_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 1 Game 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 1 Game 5', (isset($fields['loc_1_game_5']['language'])? $fields['loc_1_game_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_1_game_5', $row['loc_1_game_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 2 Game 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 2 Game 1', (isset($fields['loc_2_game_1']['language'])? $fields['loc_2_game_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_2_game_1', $row['loc_2_game_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 2 Game 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 2 Game 2', (isset($fields['loc_2_game_2']['language'])? $fields['loc_2_game_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_2_game_2', $row['loc_2_game_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 2 Game 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 2 Game 3', (isset($fields['loc_2_game_3']['language'])? $fields['loc_2_game_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_2_game_3', $row['loc_2_game_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 2 Game 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 2 Game 4', (isset($fields['loc_2_game_4']['language'])? $fields['loc_2_game_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_2_game_4', $row['loc_2_game_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 2 Game 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 2 Game 5', (isset($fields['loc_2_game_5']['language'])? $fields['loc_2_game_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_2_game_5', $row['loc_2_game_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 3 Game 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 3 Game 1', (isset($fields['loc_3_game_1']['language'])? $fields['loc_3_game_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_3_game_1', $row['loc_3_game_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 3 Game 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 3 Game 2', (isset($fields['loc_3_game_2']['language'])? $fields['loc_3_game_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_3_game_2', $row['loc_3_game_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 3 Game 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 3 Game 3', (isset($fields['loc_3_game_3']['language'])? $fields['loc_3_game_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_3_game_3', $row['loc_3_game_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 3 Game 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 3 Game 4', (isset($fields['loc_3_game_4']['language'])? $fields['loc_3_game_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_3_game_4', $row['loc_3_game_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 3 Game 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 3 Game 5', (isset($fields['loc_3_game_5']['language'])? $fields['loc_3_game_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_3_game_5', $row['loc_3_game_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 4 Game 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 4 Game 1', (isset($fields['loc_4_game_1']['language'])? $fields['loc_4_game_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_4_game_1', $row['loc_4_game_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 4 Game 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 4 Game 2', (isset($fields['loc_4_game_2']['language'])? $fields['loc_4_game_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_4_game_2', $row['loc_4_game_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 4 Game 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 4 Game 3', (isset($fields['loc_4_game_3']['language'])? $fields['loc_4_game_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_4_game_3', $row['loc_4_game_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 4 Game 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 4 Game 4', (isset($fields['loc_4_game_4']['language'])? $fields['loc_4_game_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_4_game_4', $row['loc_4_game_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 4 Game 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 4 Game 5', (isset($fields['loc_4_game_5']['language'])? $fields['loc_4_game_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_4_game_5', $row['loc_4_game_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 5 Game 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 5 Game 1', (isset($fields['loc_5_game_1']['language'])? $fields['loc_5_game_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_5_game_1', $row['loc_5_game_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 5 Game 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 5 Game 2', (isset($fields['loc_5_game_2']['language'])? $fields['loc_5_game_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_5_game_2', $row['loc_5_game_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 5 Game 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 5 Game 3', (isset($fields['loc_5_game_3']['language'])? $fields['loc_5_game_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_5_game_3', $row['loc_5_game_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 5 Game 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 5 Game 4', (isset($fields['loc_5_game_4']['language'])? $fields['loc_5_game_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_5_game_4', $row['loc_5_game_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 5 Game 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 5 Game 5', (isset($fields['loc_5_game_5']['language'])? $fields['loc_5_game_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_5_game_5', $row['loc_5_game_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 6 Game 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 6 Game 1', (isset($fields['loc_6_game_1']['language'])? $fields['loc_6_game_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_6_game_1', $row['loc_6_game_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 6 Game 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 6 Game 2', (isset($fields['loc_6_game_2']['language'])? $fields['loc_6_game_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_6_game_2', $row['loc_6_game_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 6 Game 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 6 Game 3', (isset($fields['loc_6_game_3']['language'])? $fields['loc_6_game_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_6_game_3', $row['loc_6_game_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 6 Game 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 6 Game 4', (isset($fields['loc_6_game_4']['language'])? $fields['loc_6_game_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_6_game_4', $row['loc_6_game_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 6 Game 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 6 Game 5', (isset($fields['loc_6_game_5']['language'])? $fields['loc_6_game_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_6_game_5', $row['loc_6_game_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 7 Game 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 7 Game 1', (isset($fields['loc_7_game_1']['language'])? $fields['loc_7_game_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_7_game_1', $row['loc_7_game_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 7 Game 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 7 Game 2', (isset($fields['loc_7_game_2']['language'])? $fields['loc_7_game_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_7_game_2', $row['loc_7_game_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 7 Game 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 7 Game 3', (isset($fields['loc_7_game_3']['language'])? $fields['loc_7_game_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_7_game_3', $row['loc_7_game_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 7 Game 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 7 Game 4', (isset($fields['loc_7_game_4']['language'])? $fields['loc_7_game_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_7_game_4', $row['loc_7_game_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 7 Game 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 7 Game 5', (isset($fields['loc_7_game_5']['language'])? $fields['loc_7_game_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_7_game_5', $row['loc_7_game_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 8 Game 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 8 Game 1', (isset($fields['loc_8_game_1']['language'])? $fields['loc_8_game_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_8_game_1', $row['loc_8_game_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 8 Game 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 8 Game 2', (isset($fields['loc_8_game_2']['language'])? $fields['loc_8_game_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_8_game_2', $row['loc_8_game_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 8 Game 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 8 Game 3', (isset($fields['loc_8_game_3']['language'])? $fields['loc_8_game_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_8_game_3', $row['loc_8_game_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 8 Game 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 8 Game 4', (isset($fields['loc_8_game_4']['language'])? $fields['loc_8_game_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_8_game_4', $row['loc_8_game_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 8 Game 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 8 Game 5', (isset($fields['loc_8_game_5']['language'])? $fields['loc_8_game_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_8_game_5', $row['loc_8_game_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 9 Game 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 9 Game 1', (isset($fields['loc_9_game_1']['language'])? $fields['loc_9_game_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_9_game_1', $row['loc_9_game_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 9 Game 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 9 Game 2', (isset($fields['loc_9_game_2']['language'])? $fields['loc_9_game_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_9_game_2', $row['loc_9_game_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 9 Game 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 9 Game 3', (isset($fields['loc_9_game_3']['language'])? $fields['loc_9_game_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_9_game_3', $row['loc_9_game_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 9 Game 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 9 Game 4', (isset($fields['loc_9_game_4']['language'])? $fields['loc_9_game_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_9_game_4', $row['loc_9_game_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 9 Game 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 9 Game 5', (isset($fields['loc_9_game_5']['language'])? $fields['loc_9_game_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_9_game_5', $row['loc_9_game_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 10 Game 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 10 Game 1', (isset($fields['loc_10_game_1']['language'])? $fields['loc_10_game_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_10_game_1', $row['loc_10_game_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 10 Game 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 10 Game 2', (isset($fields['loc_10_game_2']['language'])? $fields['loc_10_game_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_10_game_2', $row['loc_10_game_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 10 Game 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 10 Game 3', (isset($fields['loc_10_game_3']['language'])? $fields['loc_10_game_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_10_game_3', $row['loc_10_game_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 10 Game 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 10 Game 4', (isset($fields['loc_10_game_4']['language'])? $fields['loc_10_game_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_10_game_4', $row['loc_10_game_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 10 Game 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 10 Game 5', (isset($fields['loc_10_game_5']['language'])? $fields['loc_10_game_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_10_game_5', $row['loc_10_game_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 1 Pro" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 1 Pro', (isset($fields['loc_1_pro']['language'])? $fields['loc_1_pro']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_1_pro', $row['loc_1_pro'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 2 Pro" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 2 Pro', (isset($fields['loc_2_pro']['language'])? $fields['loc_2_pro']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_2_pro', $row['loc_2_pro'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 3 Pro" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 3 Pro', (isset($fields['loc_3_pro']['language'])? $fields['loc_3_pro']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_3_pro', $row['loc_3_pro'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 4 Pro" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 4 Pro', (isset($fields['loc_4_pro']['language'])? $fields['loc_4_pro']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_4_pro', $row['loc_4_pro'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 5 Pro" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 5 Pro', (isset($fields['loc_5_pro']['language'])? $fields['loc_5_pro']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_5_pro', $row['loc_5_pro'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 6 Pro" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 6 Pro', (isset($fields['loc_6_pro']['language'])? $fields['loc_6_pro']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_6_pro', $row['loc_6_pro'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 7 Pro" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 7 Pro', (isset($fields['loc_7_pro']['language'])? $fields['loc_7_pro']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_7_pro', $row['loc_7_pro'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 8 Pro" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 8 Pro', (isset($fields['loc_8_pro']['language'])? $fields['loc_8_pro']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_8_pro', $row['loc_8_pro'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 9 Pro" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 9 Pro', (isset($fields['loc_9_pro']['language'])? $fields['loc_9_pro']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_9_pro', $row['loc_9_pro'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 10 Pro" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 10 Pro', (isset($fields['loc_10_pro']['language'])? $fields['loc_10_pro']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_10_pro', $row['loc_10_pro'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 1 Quote" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 1 Quote', (isset($fields['loc_1_quote']['language'])? $fields['loc_1_quote']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_1_quote', $row['loc_1_quote'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 2 Quote" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 2 Quote', (isset($fields['loc_2_quote']['language'])? $fields['loc_2_quote']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_2_quote', $row['loc_2_quote'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 3 Quote" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 3 Quote', (isset($fields['loc_3_quote']['language'])? $fields['loc_3_quote']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_3_quote', $row['loc_3_quote'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 4 Quote" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 4 Quote', (isset($fields['loc_4_quote']['language'])? $fields['loc_4_quote']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_4_quote', $row['loc_4_quote'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 5 Quote" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 5 Quote', (isset($fields['loc_5_quote']['language'])? $fields['loc_5_quote']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_5_quote', $row['loc_5_quote'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 6 Quote" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 6 Quote', (isset($fields['loc_6_quote']['language'])? $fields['loc_6_quote']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_6_quote', $row['loc_6_quote'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 7 Quote" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 7 Quote', (isset($fields['loc_7_quote']['language'])? $fields['loc_7_quote']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_7_quote', $row['loc_7_quote'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 8 Quote" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 8 Quote', (isset($fields['loc_8_quote']['language'])? $fields['loc_8_quote']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_8_quote', $row['loc_8_quote'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 9 Quote" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 9 Quote', (isset($fields['loc_9_quote']['language'])? $fields['loc_9_quote']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_9_quote', $row['loc_9_quote'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 10 Quote" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 10 Quote', (isset($fields['loc_10_quote']['language'])? $fields['loc_10_quote']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_10_quote', $row['loc_10_quote'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 1 Trucking Co" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 1 Trucking Co', (isset($fields['loc_1_trucking_co']['language'])? $fields['loc_1_trucking_co']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_1_trucking_co', $row['loc_1_trucking_co'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 2 Trucking Co" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 2 Trucking Co', (isset($fields['loc_2_trucking_co']['language'])? $fields['loc_2_trucking_co']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_2_trucking_co', $row['loc_2_trucking_co'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 3 Trucking Co" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 3 Trucking Co', (isset($fields['loc_3_trucking_co']['language'])? $fields['loc_3_trucking_co']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_3_trucking_co', $row['loc_3_trucking_co'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 4 Trucking Co" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 4 Trucking Co', (isset($fields['loc_4_trucking_co']['language'])? $fields['loc_4_trucking_co']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_4_trucking_co', $row['loc_4_trucking_co'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 5 Trucking Co" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 5 Trucking Co', (isset($fields['loc_5_trucking_co']['language'])? $fields['loc_5_trucking_co']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_5_trucking_co', $row['loc_5_trucking_co'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 6 Trucking Co" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 6 Trucking Co', (isset($fields['loc_6_trucking_co']['language'])? $fields['loc_6_trucking_co']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_6_trucking_co', $row['loc_6_trucking_co'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 7 Trucking Co" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 7 Trucking Co', (isset($fields['loc_7_trucking_co']['language'])? $fields['loc_7_trucking_co']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_7_trucking_co', $row['loc_7_trucking_co'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 8 Trucking Co" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 8 Trucking Co', (isset($fields['loc_8_trucking_co']['language'])? $fields['loc_8_trucking_co']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_8_trucking_co', $row['loc_8_trucking_co'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 9 Trucking Co" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 9 Trucking Co', (isset($fields['loc_9_trucking_co']['language'])? $fields['loc_9_trucking_co']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_9_trucking_co', $row['loc_9_trucking_co'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Loc 10 Trucking Co" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Loc 10 Trucking Co', (isset($fields['loc_10_trucking_co']['language'])? $fields['loc_10_trucking_co']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('loc_10_trucking_co', $row['loc_10_trucking_co'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="External Ship Quote" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('External Ship Quote', (isset($fields['external_ship_quote']['language'])? $fields['external_ship_quote']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('external_ship_quote', $row['external_ship_quote'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="External Ship Trucking Co" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('External Ship Trucking Co', (isset($fields['external_ship_trucking_co']['language'])? $fields['external_ship_trucking_co']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('external_ship_trucking_co', $row['external_ship_trucking_co'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="External Ship Pro" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('External Ship Pro', (isset($fields['external_ship_pro']['language'])? $fields['external_ship_pro']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('external_ship_pro', $row['external_ship_pro'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Company 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Company 1', (isset($fields['freight_company_1']['language'])? $fields['freight_company_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('freight_company_1', $row['freight_company_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Company 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Company 2', (isset($fields['freight_company_2']['language'])? $fields['freight_company_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('freight_company_2', $row['freight_company_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Company 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Company 3', (isset($fields['freight_company_3']['language'])? $fields['freight_company_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('freight_company_3', $row['freight_company_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Company 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Company 4', (isset($fields['freight_company_4']['language'])? $fields['freight_company_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('freight_company_4', $row['freight_company_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Company 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Company 5', (isset($fields['freight_company_5']['language'])? $fields['freight_company_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('freight_company_5', $row['freight_company_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Company 6" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Company 6', (isset($fields['freight_company_6']['language'])? $fields['freight_company_6']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('freight_company_6', $row['freight_company_6'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Company 7" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Company 7', (isset($fields['freight_company_7']['language'])? $fields['freight_company_7']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('freight_company_7', $row['freight_company_7'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Company 8" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Company 8', (isset($fields['freight_company_8']['language'])? $fields['freight_company_8']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('freight_company_8', $row['freight_company_8'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Company 9" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Company 9', (isset($fields['freight_company_9']['language'])? $fields['freight_company_9']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('freight_company_9', $row['freight_company_9'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Company 10" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Company 10', (isset($fields['freight_company_10']['language'])? $fields['freight_company_10']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('freight_company_10', $row['freight_company_10'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Email Notes" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Email Notes', (isset($fields['email_notes']['language'])? $fields['email_notes']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='email_notes' rows='5' id='email_notes' class='form-control '
				           >{{ $row['email_notes'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Status" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Status', (isset($fields['status']['language'])? $fields['status']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('status', $row['status'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Ship Exception 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Ship Exception 1', (isset($fields['ship_exception_1']['language'])? $fields['ship_exception_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('ship_exception_1', $row['ship_exception_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Ship Exception 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Ship Exception 2', (isset($fields['ship_exception_2']['language'])? $fields['ship_exception_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('ship_exception_2', $row['ship_exception_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Ship Exception 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Ship Exception 3', (isset($fields['ship_exception_3']['language'])? $fields['ship_exception_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('ship_exception_3', $row['ship_exception_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Ship Exception 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Ship Exception 4', (isset($fields['ship_exception_4']['language'])? $fields['ship_exception_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('ship_exception_4', $row['ship_exception_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Ship Exception 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Ship Exception 5', (isset($fields['ship_exception_5']['language'])? $fields['ship_exception_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('ship_exception_5', $row['ship_exception_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="New Ship Date 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('New Ship Date 1', (isset($fields['new_ship_date_1']['language'])? $fields['new_ship_date_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('new_ship_date_1', $row['new_ship_date_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="New Ship Date 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('New Ship Date 2', (isset($fields['new_ship_date_2']['language'])? $fields['new_ship_date_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('new_ship_date_2', $row['new_ship_date_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="New Ship Date 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('New Ship Date 3', (isset($fields['new_ship_date_3']['language'])? $fields['new_ship_date_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('new_ship_date_3', $row['new_ship_date_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="New Ship Date 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('New Ship Date 4', (isset($fields['new_ship_date_4']['language'])? $fields['new_ship_date_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('new_ship_date_4', $row['new_ship_date_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="New Ship Date 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('New Ship Date 5', (isset($fields['new_ship_date_5']['language'])? $fields['new_ship_date_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('new_ship_date_5', $row['new_ship_date_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="New Ship Reason 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('New Ship Reason 1', (isset($fields['new_ship_reason_1']['language'])? $fields['new_ship_reason_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='new_ship_reason_1' rows='5' id='new_ship_reason_1' class='form-control '
				           >{{ $row['new_ship_reason_1'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="New Ship Reason 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('New Ship Reason 2', (isset($fields['new_ship_reason_2']['language'])? $fields['new_ship_reason_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='new_ship_reason_2' rows='5' id='new_ship_reason_2' class='form-control '
				           >{{ $row['new_ship_reason_2'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="New Ship Reason 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('New Ship Reason 3', (isset($fields['new_ship_reason_3']['language'])? $fields['new_ship_reason_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='new_ship_reason_3' rows='5' id='new_ship_reason_3' class='form-control '
				           >{{ $row['new_ship_reason_3'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="New Ship Reason 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('New Ship Reason 4', (isset($fields['new_ship_reason_4']['language'])? $fields['new_ship_reason_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='new_ship_reason_4' rows='5' id='new_ship_reason_4' class='form-control '
				           >{{ $row['new_ship_reason_4'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="New Ship Reason 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('New Ship Reason 5', (isset($fields['new_ship_reason_5']['language'])? $fields['new_ship_reason_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='new_ship_reason_5' rows='5' id='new_ship_reason_5' class='form-control '
				           >{{ $row['new_ship_reason_5'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> </fieldset>
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
	var form = $('#managefreightquotersFormAjax'); 
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