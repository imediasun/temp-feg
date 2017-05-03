
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'mylocationgame/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'mylocationgameFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> mylocationgame</legend>
				
				  <div class="form-group  " >
					<label for="Asset Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Asset Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for=" Game Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang(' Game Name', (isset($fields['game_name']['language'])? $fields['game_name']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='game_name' rows='5' id='game_name' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Prev Game Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Prev Game Name', (isset($fields['prev_game_name']['language'])? $fields['prev_game_name']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('prev_game_name', $row['prev_game_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Game Type " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Type ', (isset($fields['game_type_id']['language'])? $fields['game_type_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='game_type_id' rows='5' id='game_type_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Version " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Version ', (isset($fields['version_id']['language'])? $fields['version_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='version_id' rows='5' id='version_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Price Per Play" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Price Per Play', (isset($fields['price_per_play']['language'])? $fields['price_per_play']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('price_per_play', $row['price_per_play'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Players" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Players', (isset($fields['players']['language'])? $fields['players']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('players', $row['players'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Monitor Size" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Monitor Size', (isset($fields['monitor_size']['language'])? $fields['monitor_size']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('monitor_size', $row['monitor_size'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Dba" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Dba', (isset($fields['dba']['language'])? $fields['dba']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
					<?php $dba = explode(',',$row['dba']);
					$dba_opt = array( '0' => 'No' ,  '1' => 'Yes' , ); ?>
					<select name='dba' rows='5'   class='select2 '  > 
						<?php 
						foreach($dba_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['dba'] == $key ? " selected='selected' " : '' ).">$val</option>";
						}						
						?></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Sacoa" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Sacoa', (isset($fields['sacoa']['language'])? $fields['sacoa']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
					<?php $sacoa = explode(',',$row['sacoa']);
					$sacoa_opt = array( '0' => 'No' ,  '1' => 'Yes' , ); ?>
					<select name='sacoa' rows='5'   class='select2 '  > 
						<?php 
						foreach($sacoa_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['sacoa'] == $key ? " selected='selected' " : '' ).">$val</option>";
						}						
						?></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Embed" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Embed', (isset($fields['embed']['language'])? $fields['embed']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
					<?php $embed = explode(',',$row['embed']);
					$embed_opt = array( '0' => 'No' ,  '1' => 'Yes' , ); ?>
					<select name='embed' rows='5'   class='select2 '  > 
						<?php 
						foreach($embed_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['embed'] == $key ? " selected='selected' " : '' ).">$val</option>";
						}						
						?></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Notes" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='notes' rows='5' id='notes' class='form-control '
				           >{{ $row['notes'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Location " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Location ', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='location_id[]' multiple rows='5' id='location_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Manufacturer" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Manufacturer', (isset($fields['mfg_id']['language'])? $fields['mfg_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='mfg_id[]' multiple rows='5' id='mfg_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Source" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Source', (isset($fields['source']['language'])? $fields['source']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('source', $row['source'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Serial" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Serial', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('serial', $row['serial'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date In Service" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date In Service', (isset($fields['date_in_service']['language'])? $fields['date_in_service']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('date_in_service', $row['date_in_service'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Status " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Status ', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='status_id' rows='5' id='status_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Intended First Location" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Intended First Location', (isset($fields['intended_first_location']['language'])? $fields['intended_first_location']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('intended_first_location', $row['intended_first_location'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Ship Delay Reason" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Ship Delay Reason', (isset($fields['ship_delay_reason']['language'])? $fields['ship_delay_reason']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='ship_delay_reason' rows='5' id='ship_delay_reason' class='form-control '
				           >{{ $row['ship_delay_reason'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Shipped" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Shipped', (isset($fields['date_shipped']['language'])? $fields['date_shipped']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('date_shipped', $row['date_shipped'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Order " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Order ', (isset($fields['freight_order_id']['language'])? $fields['freight_order_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('freight_order_id', $row['freight_order_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Last Move" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Last Move', (isset($fields['date_last_move']['language'])? $fields['date_last_move']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('date_last_move', $row['date_last_move'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Edited By" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Edited By', (isset($fields['last_edited_by']['language'])? $fields['last_edited_by']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='last_edited_by' rows='5' id='last_edited_by' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Edited On" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Edited On', (isset($fields['last_edited_on']['language'])? $fields['last_edited_on']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('last_edited_on', $row['last_edited_on'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Prev Location " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Prev Location ', (isset($fields['prev_location_id']['language'])? $fields['prev_location_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('prev_location_id', $row['prev_location_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="For Sale" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('For Sale', (isset($fields['for_sale']['language'])? $fields['for_sale']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $for_sale = explode(",",$row['for_sale']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='for_sale[]' value ='1'   class='' 
					@if(in_array('1',$for_sale))checked @endif
					 /> 1 </label> 
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='for_sale[]' value ='0'   class='' 
					@if(in_array('0',$for_sale))checked @endif
					 /> 0 </label> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Sale Price" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Sale Price', (isset($fields['sale_price']['language'])? $fields['sale_price']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('sale_price', $row['sale_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Sale Pending" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Sale Pending', (isset($fields['sale_pending']['language'])? $fields['sale_pending']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $sale_pending = explode(",",$row['sale_pending']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='sale_pending[]' value ='1'   class='' 
					@if(in_array('1',$sale_pending))checked @endif
					 /> 1 </label> 
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='sale_pending[]' value ='0'   class='' 
					@if(in_array('0',$sale_pending))checked @endif
					 /> 0 </label> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Sold" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Sold', (isset($fields['sold']['language'])? $fields['sold']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='sold' rows='5' id='sold' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Sold" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Sold', (isset($fields['date_sold']['language'])? $fields['date_sold']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('date_sold', $row['date_sold'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Sold To" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Sold To', (isset($fields['sold_to']['language'])? $fields['sold_to']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('sold_to', $row['sold_to'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Game Move " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Move ', (isset($fields['game_move_id']['language'])? $fields['game_move_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_move_id', $row['game_move_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Game Service " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Service ', (isset($fields['game_service_id']['language'])? $fields['game_service_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_service_id', $row['game_service_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Game Title " class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Title ', (isset($fields['game_title_id']['language'])? $fields['game_title_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='game_title_id[]' multiple rows='5' id='game_title_id' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Version" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Version', (isset($fields['version']['language'])? $fields['version']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('version', $row['version'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Num Prize Meters" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Num Prize Meters', (isset($fields['num_prize_meters']['language'])? $fields['num_prize_meters']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='num_prize_meters' rows='5' id='num_prize_meters' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Num Prizes" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Num Prizes', (isset($fields['num_prizes']['language'])? $fields['num_prizes']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='num_prizes' rows='5' id='num_prizes' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Meter Date" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Meter Date', (isset($fields['last_meter_date']['language'])? $fields['last_meter_date']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('last_meter_date', $row['last_meter_date'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Not Debit" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Not Debit', (isset($fields['not_debit']['language'])? $fields['not_debit']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <?php $not_debit = explode(",",$row['not_debit']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='not_debit[]' value ='1'   class='' 
					@if(in_array('1',$not_debit))checked @endif
					 /> 1 </label> 
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='not_debit[]' value ='0'   class='' 
					@if(in_array('0',$not_debit))checked @endif
					 /> 0 </label> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Not Debit Reason" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Not Debit Reason', (isset($fields['not_debit_reason']['language'])? $fields['not_debit_reason']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('not_debit_reason', $row['not_debit_reason'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Linked To Game" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Linked To Game', (isset($fields['linked_to_game']['language'])? $fields['linked_to_game']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
					<?php $linked_to_game = explode(',',$row['linked_to_game']);
					$linked_to_game_opt = array( '1' => 'Yes' ,  '0' => 'No' , ); ?>
					<select name='linked_to_game' rows='5'   class='select2 '  > 
						<?php 
						foreach($linked_to_game_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['linked_to_game'] == $key ? " selected='selected' " : '' ).">$val</option>";
						}						
						?></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Test Piece" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Test Piece', (isset($fields['test_piece']['language'])? $fields['test_piece']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='test_piece' rows='5' id='test_piece' class='select2 '   ></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Image" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Image', (isset($fields['img']['language'])? $fields['img']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='img' rows='5' id='img' class='form-control '
				           >{{ $row['img'] }}</textarea>
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
	
        $("#game_name").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game:game_name:game_name') }}",
        {  selected_value : '{{ $row["game_name"] }}' });
        
        $("#game_type_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_type:id:game_type') }}",
        {  selected_value : '{{ $row["game_type_id"] }}' });
        
        $("#version_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_version:id:version') }}",
        {  selected_value : '{{ $row["version_id"] }}' });
        
        $("#location_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=location:id:id|location_name_short') }}",
        {  selected_value : '{{ $row["location_id"] }}' });
        
        $("#mfg_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=vendor:id:vendor_name') }}&search=hide+%3D+0+AND+status+%3D+1",
        {  selected_value : '{{ $row["mfg_id"] }}' });
        
        $("#status_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_status:id:game_status') }}",
        {  selected_value : '{{ $row["status_id"] }}' });
        
        $("#last_edited_by").jCombo("{{ URL::to('mylocationgame/comboselect?filter=users:id:username') }}",
        {  selected_value : '{{ $row["last_edited_by"] }}' });
        
        $("#sold").jCombo("{{ URL::to('mylocationgame/comboselect?filter=yes_no:id:yesno') }}",
        {  selected_value : '{{ $row["sold"] }}' });
        
        $("#game_title_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_title:id:game_title') }}",
        {  selected_value : '{{ $row["game_title_id"] }}' });
        
        $("#num_prize_meters").jCombo("{{ URL::to('mylocationgame/comboselect?filter=yes_no:id:yesno') }}",
        {  selected_value : '{{ $row["num_prize_meters"] }}' });
        
        $("#num_prizes").jCombo("{{ URL::to('mylocationgame/comboselect?filter=yes_no:id:yesno') }}",
        {  selected_value : '{{ $row["num_prizes"] }}' });
        
        $("#test_piece").jCombo("{{ URL::to('mylocationgame/comboselect?filter=yes_no:id:yesno') }}",
        {  selected_value : '{{ $row["test_piece"] }}' });
         
	
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	renderDropdown($(".select2, .select3, .select4, .select5"), { width:"100%"});
	$('.date').datepicker({format:'mm/dd/yyyy',autoclose:true})
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
	var form = $('#mylocationgameFormAjax'); 
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