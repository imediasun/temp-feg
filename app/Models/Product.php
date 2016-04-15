<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class product extends Sximo  {
	
	protected $table = 'products';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "SELECT products.*, O.order_type AS `prod_type`,vendor.vendor_name AS `vendor`,
 IF(products.hot_item = 1,CONCAT('',products.vendor_description,' **HOT ITEM**'),
 products.vendor_description) AS `prod_description`,ROUND(products.case_price/num_items,2) AS
  `unit_pricing`,T.type_description AS `product_type`,IF(products.inactive = 1,'NOT AVAIL.',CONCAT('Add to Cart'))
  AS `add`,CONCAT('Details') AS `addldetails`,products.id AS `product_id`,
  IF(products.retail_price = 0.00,ROUND(products.case_price/num_items,2),products.retail_price) AS `retail_price`
  FROM `products` LEFT JOIN vendor ON (products.vendor_id = vendor.id)
  LEFT JOIN order_type O ON (O.id = products.prod_type_id)
  LEFT JOIN product_type T ON (T.id = products.prod_sub_type_id)";
	}

	public static function queryWhere($product_list_type=null,$active=0){
		$return="WHERE products.id IS NOT NULL";
        if($product_list_type!= null)
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
                    $product_type_id=7;
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
            }
            if($product_list_type=="productsindevelopment")
            {
                $return.=" AND products.prod_type_id=".$product_type_id." AND  products.inactive = ".$active." AND products.in_development = 1";

            }
            else{
                $return.=" AND products.prod_type_id=".$product_type_id." AND  products.inactive = ".$active." AND products.in_development = 0";

            }
        }
        return $return;
	}
	
	public static function queryGroup(){
		return "  ";
	}
    public static function getRows( $args,$cond=null,$active=null)
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

        $offset = ($page-1) * $limit ;
        $limitConditional = ($page !=0 && $limit !=0) ? "LIMIT  $offset , $limit" : '';
        $orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " : '';

        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if($global == 0 )
            $params .= " AND {$table}.entry_by ='".\Session::get('uid')."'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        $select=self::querySelect();

        if($cond!=null )
        {
            $select.=self::queryWhere($cond,$active);
        }
        else
        {
            $select.=self::queryWhere();
        }
        $result=\DB::select($select." {$params} ". self::queryGroup() ." {$orderConditional}  {$limitConditional} ");
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
				{$params} ". self::queryGroup() ." {$orderConditional}  ");
            $total = count($total);
        }
        //$total = 1000;
        return $results = array('rows'=> $result , 'total' => $total);

    }



}
