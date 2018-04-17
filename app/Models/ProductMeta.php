<?php namespace App\Models;

use App\Library\FEG\System\FEGSystemHelper;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Log;

class ProductMeta extends Sximo  {
	
	protected $table = 'product_meta';
    protected $primaryKey = 'id';
    protected $guarded = [];
   // protected $fillable = ['product_id', 'variation_id', 'variation_master_product_id', 'posted_to_api_at', 'posted_to_api_expired_at', 'created_at', 'updated_at', 'deleted_at'];

	public function __construct() {
		parent::__construct();
		
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    function product()
    {
        return $this->hasOne("App\Models\product", 'id', 'product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    function parentProduct()
    {
        return $this->hasOne("App\Models\product", 'id', 'variation_master_product_id');
    }

    /**
     * @return null|Collection
     */
    public function getVariations()
    {
        $items = null;
        /** @var  $product product */
        $product = $this->product;
        if (isset($product) && empty($product)) {
            $items = product::where([
                'vendor_description' => $product->vendor_description,
                'sku' => $product->sku,
                'case_price' => $product->case_price
            ])
            ->get();
        }

        return $items;
    }

    public static function getRowsAPI($args, $options = [])
    {

        $table = with(new static)->table;
        $joinTable = 'products';
        $key = with(new static)->primaryKey;

        extract( array_merge( array(
            'page' 		=> '0' ,
            'limit'  	=> '0' ,
            'sort' 		=> '' ,
            'order' 	=> '' ,
            'params' 	=> '' ,
            'global'	=> 1
        ), $args ));


        if ($sort == 'prod_type_id' || $sort == 'prod_sub_type_id') {
            $sort = "products." . $sort;
        }
        $offset = ($page-1) * $limit ;
        $limitConditional = ($page !=0 && $limit !=0) ? "LIMIT  $offset , $limit" : '';
        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY $sort {$order} " : '';

        $table = with(new static)->table;
        $rows = array();


        $select=self::querySelectAPI($options);
        $select.=\App\Models\product::queryWhere();

        $createdFlag = false;
        if(!empty($createdFrom)){
            $select .= " AND (products.created_at BETWEEN '$createdFrom' AND '$createdTo'";
            $createdFlag = true;
        }
        if(!empty($updatedFrom)){

            if($createdFlag){
                $select .= " OR products.updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                $select .= " OR (product_meta.posted_to_api_at >= now()-1 AND product_meta.posted_to_api_expired_at <= NOW()+1)";

            } else{
                $select .= " AND (products.updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                $select .= " OR (product_meta.posted_to_api_at <= now()-1 AND product_meta.posted_to_api_expired_at >= NOW()+1) )";
            }

        }
        if($createdFlag) {
            $select .= ")";
        }

        if(!empty($prod_type_id)){
            $select .= " AND prod_type_id='$prod_type_id'";
        }
        if(!empty($vendor_id)){
            $select .= " AND vendor_id='$vendor_id'";
        }
        if(!empty($product_ids)){
            $select .= " AND products.id IN ($product_ids)";
        }

        //$limitConditional = 'LIMIT 0 , 1';

        $groupConditions = " GROUP BY product_meta.variation_id ";

        $tq =  $select. " {$params} {$groupConditions} {$orderConditional}  ";
        $q = $tq ." {$limitConditional} ";
        Log::info("Query : ". $q);

        $result = \DB::select($q);

        $totalRecords = \DB::select($tq);
        $total = count($totalRecords);

        return $results = array('total' => $total, 'query' => $q, 'totalQuery' => $tq, 'rows'=> $result);


    }
    public static function querySelectAPI($options = []){

//        $id = self::getDefaultExpenseCategoryQuery("expns_p.id", "id");
//        $item_description = self::getDefaultExpenseCategoryQuery("expns_p.item_description", "item_description");
//        $netsuite_description = self::getDefaultExpenseCategoryQuery("expns_p.netsuite_description", "netsuite_description");
//        $expense_category = self::getDefaultExpenseCategoryQuery("expns_p.expense_category", "expense_category");
//        $is_default_expense_category = self::getDefaultExpenseCategoryQuery("expns_p.is_default_expense_category", "is_default_expense_category");

//showAllAsActive
        $showAllAsActive = isset($showAllAsActive) && $showAllAsActive == 1;
        $postedToAPIDateQuery = "(NOW()-1 >= product_meta.posted_to_api_at AND NOW()+1 <= product_meta.posted_to_api_expired_at)";
        $inactive = $showAllAsActive ? "0": "IF($postedToAPIDateQuery, 0, products.inactive)";
        $retailPriceQuery = "IF(products.retail_price = 0.00, TRUNCATE(products.case_price/products.num_items,5), products.retail_price)";
        $updatedAt = "IF (ISNULL(product_meta.posted_to_api_at),products.updated_at, 
                        IF ($postedToAPIDateQuery, product_meta.posted_to_api_at, products.updated_at) 
                       )";

        $sql = "SELECT
                  
                  IFNULL(mp.id, products.id) AS id,
                  IFNULL(mp.item_description, products.item_description) AS item_description,
                  IFNULL(mp.netsuite_description, products.netsuite_description) AS netsuite_description,
                  IFNULL(mp.expense_category, products.expense_category) AS expense_category,
                  IFNULL(mp.is_default_expense_category, products.is_default_expense_category) AS is_default_expense_category,
                                    
                  products.sku,
                  products.vendor_description,
                  products.size,
                  products.details,
                  products.num_items,
                  products.vendor_id,
                  products.unit_price,
                  products.case_price,
                  products.is_reserved,
                  products.reserved_qty,
                  products.min_order_amt,
                  products.img,
                  products.inactive_by,
                  products.eta,
                  products.in_development,
                  products.limit_to_loc_group_id,
                  products.date_added,
                  products.hot_item,
                  products.created_at,
                  $updatedAt AS updated_at,
                  products.exclude_export,
                  products.allow_negative_reserve_qty,
                  products.reserved_qty_limit,
                  
                  GROUP_CONCAT(O.order_type)          AS prod_type_id,
                  GROUP_CONCAT(T.type_description)    AS prod_sub_type_id,
                  GROUP_CONCAT(products.id)           AS product_id,
                  GROUP_CONCAT($retailPriceQuery)     AS retail_price,
                  GROUP_CONCAT(products.ticket_value) AS ticket_value,
                  GROUP_CONCAT($inactive)             AS inactive
                  
                FROM  product_meta
                  LEFT JOIN products ON products.id = product_meta.product_id 
                  LEFT JOIN products mp ON mp.id = product_meta.variation_master_product_id 
                  LEFT JOIN order_type O ON (O.id = products.prod_type_id)
                  LEFT JOIN product_type T ON (T.id = products.prod_sub_type_id) ";


        return $sql;
    }


}
