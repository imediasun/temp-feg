<?php namespace App\Models;

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
		
		return "  SELECT vendor_import_products.* FROM vendor_import_products  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE vendor_import_products.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    public static function getRows($args, $cond = null) {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        $hideUnchanged = $hideOmittedItems = 0;
        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'extraSorts' => [],
            'order' => '',
            'hideUnchanged'=>0,
            'hideOmittedItems' => 0,
            'params' => '',
            'global' => 1
        ), $args));


        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY is_new DESC , is_updated DESC , is_omitted DESC  " : ' ORDER BY is_new DESC , is_updated DESC , is_omitted DESC   ';
        if (!empty($extraSorts)) {
            if (empty($orderConditional)) {
                $orderConditional = " ORDER BY ";
            }
            else {
                $orderConditional .= ", ";
            }
            $extraOrderConditionals = [];
            foreach($extraSorts as $extraSortItem) {
                $extraSortItem[0] = '`'.$extraSortItem[0].'`';
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
        }
        else {
            $select .= self::queryWhere();
        }

        if(!empty($createdFrom)){
            if($cond != 'only_api_visible')
            {
                $select .= " AND created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            else
            {
                $select .= " AND api_created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            $createdFlag = true;
        }

        if(!empty($updatedFrom)){

            if($createdFlag){
                if($cond != 'only_api_visible')
                {
                    $select .= " OR updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
                else
                {
                    $select .= " OR api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }
            else{
                if($cond != 'only_api_visible')
                {
                    $select .= " AND updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
                else
                {
                    $select .= " AND api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }

        }

        if(!empty($order_type_id)){
            $select .= " AND order_type_id in($order_type_id)";
        }
        if(!empty($status_id)){
            $select .= " AND status_id='$status_id'";
        }
        if(!empty($active)){//added for location
            $select .= " AND location.active='$active'";
        }

        if($hideUnchanged == 1){
            $select .= ' AND (is_new = 1 OR is_updated = 1) ';
        }
        if($hideOmittedItems == 1){

            $select .= ' AND is_omitted = 0 ';
        }

        Log::info("Total Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $counter_select =\DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $total= count($counter_select);
        if($table=="img_uploads")
        {
            $total="";
        }

        $offset = ($page - 1) * $limit;
        if ($offset >= $total && $total != 0 && $limit != 0) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }

        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';
        // echo $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";
        Log::info("Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
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
            ->orderBy('vendor.vendor_name','asc')
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
        //Red=#fd4b4b;Green=#4fbb39;Blue=#103669;


        foreach ($rows as $row){
            if($row->is_omitted == 1) {
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
                    'message' => "Please select product type of all Items."
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
        $vendor = vendor::select("id")->where(function ($query) use($fromEmail){
            $query->where('email',$fromEmail);
            $query->orWhere('email_2',$fromEmail);
        })->get();
        return $vendor->count() > 0 ? true:false;
    }
    public function importExlAttachment($dataArray = []){
      $fromEmail = $dataArray['from_email'];
      $vendor = vendor::select("id")->where(function ($query) use($fromEmail){
          $query->where('email',$fromEmail);
          $query->orWhere('email_2',$fromEmail);
      })->first();
        if($vendor) {
            $data = [
                'vendor_id' => $vendor->id, 'email_recieved_at' => date('Y-m-d H:i:s', strtotime($dataArray['email_received_at'])), 'created_at' => date('Y-m-d H:i:s'),
            ];
            $importVendor = new ImportVendor();
            $vendorListId = $importVendor->insertRow($data, null);

            foreach ($dataArray['attachments'] as $attachment) {
                $fileData = \SiteHelpers::getVendorFileImportData($attachment);

                if (!empty($fileData)) {
                    foreach ($fileData as $item) {

                        if ($item['id'] > 0 && !empty($item['id'])) {
                            $productRows = $this->findProducts($item['id'], $item, $vendorListId);
                            $this->saveProductList($productRows, $vendor->id);
                        } else {
                            $productRows = $this->findProducts($item['id'], $item, $vendorListId);
                            $this->saveProductList($productRows, $vendor->id, true);
                        }
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

            if (!empty($row['vendor_description'])) {
                if ($isNew) {
                    $row['is_updated'] = 0;
                    $row['is_new'] = 1;
                    $UniqueID = (!empty($row['variation_id'])) ? $row['variation_id']:substr(md5(microtime(true)."-".md5(microtime(true))),0,10);
                    $row['variation_id'] = $UniqueID;
                }
                $updateItems = self::select('id')->where('vendor_id', $vendorId)->where('vendor_description', $row['vendor_description'])
                    ->where('sku', $row['sku'])
                    ->where('case_price', $row['case_price'])->where('is_omitted', 1)->first();

                if ($updateItems) {
                    $row['is_omitted'] = 1;
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
//                $row->is_reserved = in_array($updatedFields['is_reserved'], ['YES', 'yes', 'Yes', 1, 'enabled', 'Enabled']) ? 1 : 0;
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
            $row['is_updated'] = 0;
            $row['is_new'] = 1;
            return [$row];
        }
    }

}
