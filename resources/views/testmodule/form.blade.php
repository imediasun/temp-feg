
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'testmodule/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'testmoduleFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> testModule</legend>
				
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
					<label for="Game Name" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Name', (isset($fields['game_name']['language'])? $fields['game_name']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_name', $row['game_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					<label for="Game Type Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Type Id', (isset($fields['game_type_id']['language'])? $fields['game_type_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_type_id', $row['game_type_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Version Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Version Id', (isset($fields['version_id']['language'])? $fields['version_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('version_id', $row['version_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					  {!! Form::text('dba', $row['dba'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Sacoa" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Sacoa', (isset($fields['sacoa']['language'])? $fields['sacoa']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('sacoa', $row['sacoa'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Embed" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Embed', (isset($fields['embed']['language'])? $fields['embed']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('embed', $row['embed'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Rfid" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Rfid', (isset($fields['rfid']['language'])? $fields['rfid']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('rfid', $row['rfid'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					<label for="Location Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Location Id', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('location_id', $row['location_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Mfg Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Mfg Id', (isset($fields['mfg_id']['language'])? $fields['mfg_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('mfg_id', $row['mfg_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					  {!! Form::text('date_in_service', $row['date_in_service'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Status Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Status Id', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('status_id', $row['status_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Game Setup Status Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Setup Status Id', (isset($fields['game_setup_status_id']['language'])? $fields['game_setup_status_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_setup_status_id', $row['game_setup_status_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					  {!! Form::text('date_shipped', $row['date_shipped'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Freight Order Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Freight Order Id', (isset($fields['freight_order_id']['language'])? $fields['freight_order_id']['language'] : array())) !!}
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
					  {!! Form::text('date_last_move', $row['date_last_move'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Edited By" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Edited By', (isset($fields['last_edited_by']['language'])? $fields['last_edited_by']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('last_edited_by', $row['last_edited_by'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Edited On" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Edited On', (isset($fields['last_edited_on']['language'])? $fields['last_edited_on']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('last_edited_on', $row['last_edited_on'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Prev Location Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Prev Location Id', (isset($fields['prev_location_id']['language'])? $fields['prev_location_id']['language'] : array())) !!}
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
					  {!! Form::text('for_sale', $row['for_sale'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					  {!! Form::text('sale_pending', $row['sale_pending'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Sold" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Sold', (isset($fields['sold']['language'])? $fields['sold']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('sold', $row['sold'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Sold" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Sold', (isset($fields['date_sold']['language'])? $fields['date_sold']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('date_sold', $row['date_sold'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					<label for="Game Move Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Move Id', (isset($fields['game_move_id']['language'])? $fields['game_move_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_move_id', $row['game_move_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Game Service Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Service Id', (isset($fields['game_service_id']['language'])? $fields['game_service_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_service_id', $row['game_service_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Game Title Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game Title Id', (isset($fields['game_title_id']['language'])? $fields['game_title_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('game_title_id', $row['game_title_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					  {!! Form::text('num_prize_meters', $row['num_prize_meters'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Num Prizes" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Num Prizes', (isset($fields['num_prizes']['language'])? $fields['num_prizes']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('num_prizes', $row['num_prizes'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Id', (isset($fields['product_id']['language'])? $fields['product_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_id', $row['product_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Id 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Id 1', (isset($fields['product_id_1']['language'])? $fields['product_id_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_id_1', $row['product_id_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Qty 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Qty 1', (isset($fields['product_qty_1']['language'])? $fields['product_qty_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_qty_1', $row['product_qty_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Id 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Id 2', (isset($fields['product_id_2']['language'])? $fields['product_id_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_id_2', $row['product_id_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Qty 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Qty 2', (isset($fields['product_qty_2']['language'])? $fields['product_qty_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_qty_2', $row['product_qty_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Id 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Id 3', (isset($fields['product_id_3']['language'])? $fields['product_id_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_id_3', $row['product_id_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Qty 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Qty 3', (isset($fields['product_qty_3']['language'])? $fields['product_qty_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_qty_3', $row['product_qty_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Id 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Id 4', (isset($fields['product_id_4']['language'])? $fields['product_id_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_id_4', $row['product_id_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Qty 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Qty 4', (isset($fields['product_qty_4']['language'])? $fields['product_qty_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_qty_4', $row['product_qty_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Id 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Id 5', (isset($fields['product_id_5']['language'])? $fields['product_id_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_id_5', $row['product_id_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Product Qty 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Product Qty 5', (isset($fields['product_qty_5']['language'])? $fields['product_qty_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('product_qty_5', $row['product_qty_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Price Per Play" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Price Per Play', (isset($fields['price_per_play']['language'])? $fields['price_per_play']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('price_per_play', $row['price_per_play'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Product Meter 1" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Product Meter 1', (isset($fields['last_product_meter_1']['language'])? $fields['last_product_meter_1']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('last_product_meter_1', $row['last_product_meter_1'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Product Meter 2" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Product Meter 2', (isset($fields['last_product_meter_2']['language'])? $fields['last_product_meter_2']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('last_product_meter_2', $row['last_product_meter_2'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Product Meter 3" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Product Meter 3', (isset($fields['last_product_meter_3']['language'])? $fields['last_product_meter_3']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('last_product_meter_3', $row['last_product_meter_3'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Product Meter 4" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Product Meter 4', (isset($fields['last_product_meter_4']['language'])? $fields['last_product_meter_4']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('last_product_meter_4', $row['last_product_meter_4'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Product Meter 5" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Product Meter 5', (isset($fields['last_product_meter_5']['language'])? $fields['last_product_meter_5']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('last_product_meter_5', $row['last_product_meter_5'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Product Meter 6" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Product Meter 6', (isset($fields['last_product_meter_6']['language'])? $fields['last_product_meter_6']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('last_product_meter_6', $row['last_product_meter_6'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Product Meter 7" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Product Meter 7', (isset($fields['last_product_meter_7']['language'])? $fields['last_product_meter_7']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('last_product_meter_7', $row['last_product_meter_7'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Product Meter 8" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Product Meter 8', (isset($fields['last_product_meter_8']['language'])? $fields['last_product_meter_8']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('last_product_meter_8', $row['last_product_meter_8'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Last Meter Date" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Last Meter Date', (isset($fields['last_meter_date']['language'])? $fields['last_meter_date']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('last_meter_date', $row['last_meter_date'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Not Debit" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Not Debit', (isset($fields['not_debit']['language'])? $fields['not_debit']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('not_debit', $row['not_debit'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					  {!! Form::text('linked_to_game', $row['linked_to_game'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Test Piece" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Test Piece', (isset($fields['test_piece']['language'])? $fields['test_piece']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('test_piece', $row['test_piece'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> </fieldset>
			</div>
			
												
								
						
			<div style="clear:both"></div>	
							
			<div class="form-group">
				<div class="col-sm-12 text-center">	
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
	renderDropdown($(".select2 "), { width:"100%"});
	$('.date').datepicker({format:'mm/dd/yyyy',autoclose:true})
	$('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
	});			
	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});			
	var form = $('#testmoduleFormAjax'); 
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