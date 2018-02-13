<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Log;

class product extends Sximo  {
	
	protected $table = 'products';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "SELECT products.*, O.order_type AS `prod_type`,vendor.vendor_name AS `vendor`,
 IF(products.hot_item = 1,CONCAT('',products.vendor_description,' **HOT ITEM**'),
 products.vendor_description) AS `prod_description`,TRUNCATE(products.case_price/num_items,5) AS
  `unit_pricing`,T.type_description AS `product_type`,IF(products.inactive = 1,'NOT AVAIL.',CONCAT('Add to Cart'))
  AS `add`,CONCAT('Details') AS `addldetails`,products.id AS `product_id`,
  IF(products.retail_price = 0.00,TRUNCATE(products.case_price/num_items,5),products.retail_price) AS `retail_price`,
  O.order_type AS prod_type_id,
  T.type_description AS prod_sub_type_id
  FROM `products` LEFT JOIN vendor ON (products.vendor_id = vendor.id)
  LEFT JOIN order_type O ON (O.id = products.prod_type_id)
  LEFT JOIN product_type T ON (T.id = products.prod_sub_type_id)";
	}

    public static function querySelectAPI(){
        return "SELECT products.*, 
                GROUP_CONCAT(O.order_type) AS `prod_type`,
                GROUP_CONCAT(vendor.vendor_name) AS `vendor`,
                GROUP_CONCAT(IF(products.hot_item = 1,CONCAT('',products.vendor_description,' **HOT ITEM**'), products.vendor_description)) AS `prod_description`,
                GROUP_CONCAT(TRUNCATE(products.case_price/num_items,5)) AS `unit_pricing`,
                GROUP_CONCAT(T.type_description) AS `product_type`,
                GROUP_CONCAT(products.id) AS `product_id`,
                GROUP_CONCAT(IF(products.retail_price = 0.00,TRUNCATE(products.case_price/num_items,5),products.retail_price)) AS `retail_price`,
                GROUP_CONCAT(O.order_type) AS prod_type_id,
                GROUP_CONCAT(T.type_description) AS prod_sub_type_id,
                GROUP_CONCAT(expense_category) AS expense_category,
                GROUP_CONCAT(ticket_value) AS ticket_value,
                GROUP_CONCAT(inactive) AS inactive
                
                FROM `products` LEFT JOIN vendor ON (products.vendor_id = vendor.id)
                LEFT JOIN order_type O ON (O.id = products.prod_type_id)
                LEFT JOIN product_type T ON (T.id = products.prod_sub_type_id)";
    }

	public static function queryWhere($product_list_type=null,$active=0,$sub_type=null){
        $return="WHERE products.id IS NOT NULL";

        if($product_list_type!= null && $product_list_type != "select" )
        {
            $product_type_id='';
            switch($product_list_type)
            {
                case 'redemption':
                    $product_type_id=7;
                    break;
                case 'instant':
                    $product_type_id=8;
                    break;
                case 'other':
                    $product_type_id=4;
                    break;
                case 'graphics':
                    $product_type_id=10;
                    break;
                case 'ticketokens':
                    $product_type_id=4;
                    break;
                case 'party':
                    $product_type_id=17;
                    break;
                case 'officesupplies':
                    $product_type_id=6;
                    break;
                case 'parts':
                    $product_type_id=1;
                    break;
                case 'tickets':
                    $product_type_id=22;
                    break;
                case 'tokens':
                    $product_type_id=23;
                    break;
                case 'uniforms':
                    $product_type_id=24;
                    break;
                case 'photopaper':
                    $product_type_id=25;
                    break;
                case 'debitcards':
                    $product_type_id=26;
                    break;
                case 'advancereplacement':
                    $product_type_id=2;
                    break;
            }
           // unset();
            \Session::put('product_type_id',$product_type_id);
            \Session::put('product_type',$product_list_type);

            if($product_list_type == "productsindevelopment")
            {
                if($sub_type != null)
                {

                    \Session::put('sub_type',$sub_type);
                    $return.=" AND products.prod_type_id=".$product_type_id." AND products.prod_sub_type_id=".$sub_type." AND products.in_development = 1";
                }
              else {
                  \Session::put('sub_type',"");
                  $return .= " AND products.in_development = 1";

              }
            }
            else{
                if($sub_type != null)
                {
                    \Session::put('sub_type',$sub_type);
                    $return.=" AND products.prod_type_id=".$product_type_id." AND products.prod_sub_type_id=".$sub_type."  AND products.in_development = 0";
                }
                else {
                    \Session::put('sub_type',"");
                    $return .= " AND products.prod_type_id=" . $product_type_id . "  AND products.in_development = 0";
                }
            }

        }
        else
        {
            \Session::put('product_type_id',"");
            \Session::put('product_type',"");
            if($sub_type !=null)
            {
                \Session::put('sub_type',$sub_type);
                $return .=" AND products.prod_sub_type_id=".$sub_type." AND products.in_development = 0";
            }
            else{
                \Session::put('sub_type',"");
            }
            return $return;
        }
        return $return;
	}
	
	public static function queryGroup(){
		return "  ";
	}

    public static function queryGroupAPI(){
        return " GROUP BY vendor_description, sku ";
    }

    public static function getRows( $args,$cond=null,$active=null,$sub_type=null, $is_api=false)
    {

        $table = with(new static)->table;
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

        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if($global == 0 )
            $params .= " AND {$table}.entry_by ='".\Session::get('uid')."'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        if($is_api){
            $select=self::querySelectAPI();
        }else{
            $select=self::querySelect();
        }

        $createdFlag = false;

        if($cond!=null )
        {
            $select.=self::queryWhere($cond,$active,$sub_type);
        }
        else
        {
            $select.=self::queryWhere();
        }

        if(!empty($createdFrom)){
            $select .= " AND products.created_at BETWEEN '$createdFrom' AND '$createdTo'";
            $createdFlag = true;
        }

        if(!empty($updatedFrom)){

            if($createdFlag){
                $select .= " OR products.updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
            }
            else{
                $select .= " AND products.updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
            }

        }

        if(!empty($prod_type_id)){
            $select .= " AND prod_type_id='$prod_type_id'";
        }
        if(!empty($vendor_id)){
            $select .= " AND vendor_id='$vendor_id'";
        }

        //$limitConditional = 'LIMIT 0 , 1';

        if($is_api){
            $groupConditions = self::queryGroupAPI();
        }else{
            $groupConditions = self::queryGroup();
        }


        Log::info("Query : ".$select . " {$params}  {$groupConditions} {$orderConditional}  {$limitConditional} ");

        $result=\DB::select($select." {$params} {$groupConditions} {$orderConditional}  {$limitConditional} ");
        if($key =='' ) { $key ='*'; } else { $key = $table.".".$key ; }
        $counter_select = preg_replace( '/[\s]*SELECT(.*)FROM/Usi', 'SELECT count('.$key.') as total FROM', self::querySelect() );
        //total query becomes too huge
        if($table == "orders")
        {
            $total = 20000;
        }
        else
        {
            $total = \DB::select( $select. "
				{$params} {$groupConditions} {$orderConditional}  ");
            $total = count($total);
        }
        //$total = 1000;
        return $results = array('rows'=> $result , 'total' => $total);

    }

    public function checkProducts($id){
        $product = DB::table('products')->where(['id'=>$id])->first();

        $products = DB::table('products')->where(['vendor_description'=>$product->vendor_description,'sku'=>$product->sku])->get();

        return $products;
    }

    public function checkIsDefaultExpenseCategory($id)
    {
        $product = DB::table('products')->where(['id' => $id])->first();
        if ($product->is_default_expense_category == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function hasDefaultExpenseCategory($id)
    {

        $searchProduct = $this->checkIsDefaultExpenseCategory($id);

        $products = $this->checkProducts($id);
        if (count($products) == 1) {
            return false;
        } elseif ($searchProduct == true) {
            return true;
        } elseif ($searchProduct == false) {
            return false;
        } elseif (count($products) > 1) {
            foreach ($products as $product) {
                if ($product->is_default_expense_category == 1) {
                    return true;
                }
            }
        }
        return false;
    }

    public function setFirstDefaultExpenseCategory($id)
    {
        $products = $this->checkProducts($id);
        $first = 1;
        foreach ($products as $product) {
            $item = self::find($product->id);
            if ($first == 1) {
                $item->is_default_expense_category = 1;
                $first = 2;
            } else {
                $item->is_default_expense_category = 0;
            }
            $item->save();
        }
    }

    public function setDefaultExpenseCategory($id)
    {
        $products = $this->checkProducts($id);

        foreach ($products as $product) {
            $item = self::find($product->id);
            if ($id == $item->id) {
                $item->is_default_expense_category = 1;
            } else {
                $item->is_default_expense_category = 0;
            }
            $item->save();
        }
    }


}
