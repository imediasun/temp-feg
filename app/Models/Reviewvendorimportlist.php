<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

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

    public function getImportVendors(){
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
            if($row->is_deleted == 1 && $row->is_updated == 0 && $row->is_new == 0) {
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
            $itemsobjs = self::where('import_vendor_id',$id)->where('is_omitted',0);
            self::where('import_vendor_id',$id)->where('is_omitted',0)->update(['is_imported'=>1,'imported_by'=>Session::get('uid'),'imported_at'=>date('Y-m-d H:i:s')]);

            $items = $itemsobjs->get()->toArray();
            $product = new product();
            $a = [];
            foreach ($items as $item){
                $isDeleted = $item['is_deleted'] == 1 ? 1:0;
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
                if($isDeleted == 1){
                    $product->where('id',$productId)->delete();
                }else {
                    $product->insertRow($item, $productId);
                }
            }

            \DB::table('import_vendors')->where('id',$id)->update(['is_imported'=>1,'updated_at'=>date('Y-m-d H:i:s')]);
            return true;
        }else{
            return false;
        }

    }
    public function isVendorExist($fromEmail){
        $vendor = vendor::select("id")->where(function ($query) use($fromEmail){
            $query->where('email',$fromEmail);
            $query->where('email_2',$fromEmail);
        })->get();
        return $vendor->count() > 0 ? true:false;
    }
    public function importExlAttachment($dataArray = []){
      $fromEmail = $dataArray['from_email'];
      $vendor = vendor::select("id")->where(function ($query) use($fromEmail){
          $query->where('email',$fromEmail);
          $query->where('email_2',$fromEmail);
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

            $products = product::where(['vendor_id' => $vendor->id, 'exclude_export' => 0])
                ->groupBy('variation_id')
                ->orderBy('id','asc')->get();
            foreach ($products as $product){
                $productInImportList  = self::where('import_vendor_id',$vendorListId)
                    ->where('vendor_id',$vendor->id)
                    ->where('product_id',$product->id)->get();
                if($productInImportList->count() == 0){
                    $this->insertDeletedRecord($product,$vendorListId);
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
            if (!empty($row['vendor_description'])) {
                if ($isNew) {
                    $row['is_updated'] = 0;
                    $row['is_new'] = 1;
                }
                $updateItems = self::select('id')->where('vendor_id', $vendorId)->where('product_id', $row['product_id'])->where('is_omitted', 1)->first();
                if ($updateItems) {
                    self::where('id', $updateItems->id)->update($row);
                } else {
                    $this->insertRow($row, null);
                }
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
                unset($row->id);
                $row->is_reserved = !empty($row->is_reserved) ? $row->is_reserved:0;

                $row->is_updated = (
                $row->vendor_description != $updatedFields['vendor_description']
                    || $row->sku != $updatedFields['sku']
                    || $row->upc_barcode != $updatedFields['upc_barcode']
                    || $row->num_items != $updatedFields['item_per_case']
                    || $row->case_price != $updatedFields['case_price']
                    || $row->unit_price != $updatedFields['unit_price']
                    || $row->reserved_qty != $updatedFields['reserved_qty']
                 || ($row->is_reserved != in_array($updatedFields['is_reserved'], ['YES', 'yes', 'Yes', 1, 'enabled', 'Enabled']) ? 1 : 0)
                ) ? 1:0;
                $row->product_id = $id;
                $row->import_vendor_id = $vendorListId;
                $row->vendor_description = $updatedFields['vendor_description'];
                $row->sku = $updatedFields['sku'];
                $row->upc_barcode = $updatedFields['upc_barcode'];
                $row->num_items = $updatedFields['item_per_case'];
                $row->case_price = $updatedFields['case_price'];
                $row->unit_price = $updatedFields['unit_price'];
                $row->is_reserved = in_array($updatedFields['is_reserved'], ['YES', 'yes', 'Yes', 1, 'enabled', 'Enabled']) ? 1 : 0;
                $row->ticket_value = $updatedFields['ticket_value'];
                $row->reserved_qty = $updatedFields['reserved_qty'];

            }
            return $rows->toArray();
        }else{
            $row['product_id'] = $id;
            $row['import_vendor_id'] = $vendorListId;
            $row['vendor_description'] = $updatedFields['vendor_description'];
            $row['sku'] = $updatedFields['sku'];
            $row['upc_barcode'] = $updatedFields['upc_barcode'];
            $row['num_items'] = $updatedFields['item_per_case'];
            $row['case_price'] = $updatedFields['case_price'];
            $row['unit_price'] = $updatedFields['unit_price'];
            $row['is_reserved'] = in_array($updatedFields['is_reserved'], ['YES', 'yes', 'Yes', 1, 'enabled', 'Enabled']) ? 1 : 0;
            $row['ticket_value'] = $updatedFields['ticket_value'];
            $row['reserved_qty'] = $updatedFields['reserved_qty'];
            $row['is_updated'] = 0;
            $row['is_new'] = 1;
            return [$row];
        }
    }

    public function insertDeletedRecord($product,$vendorListId){

        $variations = product::where('vendor_description',$product->vendor_description)
                                ->where('sku',$product->sku)
                                ->where('case_price',$product->case_price)->get();
            foreach ($variations as $variation){
                $this->saveDeletedItems($variation,$vendorListId);
            }
    }
    public function saveDeletedItems($variation,$vendorListId){
        $productId = $variation->id;
        unset($variation->id);
        $variation->product_id = $productId;
        $variation->import_vendor_id = $vendorListId;
        $variation->is_deleted = 1;
        $data = (array) $variation->toArray();
        $this->insertRow($data, null);
    }
}
