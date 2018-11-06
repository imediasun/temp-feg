<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

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

}
