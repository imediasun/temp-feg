
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'reviewvendorimportlist/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'reviewvendorimportlistFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Review Vendor Import List</legend>
				
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
					<label for="Sku" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Sku', (isset($fields['sku']['language'])? $fields['sku']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('sku', $row['sku'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Upc Barcode" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Upc Barcode', (isset($fields['upc_barcode']['language'])? $fields['upc_barcode']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('upc_barcode', $row['upc_barcode'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Vendor Description" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Vendor Description', (isset($fields['vendor_description']['language'])? $fields['vendor_description']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('vendor_description', $row['vendor_description'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Item Description" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Item Description', (isset($fields['item_description']['language'])? $fields['item_description']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('item_description', $row['item_description'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Netsuite Description" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Netsuite Description', (isset($fields['netsuite_description']['language'])? $fields['netsuite_description']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('netsuite_description', $row['netsuite_description'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Size" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Size', (isset($fields['size']['language'])? $fields['size']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('size', $row['size'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Details" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Details', (isset($fields['details']['language'])? $fields['details']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('details', $row['details'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Num Items" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Num Items', (isset($fields['num_items']['language'])? $fields['num_items']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('num_items', $row['num_items'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Vendor Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Vendor Id', (isset($fields['vendor_id']['language'])? $fields['vendor_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('vendor_id', $row['vendor_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Unit Price" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Unit Price', (isset($fields['unit_price']['language'])? $fields['unit_price']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('unit_price', $row['unit_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Case Price" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Case Price', (isset($fields['case_price']['language'])? $fields['case_price']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('case_price', $row['case_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Retail Price" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Retail Price', (isset($fields['retail_price']['language'])? $fields['retail_price']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('retail_price', $row['retail_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Ticket Value" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Ticket Value', (isset($fields['ticket_value']['language'])? $fields['ticket_value']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('ticket_value', $row['ticket_value'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Prod Type Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Prod Type Id', (isset($fields['prod_type_id']['language'])? $fields['prod_type_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('prod_type_id', $row['prod_type_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Prod Sub Type Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Prod Sub Type Id', (isset($fields['prod_sub_type_id']['language'])? $fields['prod_sub_type_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('prod_sub_type_id', $row['prod_sub_type_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Is Reserved" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Is Reserved', (isset($fields['is_reserved']['language'])? $fields['is_reserved']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('is_reserved', $row['is_reserved'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Reserved Qty" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Reserved Qty', (isset($fields['reserved_qty']['language'])? $fields['reserved_qty']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('reserved_qty', $row['reserved_qty'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Reserved Qty Reason" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Reserved Qty Reason', (isset($fields['reserved_qty_reason']['language'])? $fields['reserved_qty_reason']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('reserved_qty_reason', $row['reserved_qty_reason'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Variation Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Variation Id', (isset($fields['variation_id']['language'])? $fields['variation_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('variation_id', $row['variation_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Min Order Amt" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Min Order Amt', (isset($fields['min_order_amt']['language'])? $fields['min_order_amt']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('min_order_amt', $row['min_order_amt'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Img" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Img', (isset($fields['img']['language'])? $fields['img']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('img', $row['img'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Inactive" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Inactive', (isset($fields['inactive']['language'])? $fields['inactive']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('inactive', $row['inactive'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Inactive By" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Inactive By', (isset($fields['inactive_by']['language'])? $fields['inactive_by']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('inactive_by', $row['inactive_by'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Activated At" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Activated At', (isset($fields['activated_at']['language'])? $fields['activated_at']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('activated_at', $row['activated_at'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Eta" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Eta', (isset($fields['eta']['language'])? $fields['eta']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('eta', $row['eta'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="In Development" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('In Development', (isset($fields['in_development']['language'])? $fields['in_development']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('in_development', $row['in_development'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Limit To Loc Group Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Limit To Loc Group Id', (isset($fields['limit_to_loc_group_id']['language'])? $fields['limit_to_loc_group_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('limit_to_loc_group_id', $row['limit_to_loc_group_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Added" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Added', (isset($fields['date_added']['language'])? $fields['date_added']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('date_added', $row['date_added'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Hot Item" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Hot Item', (isset($fields['hot_item']['language'])? $fields['hot_item']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('hot_item', $row['hot_item'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Created At" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Created At', (isset($fields['created_at']['language'])? $fields['created_at']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('created_at', $row['created_at'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Updated At" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Updated At', (isset($fields['updated_at']['language'])? $fields['updated_at']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('updated_at', $row['updated_at'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Expense Category" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Expense Category', (isset($fields['expense_category']['language'])? $fields['expense_category']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('expense_category', $row['expense_category'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Exclude Export" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Exclude Export', (isset($fields['exclude_export']['language'])? $fields['exclude_export']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('exclude_export', $row['exclude_export'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Allow Negative Reserve Qty" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Allow Negative Reserve Qty', (isset($fields['allow_negative_reserve_qty']['language'])? $fields['allow_negative_reserve_qty']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('allow_negative_reserve_qty', $row['allow_negative_reserve_qty'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Reserved Qty Limit" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Reserved Qty Limit', (isset($fields['reserved_qty_limit']['language'])? $fields['reserved_qty_limit']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('reserved_qty_limit', $row['reserved_qty_limit'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Is Default Expense Category" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Is Default Expense Category', (isset($fields['is_default_expense_category']['language'])? $fields['is_default_expense_category']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('is_default_expense_category', $row['is_default_expense_category'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Send Email Alert" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Send Email Alert', (isset($fields['send_email_alert']['language'])? $fields['send_email_alert']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('send_email_alert', $row['send_email_alert'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Import Vendor Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Import Vendor Id', (isset($fields['import_vendor_id']['language'])? $fields['import_vendor_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('import_vendor_id', $row['import_vendor_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
					<label for="Is Imported" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Is Imported', (isset($fields['is_imported']['language'])? $fields['is_imported']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('is_imported', $row['is_imported'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Imported By" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Imported By', (isset($fields['imported_by']['language'])? $fields['imported_by']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('imported_by', $row['imported_by'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Imported At" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Imported At', (isset($fields['imported_at']['language'])? $fields['imported_at']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('imported_at', $row['imported_at'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Is Omitted" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Is Omitted', (isset($fields['is_omitted']['language'])? $fields['is_omitted']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('is_omitted', $row['is_omitted'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
	var form = $('#reviewvendorimportlistFormAjax'); 
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