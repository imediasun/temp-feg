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


        extract($options);
        /** @var  $showAllAsActive */
        /** @var  activeLimit */
        /** @var  $exposeInactive */
        if (isset($exposeInactive) && $exposeInactive == 1) {
            $select .= " OR products.inactive=1 ";
        }

        $createdFlag = false;
        if(!empty($createdFrom)){
            $select .= " AND (products.created_at BETWEEN '$createdFrom' AND '$createdTo'";
            $createdFlag = true;
        }
        if(!empty($updatedFrom)){

            if($createdFlag){
                $select .= " OR products.updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                $select .= " OR (product_meta.posted_to_api_at BETWEEN '$updatedFrom' AND '$updatedTo')";

            } else{
                $select .= " AND (products.updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                $select .= " OR (product_meta.posted_to_api_at BETWEEN '$updatedFrom' AND '$updatedTo') )";
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

        //$groupConditions = " GROUP BY product_meta.variation_id ";
        $groupConditions = " ";

        $tq =  $select. " {$params} {$groupConditions} {$orderConditional}  ";
        $q = $tq ." {$limitConditional} ";
        Log::info("Query : ". $q);

        $result = \DB::select($q);

        $totalRecords = \DB::select($tq);
        $total = count($totalRecords);

        return $results = array('total' => $total, 'query' => $q, 'totalQuery' => $tq, 'rows'=> $result);


    }
    public static function querySelectAPI($options = []){
        extract($options);
        /** @var  $showAllAsActive */
        /** @var  activeLimit */
        /** @var  $exposeInactive */

        $showAllAsActive = isset($showAllAsActive) && $showAllAsActive == 1;
        $exposeInactive = isset($exposeInactive) && $exposeInactive == 1;
        $postedToAPIDateQuery = "(NOW() >= product_meta.posted_to_api_at AND NOW() <= product_meta.posted_to_api_expired_at)";
        $inactive = $showAllAsActive || $exposeInactive ? "0": "IF($postedToAPIDateQuery, 0, products.inactive)";
        $retailPriceQuery = "IF(products.retail_price = 0.00, TRUNCATE(products.case_price/products.num_items,5), products.retail_price)";
        $updatedAt = "IF (ISNULL(product_meta.posted_to_api_at), products.updated_at, 
                        IF ($postedToAPIDateQuery, product_meta.posted_to_api_at, products.updated_at) 
                       )";


        $mAlias = 'mp';
        $masterFields = [
            'sku',
            'vendor_description',
            'size',
            'details',
            'num_items',
            'vendor_id',
            'unit_price',
            'case_price',
            'is_reserved',
            'min_order_amt',
            'eta',
            'img',
            'in_development',
            'limit_to_loc_group_id',
            'hot_item',
            'exclude_export',
            'reserved_qty',
            'allow_negative_reserve_qty',
            'reserved_qty_limit',
            'date_added',
            'expense_category',
        ];

        $vAlias = 'products';
        $variationFields = [
            'id',
            'item_description',
            'netsuite_description',
            'ticket_value',
            'inactive_by',
            'is_default_expense_category',
            'created_at',

            'inactive' => $inactive,
            'retail_price' => $retailPriceQuery,
            'updated_at' => $updatedAt,

            'prod_sub_type_id' => 'T.type_description',
            'prod_type_id' => 'O.order_type',
            'product_id' => 'products.id',
        ];

        $sql = "SELECT ";

        $selects = [];
        foreach($masterFields as $key => $def) {
            if (is_numeric($key)) {
                $selects[] = "IFNULL($mAlias.$def, $vAlias.$def) as `$def`";
            }
            else {
                $selects[] = "$def as `$key`";
            }
        }
        foreach($variationFields as $key => $def) {
            if (is_numeric($key)) {
                $selects[] = "$vAlias.$def as `$def`";
            }
            else {
                $selects[] = "$def as `$key`";
            }
        }

        $selectsSeparator = ",\r\n";
        $selectsQuery = implode($selectsSeparator, $selects);
        if (empty($selectsQuery)) {
            $selectsQuery = "*";
        }
        $sql .= $selectsQuery;

        $sql .= " FROM  product_meta
                  LEFT JOIN products ON products.id = product_meta.product_id 
                  LEFT JOIN products mp ON mp.id = product_meta.variation_master_product_id 
                  LEFT JOIN order_type O ON (O.id = products.prod_type_id)
                  LEFT JOIN product_type T ON (T.id = products.prod_sub_type_id) ";

        return $sql;
    }


}
