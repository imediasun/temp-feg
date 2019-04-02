<?php namespace App\Models;

use App\Library\FEG\System\FEGSystemHelper;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Log;

class reviewvendorimportlist extends Sximo  {
	
	protected $table = 'vendor_import_products';
	protected $primaryKey = 'id';
    protected $editableGridColumns = [
        'ticket_value'=>['field'=>'text'],
        'retail_price'=>['field'=>'text'],
        'prod_type_id'=>['field'=>'select','source'=>'order_type'],
        'prod_sub_type_id'=>['field'=>'select','source'=>'product_type'],
        'expence_category'=>['field'=>'select','source'=>'expense_category_mapping'],
        'is_reserved'=>['field'=>'select','source'=>'custom','type'=>'boolean'],
        'allow_negative_reserve_qty'=>['field'=>'select','source'=>'custom','type'=>'boolean'],
        'inactive'=>['field'=>'select','source'=>'custom','type'=>'boolean'],
        'in_development'=>['field'=>'select','source'=>'custom','type'=>'boolean'],
        'hot_item'=>['field'=>'select','source'=>'custom','type'=>'boolean'],
        'exclude_export'=>['field'=>'select','source'=>'custom','type'=>'boolean'],
    ];

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT
  vendor_import_products.*,
  IF(vendor_import_products.is_omitted = 1, 0,vendor_import_products.is_new) AS newItem,
IF(vendor_import_products.is_omitted = 1, 0,vendor_import_products.is_updated) AS updatedItem 
FROM vendor_import_products  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE vendor_import_products.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    public static function getRows($args, $cond = null)
    {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        $hideUnchanged = $hideOmittedItems = 0;
        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'extraSorts' => [],
            'order' => '',
            'hideUnchanged' => 0,
            'hideOmittedItems' => 0,
            'params' => '',
            'global' => 1
        ), $args));


        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY  {$sort} {$order}  " : ' ORDER BY is_new DESC , is_updated DESC, is_missing_in_file ASC ,  is_omitted ASC    ';
        if (!empty($extraSorts)) {
            if (empty($orderConditional)) {
                $orderConditional = " ORDER BY ";
            } else {
                $orderConditional .= ", ";
            }
            $extraOrderConditionals = [];
            foreach ($extraSorts as $extraSortItem) {
                $extraSortItem[0] = '`' . $extraSortItem[0] . '`';
                $extraOrderConditionals[] = implode(' ', $extraSortItem);
            }
            $orderConditional .= implode(', ', $extraOrderConditionals);
        }


        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if ($global == 0)
            $params .= " AND {$table}.entry_by ='" . \Session::get('uid') . "'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        $select = self::querySelect();

        /*

        */
        $createdFlag = false;

        if ($cond != null) {
            $select .= self::queryWhere($cond);
        } else {
            $select .= self::queryWhere();
        }

        if (!empty($createdFrom)) {
            if ($cond != 'only_api_visible') {
                $select .= " AND created_at BETWEEN '$createdFrom' AND '$createdTo'";
            } else {
                $select .= " AND api_created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            $createdFlag = true;
        }

        if (!empty($updatedFrom)) {

            if ($createdFlag) {
                if ($cond != 'only_api_visible') {
                    $select .= " OR updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                } else {
                    $select .= " OR api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            } else {
                if ($cond != 'only_api_visible') {
                    $select .= " AND updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                } else {
                    $select .= " AND api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }

        }

        if (!empty($order_type_id)) {
            $select .= " AND order_type_id in($order_type_id)";
        }
        if (!empty($status_id)) {
            $select .= " AND status_id='$status_id'";
        }
        if (!empty($active)) {//added for location
            $select .= " AND location.active='$active'";
        }

        if ($hideUnchanged == 1) {
            $select .= ' AND (is_new = 1 OR is_updated = 1 OR is_omitted = 1 OR is_missing_in_file = 1) ';
        }
        if ($hideOmittedItems == 1) {

            $select .= ' AND is_omitted = 0 ';
        }

        Log::info("Total Query : " . $select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $counter_select = \DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $total = count($counter_select);
        if ($table == "img_uploads") {
            $total = "";
        }

        $offset = ($page - 1) * $limit;
        if ($offset >= $total && $total != 0 && $limit != 0) {
            $page = ceil($total / $limit);
            $offset = ($page - 1) * $limit;
        }

        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';
        // echo $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";
        Log::info("Query : " . $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        self::$getRowsQuery = $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";
        $result = \DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");

        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }

        return $results = array('rows' => $result, 'total' => $total);
    }

    public function getImportVendors($vendorId){
        $fields = [
            'import_vendors.id',
            'import_vendors.vendor_id',
            'vendor.vendor_name',
            'import_vendors.email_recieved_at'
        ];
        $vendors = vendor::select($fields)
            ->join('import_vendors','import_vendors.vendor_id','=','vendor.id')
            ->orderBy('import_vendors.email_recieved_at','desc')
            ->where('import_vendors.is_imported','=','0')->get();
        
        return $vendors;
    }

    public function getExpenseCategoryGroups(){
        $expense_category = \DB::select("SELECT expense_category_mapping.id,expense_category_mapping.mapped_expense_category,order_type.`order_type`,CONCAT(mapped_expense_category,' ',GROUP_CONCAT(order_type.`order_type` ORDER BY order_type.`order_type` ASC SEPARATOR ' | ')) as order_type
FROM expense_category_mapping
JOIN order_type ON order_type.id = expense_category_mapping.order_type
WHERE product_type IS NULL
GROUP BY mapped_expense_category");
        $items = [];
        foreach ($expense_category as $category) {
            $orderType = $category->order_type;
            $categoryId = $category->mapped_expense_category;
            if ($categoryId == 0) {
                /* $orderType = "N/A";
                 $categoryId = "";
                */
            } else {

                $items[] = [$categoryId, $orderType];
            }
        }
        return $items;
    }

    public function getProductType(){
        return Ordertyperestrictions::select('id','order_type')->where('can_request','=','1')->orderBy('order_type','asc')->get();
    }
    public function getProductAllSubTypes(){
       return ProductType::orderBy('type_description','asc')->get();
    }
    public function addProductSubTypes($rows){
        $productSubTypes = $this->getProductAllSubTypes();
        foreach ($rows as $row){
            $row->productSubTypes = $productSubTypes->where('request_type_id',$row->prod_type_id);
        }
        return $rows;
    }

    public function setRowStatus($rows){
        //Red=#fd4b4b;Green=#4fbb39;Blue=#103669;Orange=#ff9900;


        foreach ($rows as $row){
            if($row->is_omitted == 1) {
                $row->textColor = '#ff9900';
            }if($row->is_missing_in_file == 1) {
                $row->textColor = '#fd4b4b';
            }elseif($row->is_deleted == 0 && $row->is_updated == 1 && $row->is_new == 0){
                $row->textColor = '#1c78f5';
            }elseif($row->is_deleted == 0 && $row->is_updated == 0 && $row->is_new == 1){
                $row->textColor = '#4fbb39';
            }
        }

        return $rows;
    }

    /**
     * @param $id
     * @return bool
     */
    public function updateProductModule($id){
        if($id > 0){

            $vendorImportList = self::where('import_vendor_id',$id)->where('is_omitted',0)->where('prod_type_id', 0)->get()->toArray();
            if(count($vendorImportList) > 0)//if product type id of any item is not set.
            {
                return response()->json(array(
                    'status' => 'error',
                    'message' => "Please select a product type for each non-omitted item."
                ));
            }

            $itemsobjs = self::where('import_vendor_id',$id)->where('is_omitted',0);
            self::where('import_vendor_id',$id)->where('is_omitted',0)->update(['is_imported'=>1,'imported_by'=>Session::get('uid'),'imported_at'=>date('Y-m-d H:i:s')]);

            $items = $itemsobjs->get()->toArray();
            $product = new product();
            $a = [];
            foreach ($items as $item){
                $isOmitted = $item['is_omitted'] == 1 ? 1:0;
                $productId = $item['product_id'] > 0 ? $item['product_id']:null;
                unset($item['id']);
                unset($item['product_id']);
                unset($item['is_imported']);
                unset($item['imported_by']);
                unset($item['imported_at']);
                unset($item['is_omitted']);
                unset($item['import_vendor_id']);
                unset($item['is_new']);
                unset($item['is_updated']);
                unset($item['is_deleted']);
                unset($item['previous_status']);
                unset($item['is_missing_in_file']);
                if($isOmitted == 0){
                    $product->insertRow($item, $productId);
                }
            }

            \DB::table('import_vendors')->where('id',$id)->update(['is_imported'=>1,'updated_at'=>date('Y-m-d H:i:s')]);
            return response()->json(array(
                'status' => 'success',
                'message' => "Product list module has been updated."
            ));
        }else{
            return response()->json(array(
                'status' => 'error',
                'message' => "Product list module couldn't be updated."
            ));
        }

    }
    public function isVendorExist($fromEmail){
        $vendor = vendor::select("*")->where(function ($query) use($fromEmail){
            $query->where('email',$fromEmail);
            $query->orWhere('email_2',$fromEmail);
            $query->orWhere('games_contact_email',$fromEmail);
        })->get();
        return $vendor->count();
    }

    /**
     * @param $fromEmail
     * @return mixed
     */
    public function getVendorByEmail($fromEmail){
        $vendor = vendor::select("*")->where(function ($query) use($fromEmail){
            $query->where('email',$fromEmail);
            $query->orWhere('email_2',$fromEmail);
            $query->orWhere('games_contact_email',$fromEmail);
        })->first();
        return $vendor;
    }

    public function getVendorById($vendorId){
        $vendor = vendor::find($vendorId);
        return $vendor;
    }
    
    public function importExlAttachment($dataArray = [], $vendor){
      $fromEmail = $dataArray['from_email'];

        if($vendor) {
            $isInvalidId = false;
            foreach ($dataArray['attachments'] as $attachment) {
                $testFileData = \SiteHelpers::getVendorFileImportData($attachment);
                if (!empty($testFileData)) {
                    $vendorListStatus = $this->notifyVendorIfProductIdInvalid($testFileData, $vendor->id);

                    if ($vendorListStatus['status'] == true) {
                        $isInvalidId = true;
                        //Sending mail with Excel file attachment
                        $subject = "[System Error] Products List - [Vendor Product List #$vendor->id] " . FEGSystemHelper::getHumanDate(date('Y-m-d'));
                        $this->replyToVendor($vendor, $subject, $vendorListStatus['message'], $attachment);
                    }
                }
            }

            if ($isInvalidId == true) {
                return false;
            }

            $data = [
                'vendor_id' => $vendor->id, 'email_recieved_at' => date('Y-m-d H:i:s', strtotime($dataArray['email_received_at'])), 'created_at' => date('Y-m-d H:i:s'),
            ];
            $importVendor = new ImportVendor();
            $vendorListId = $importVendor->insertRow($data, null);

            foreach ($dataArray['attachments'] as $attachment) {
                $fileData = \SiteHelpers::getVendorFileImportData($attachment);

                if (!empty($fileData)) {
                    foreach ($fileData as $item) {
                        $item['id'] = (int) $item['product_id'];
                        unset($item['product_id']);
                        if ($item['id'] > 0 && !empty($item['id'])) {
                            $productRows = $this->findProducts($item['id'], $item, $vendorListId);
                            $this->saveProductList($productRows, $vendor->id);
                        } else {
                            $productRows = $this->findProducts($item['id'], $item, $vendorListId);
                            $this->saveProductList($productRows, $vendor->id, true);
                        }
                    }
                    $fileItemIds = reviewvendorimportlist::getProductIdsOnly($fileData);
                    $products = reviewvendorimportlist::getRemovedItemsByVendor($fileItemIds,$vendor->id,$vendorListId);
                    foreach ($products as $product){
                        $this->insertRow($product, null);
                    }
                }
            }


        }
    }

    /**
     * @param $rows
     * @param bool $isNew
     */
    public function saveProductList($rows,$vendorId,$isNew = false){
        foreach ($rows as $row) {
            unset($row['item_name']);
            if(isset($row['is_converted'])) {
                unset($row['is_converted']);
            }
        sleep(2);
            if (!empty($row['vendor_description'])) {
                if ($isNew) {
                    $row['is_updated'] = 0;
                    $row['is_new'] = 1;
                    $UniqueID = (!empty($row['variation_id'])) ? $row['variation_id']:substr(md5(microtime(true)."-".md5($row['vendor_description'].$row['sku'].$row['case_price'])),0,10);
                    $row['variation_id'] = $UniqueID;
                }
                $updateItems = self::select('id')->where('vendor_id', $vendorId)->where('vendor_description', $row['vendor_description'])
                    ->where('sku', $row['sku'])
                    ->where('case_price', $row['case_price'])->where('is_omitted', 1)->first();

                if ($updateItems) {
                    $row['is_omitted'] = 1;
                    $row['is_updated'] = 0;
                    $row['is_new'] = 0;
                }else{
                    $row['is_omitted'] = 0;
                }

                    $row['vendor_id'] = $vendorId;
                    $this->insertRow($row, null);

            }
        }
    }

    /**
     * @param $id
     * @param $updatedFields
     * @param $vendorListId
     * @return array
     */
    public function findProducts($id,$updatedFields,$vendorListId){
        $product = product::find($id);
        if($product && $id > 0){

            $rows = product::where('vendor_description',$product->vendor_description)
                ->where('sku',$product->sku)
                ->where('case_price',$product->case_price)->get();

            foreach ($rows as $row) {
                $row->product_id = $row->id;
                unset($row->id);
                if(isset($row->is_converted)) {
                    unset($row->is_converted);
                }
//                $row->is_reserved = !empty($row->is_reserved) ? $row->is_reserved:0;

                $row->is_updated = (
                $row->vendor_description != $updatedFields['item_name']
                    || $row->sku != $updatedFields['sku']
                    || $row->upc_barcode != $updatedFields['upc_barcode']
                    || $row->num_items != $updatedFields['item_per_case']
                    || $row->case_price != $updatedFields['case_price']
                    || $row->unit_price != $updatedFields['unit_price']
                    || $row->reserved_qty != $updatedFields['reserved_qty']
//                 || ($row->is_reserved != in_array($updatedFields['is_reserved'], ['YES', 'yes', 'Yes', 1, 'enabled', 'Enabled']) ? 1 : 0)
                ) ? 1:0;

                $row->import_vendor_id = $vendorListId;
                $row->vendor_description = $updatedFields['item_name'];
                $row->sku = $updatedFields['sku'];
                $row->upc_barcode = $updatedFields['upc_barcode'];
                $row->num_items = $updatedFields['item_per_case'];
                $row->case_price = $updatedFields['case_price'];
                $row->unit_price = $updatedFields['unit_price'];
                $row->is_reserved = ($updatedFields['reserved_qty'] > 0) ? 1 : 0;
//                $row->ticket_value = $updatedFields['ticket_value'];
                $row->reserved_qty = $updatedFields['reserved_qty'];

            }
            return $rows->toArray();
        }else{
            $row['product_id'] = $id;
            $row['import_vendor_id'] = $vendorListId;
            $row['vendor_description'] = $updatedFields['item_name'];
            $row['sku'] = $updatedFields['sku'];
            $row['upc_barcode'] = $updatedFields['upc_barcode'];
            $row['num_items'] = $updatedFields['item_per_case'];
            $row['case_price'] = $updatedFields['case_price'];
            $row['unit_price'] = $updatedFields['unit_price'];
//            $row['is_reserved'] = in_array($updatedFields['is_reserved'], ['YES', 'yes', 'Yes', 1, 'enabled', 'Enabled']) ? 1 : 0;
//            $row['ticket_value'] = $updatedFields['ticket_value'];
            $row['reserved_qty'] = $updatedFields['reserved_qty'];
            $row['is_reserved'] = ($updatedFields['reserved_qty'] > 0) ? 1 : 0;
            $row['is_updated'] = 0;
            $row['is_new'] = 1;
            $row['inactive'] = 0;
            return [$row];
        }
    }
    /**
     * @param $items
     * @param $vendorId
     * @return array
     */
    public function notifyVendorIfProductIdInvalid($items,$vendorId){

        $itemNotify = ['status' => false ,'message' => ''];
        $message = '<p>There is a product ID error on row(s)';
        $itemsIndex = '';
        $i = 1;

        foreach ($items as $listItem){
            $i++;
            if(trim($listItem['item_name']) != '') {
                if (trim($listItem['product_id']) != '') {
                    //Generating Error Messages if user has entered an invalid product ID
                    $productId = (int)$listItem['product_id'];
                    $vendorProduct = VendorProductTrack::where(['vendor_id'=>$vendorId,'product_id' => $productId])->get();
                    if ($vendorProduct->count() < 1) {
                        $itemsIndex .= empty($itemsIndex) ? $i : ',' . $i;
                        $itemNotify['status'] = true; // If itemNotify['status'] is equal to true then an email notification will be sent to the user along attachment
                    }
                }
            }
        }
        $message .= ' '.$itemsIndex.'.</p>';
        $message .= '<p style="margin-bottom: 0px;">To correct this error:</p>';
        $message .='<ol style="margin-top: 0px;">';
        $message .='<li>Open the attached file.</li>';
        $message .='<li>Go to the row(s) indicated above.</li>';
        $message .='<li>Delete the entry in column A for the row(s) indicated above.</li>';
        $message .='<li>Save the file to your computer. Remember where you saved it.</li>';
        $message .='<li>REPLY ALL to this email and attach your updated file.</li>';
        $message .='<li>Send the email with the updated attachment back to us.</li>';
        $message .= '</ol>';

        $itemNotify['message'] = $message;
        return $itemNotify;

    }

    /**
     * @param $vendor
     * @param $subject
     * @param $message
     * @param $file_to_save
     */
    public function replyToVendor($vendor,$subject,$message,$file_to_save){

        $sendEmailFromMerchandise = false;
        $from = 'vendor.products@fegllc.com';
        $to = []; //$vendor->email ? $vendor->email:$vendor->email_2;
//        games_contact_email
        $vendorEmail = '';
        if(!empty($vendor->email) && $vendor->email !='') {
            $to[] = $vendor->email; //get vendor mail address one
        }
        if(!empty($vendor->email_2) && $vendor->email_2 !='') {
            $to[] = $vendor->email_2; //get vendor mail address one
        }
        if(!empty($to)) {
            if (count($to) > 1) {
                $to = array_unique($to);
            }
        }
        $to = !empty($to) ? implode(',',$to):$to;
        $basename = basename($file_to_save);
        $fileName = $basename;

        //Check if vendor ismerch = yes
        if ($vendor->ismerch == 1){
            $configName = 'Send Product Export To Merchandise Vendor';
            $recipients =  FEGSystemHelper::getSystemEmailRecipients($configName);
            if(!empty($to)){
                $recipients['to'].= ','.$to;
            }

            if($recipients['to']!='') {
                $sent = FEGSystemHelper::sendSystemEmail(array_merge($recipients, array(
                    'subject' => $subject,
                    'message' => $message,
                    'preferGoogleOAuthMail' => false,
                    'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                    'configName' => $configName,
                    'from' => $from,
                    'replyTo' => $from,
                    'attach' => $file_to_save,
                    'filename' => $fileName,
                    'encoding' => 'base64',
                    'type' => 'text/csv',
                )), $sendEmailFromMerchandise, $sendEmailFromVendorAccount = true);
                if (!$sent){
                    $isSent = 3;
                }else {
                    // Delete temporary file
                    $isSent = 1;
                }

            }
        }


        // Game Related Vendor Email Configuration name "Send Product Export To Game Vendor"
        //Check if vendor isgame = yes
        if ($vendor->isgame == 1){
            $configName = 'Send Product Export To Game Vendor';
            $recipients =  FEGSystemHelper::getSystemEmailRecipients($configName);
            $to = $vendor->games_contact_email;
            if(!empty($to)){
                $recipients['to'].= ','.$to;
            }

            if($recipients['to']!='') {
                $sent = FEGSystemHelper::sendSystemEmail(array_merge($recipients, array(
                    'subject' => $subject,
                    'message' => $message,
                    'preferGoogleOAuthMail' => false,
                    'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                    'configName' => $configName,
                    'from' => $from,
                    'replyTo' => $from,
                    'attach' => $file_to_save,
                    'filename' => $fileName,
                    'encoding' => 'base64',
                    'type' => 'text/csv',
                )), $sendEmailFromMerchandise, $sendEmailFromVendorAccount = true);

                if (!$sent){
                    $isSent = 3;
                }else {
                    // Delete temporary file
                    $isSent = 1;
                }

            }
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public static function getProductIdsOnly($data = [])
    {
        $collection = collect($data);
        $dataArray = $collection->pluck('product_id')->toArray();
        $dataArray = array_map(function ($arr) {
            $productId = (int)$arr;
            return $productId;
        }, $dataArray);
        return $dataArray;
    }

    /**
     * @param array $productIds
     * @param int $vendorId
     * @param int $vendorListId
     * @return array
     */
    public static function getRemovedItemsByVendor($productIds = [], $vendorId = 0, $vendorListId = 0)
    {
        $vendorProduct = VendorProductTrack::where(['vendor_id'=>$vendorId])->whereNotIn('product_id', $productIds)->get()->toArray();
        $vendorProduct = self::getProductIdsOnly($vendorProduct);
        $products = product::whereIn('id',$vendorProduct)->get();
        $data = [];
        if($products && count($vendorProduct) > 0) {

            foreach ($products as $product) {
                $rows = product::where('vendor_description', $product->vendor_description)
                    ->where('sku', $product->sku)
                    ->where('case_price', $product->case_price)->get();
                foreach ($rows as $row) {
                    $row->product_id = $row->id;
                    unset($row->id);
                    if (isset($row->is_converted)) {
                        unset($row->is_converted);
                    }
                    $row->inactive = 1;
                    $row->inactive_by = 0;
                    $row->is_updated = 0;
                    $row->is_new = 0;
                    $row->is_missing_in_file = 1;
                    $row->import_vendor_id = $vendorListId;
                    $data[] = $row->toArray();
                }
            }

        }
        return $data;
    }
}
