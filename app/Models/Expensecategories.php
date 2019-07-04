<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use Elasticsearch\Client;
use App\Repositories\Expensecategories\ExpensecategoriesRepository;
use App\Repositories\Expensecategories\ElasticsearchExpensecategoriesRepository;
use App\Search\Searchable;
use Elasticsearch\ClientBuilder;

class expensecategories extends Sximo  {

    use Searchable;
	protected $table = 'expense_category_mapping';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
			
		return "  SELECT expense_category_mapping.*, order_type.order_type as order_type_name, product_type.type_description 
  				  FROM expense_category_mapping
  				  INNER JOIN order_type ON (expense_category_mapping.order_type = order_type.id)
  				  LEFT JOIN product_type ON (expense_category_mapping.product_type = product_type.id)  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE expense_category_mapping.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    public static function getRows($args, $cond = null, $active = null, $sub_type = null, $is_api = false)
    {

        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'order' => '',
            'params' => '',
            'global' => 1,
            'vendor_id' => '',
            'case_price' => '',
            'sku' => '',
            'vendor_description' => '',
        ), $args));
        //session_start();
        if(isset($_SESSION['expensecategories_search'])&& !empty($_SESSION['expensecategories_search'])){
            $explode_string=explode('|',$_SESSION['expensecategories_search']);
            //dump($explode_string);
            $second_explode=explode(':',$explode_string[0]);
            $elastic = function (ExpensecategoriesRepository $repository)  {
                if(isset($_SESSION['expensecategories_search']) && !empty($_SESSION['expensecategories_search']) ){
                    $explode_string=explode('|',$_SESSION['expensecategories_search']);
                    //dump($explode_string);
                    $result['vendor'] = null;
                    $result['prod_sub_type_id'] = null;
                    $result['prod_type_id'] = null;
                    $result['in_development'] = null;
                    $result['upc_barcode'] = null;
                    $result['inactive'] = null;
                    foreach ($explode_string as $k => $param) {
                        $second_explode = explode(':', $param);
                        switch ($second_explode[0]) {
                            case 'search_all_fields':
                                $main_search = $second_explode[2];
                                break;
                            case 'vendor_id':
                                $result['vendor'] = $second_explode[2];
                                break;
                            case 'prod_sub_type_id':
                                $result['prod_sub_type_id'] = $second_explode[2];
                                break;
                            case 'prod_type_id':
                                $result['prod_type_id'] = $second_explode[2];
                                break;
                            case 'in_development':
                                $result['in_development'] = $second_explode[2];
                                break;
                            case 'upc_barcode':
                                $result['upc_barcode'] = $second_explode[2];
                                break;
                            case 'inactive':
                                $result['inactive'] = $second_explode[2];
                                break;


                        }
                        //dump($second_explode);
                    }
                    //$second_explode=explode(':',$explode_string[0]);

                }
                if (empty($main_search)) {
                    unset($_SESSION['expensecategories_search']);
                } else {
                    $result['expensecategories'] = $repository->search((string)$main_search);
                    //dump('elastic',$result['products']);
                    return $result;
                }
                return false;
            };
            $client = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
            $el = new ElasticsearchExpensecategoriesRepository($client);
            $pre_products = $elastic($el);

            if ($pre_products['expensecategories'] != null) {

                $products = $pre_products['expensecategories'];

                //dump('products', $products);
                $total = count($products/*$pre_products['orders']*/);
                $search_total = $total;
                //dump('total1=>',$total);
                $offset = ($page - 1) * $limit;
                if ($offset >= $total && $total != 0 && $limit != 0) {
                    $page = ceil($total / $limit);
                    $offset = ($page - 1) * $limit;
                }
                if ($total > 0) {
                    $products = $products->chunk($limit);/*$pre_products['orders']*/
                    $products = $products[$page - 1];
                }


                $products=(is_array($products)) ? $products : $products->toArray();
                foreach($products as $k=>$v){
                    $object = new \stdClass();
                    foreach($v as $key=>$value){


                        $object->$key=$value;
                        $_products[$k]=$object;

                    }
                }

             $products=$_products;
             //dump('$products=>',$_products);
            }
        }


        if ($sort == 'prod_type_id' || $sort == 'prod_sub_type_id') {
            $sort = "products." . $sort;
        }
        $offset = ($page - 1) * $limit;
        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';
        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY $sort {$order} " : '';

        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if ($global == 0)
            $params .= " AND {$table}.entry_by ='" . \Session::get('uid') . "'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        if ($is_api) {
            $select = self::querySelectAPI();
        } else {
            $select = self::querySelect();
        }

        $createdFlag = false;

        if ($cond != null) {
            $select .= self::queryWhere($cond, $active, $sub_type);
        } else {
            $select .= self::queryWhere();
        }

        if (!empty($createdFrom)) {
            $select .= " AND products.created_at BETWEEN '$createdFrom' AND '$createdTo'";
            $createdFlag = true;
        }

        if (!empty($updatedFrom)) {

            if ($createdFlag) {
                $select .= " OR products.updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
            } else {
                $select .= " AND products.updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
            }

        }

        if (!empty($prod_type_id)) {
            $select .= " AND prod_type_id='$prod_type_id'";
        }
        if (!empty($vendor_id)) {
            $select .= " AND vendor_id='$vendor_id'";
        }
        if (!empty($vendor_id)) {
            $select .= " AND products.vendor_id='$vendor_id'";
        }
        if (!empty($case_price)) {
            $select .= " AND products.case_price='$case_price'";
        }
        if (!empty($vendor_description)) {
            $select .= " AND products.vendor_description='$vendor_description'";
        }
        if (!empty($sku)) {
            $select .= " AND products.sku='$sku'";
        }

        //$limitConditional = 'LIMIT 0 , 1';

        if ($is_api) {
            $groupConditions = self::queryGroupAPI();
        } else {
            $groupConditions = self::queryGroup();
        }

        //Log::info("Query : ".$select . " {$params}  {$groupConditions} {$orderConditional}  {$limitConditional} ");
        if(isset($_SESSION['expensecategories_search'])&& !empty($_SESSION['expensecategories_search'])){
            $result=$products;
            unset($_SESSION['expensecategories_search']);
        } else {
            $result = \DB::select($select . " {$params} {$groupConditions} {$orderConditional}  {$limitConditional} ");
        }
        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }
        $counter_select = preg_replace('/[\s]*SELECT(.*)FROM/Usi', 'SELECT count(' . $key . ') as total FROM', self::querySelect());
        //total query becomes too huge

        if (!isset($search_total)) {
            //dump('sesnon');
            if ($table == "orders") {
                $total = 20000;
            } else {

                $total = \DB::select($select . "
				{$params} {$groupConditions} {$orderConditional}  ");
                $total = count($total);
            }
        } else {
            // dump('here');
            $total = $search_total;
        }

        //$total = 1000;
        //dump('total2=>',$total);
        return $results = array('rows' => $result, 'total' => $total);

    }


    public function orderType()
    {
        return $this->hasOne('App\Models\OrderType','id','order_type');
    }



}
