<?php namespace App\Models;

use App\Http\Controllers\OrderController;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Groups;
use Illuminate\Support\Facades\Session;
use Log;
//use App\Http\Controllers\OrderController;

class addtocart extends Sximo
{

    protected $table = 'requests';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {
        return "SELECT
  requests.*,
  (SELECT
     COALESCE(SUM(qty),0)
   FROM requests
   WHERE location_id = location.id
       AND status_id = 1
       AND product_id = products.id) AS already_order_qty,
  u1.username,
  products.img,
  IF(product_id = 0, requests.description, products.item_description) AS description,
  products.vendor_description,
  products.sku,
  products.unit_price,
  products.case_price,
  products.retail_price,
    '' AS lineTotal,
  products.ticket_value,
  location.location_name_short,
  merch_request_status.status,
  products.size,
  products.num_items,
  V1.vendor_name,
  order_type.order_type,
  product_type.type_description,
  IF(products.reserved_qty = 0, '', products.reserved_qty) AS reserved_qty,
  (products.reserved_qty - requests.qty) AS reserved_difference,
  products.prod_type_id,
  products.prod_sub_type_id
FROM requests
  LEFT JOIN users u1
    ON (requests.request_user_id = u1.id)
  LEFT JOIN products
    ON (requests.product_id = products.id)
  LEFT JOIN vendor V1
    ON (products.vendor_id = V1.id)
  LEFT JOIN location
    ON (requests.location_id = location.id)
  LEFT JOIN merch_request_status
    ON (requests.status_id = merch_request_status.id)
  LEFT JOIN order_type
    ON (order_type.id = products.prod_type_id)
  LEFT JOIN product_type
    ON (product_type.id = products.prod_sub_type_id)";
    }

    public static function queryWhere()
    {
        $where="WHERE requests.id IS NOT NULL ";
        $data['user_level'] = \Session::get('gid');
//
//
//        if ($data['user_level'] == Groups::MERCHANDISE_MANAGER || $data['user_level'] == Groups::FIELD_MANAGER || $data['user_level'] == Groups::OFFICE_MANAGER || $data['user_level'] == Groups::FINANCE_MANAGER || $data['user_level'] == Groups::GUEST || $data['user_level'] == Groups::SUPPER_ADMIN) {
//            $where.= " AND requests.location_id = " . \Session::get('selected_location') . " AND requests.status_id = 9"; /// 9 IS USED AS AN ARBITRARY DELIMETER TO KEEP CART SEPERATE FROM LOCATIONS' OWN
//        } else {
//            $where.= " AND requests.location_id = " . \Session::get('selected_location') . " AND requests.status_id = 4";
//        }
        $where.= " AND requests.location_id = " . \Session::get('selected_location') . " AND requests.status_id = 4 AND requests.request_user_id = " . \Session::get('uid');
        return $where ;
    }

    public function getCartData($productId)
    {
        return self::where('product_id', $productId)->where('request_user_id', \Session::get('uid'))
            ->where('location_id', \Session::get('selected_location'))
            ->where('status_id', 4)
            ->first();
    }

    public static function queryGroup()
    {
        return "  ";
    }
    

    function popupCartData($productId=null,$v1=null,$qty=0)
    {

        $data['user_level']=\Session::get('gid');
        $userID = \Session::get('uid');

        // TODO: Remove the false and the whole logic related to PARTNER [comment 24 June 2017]
        if (false && $data['user_level'] == Groups::PARTNER)
        {
            //redirect('./dashboard', 'refresh');
            return false;
        }
        else
        {

            $locationId = \Session::get('selected_location');

            if(empty($locationId)){
                return false;
            }

            /*if ($data['user_level'] == Groups::MERCHANDISE_MANAGER || $data['user_level'] == Groups::FIELD_MANAGER || $data['user_level'] == Groups::OFFICE_MANAGER || $data['user_level'] == Groups::FINANCE_MANAGER || $data['user_level'] == Groups::GUEST || $data['user_level'] == Groups::SUPPER_ADMIN)
            {
                $statusId = 9; /// 9 IS USED AS AN ARBITRARY DELIMETER TO KEEP CART SEPERATE FROM LOCATIONS' OWN
            }
            else
            {
                $statusId = 4;
            }*/
            $statusId = 4;
            if(!empty($productId) &&!empty($qty))
            {
               // $qty = 1;

                $query = \DB::select('SELECT id FROM requests WHERE product_id = "'.$productId.'" AND status_id = "'.$statusId.'" AND request_user_id = "'.$userID.'" AND location_id = "'.$locationId.'"');

                /// TO AVOID ADDITNG THE SAME PRODUCT IN TWO PLACES
                if (count($query) == 0)
                {

                    $now = date('Y-m-d');
                    $insert = array(
                        'product_id' => $productId,
                        'location_id' => $locationId,
                        'request_user_id' => \Session::get('uid'),
                        'request_date' => $now,
                        'qty' => $qty,
                        'status_id' => $statusId
                    );
                    \DB::table('requests')->insert($insert);
                }
            }
            $location_id = \Session::get('selected_location');

            $data['selected_location'] = $location_id;


            // SHOPPING CART TOTALS (SHOWN ABOVE CART) START
            $data['total_cart_items'] = '';
            $data['shopping_cart_total'] = 0;
            $data['amt_short'] = '';
            $data['amt_short_message'] = '';
            $module = new OrderController();
            $pass = \FEGSPass::getMyPass($module->module_id, '', false, true);
            global $casePriceOrders,$unitPriceOrders;
            $casePriceOrders = $unitPriceOrders = "";
            if(!empty($pass['calculate price according to case price'])) {
                $casePriceOrders = explode(",", $pass['calculate price according to case price']->data_options);
            }
            if(!empty($pass['use case price if unit price is 0.00'])) {
                $unitPriceOrders = explode(",", $pass['use case price if unit price is 0.00']->data_options);
            }

                                       $select='SELECT V.vendor_name,  V.id AS vendor_id, V.min_order_amt, SUM(R.qty*P.case_price) AS total, COUNT(V.id) AS cart_items,
                                       V.min_order_amt - SUM(R.qty*P.case_price) AS amt_short,P.prod_type_id,P.case_price,P.unit_price,R.qty FROM requests R
                                       LEFT JOIN products P ON P.id = R.product_id
								       LEFT JOIN vendor V ON V.id = P.vendor_id
									   WHERE R.status_id = "' . $statusId . '" AND V.vendor_name !="null"
									   AND R.location_id = "' . $location_id . '"
									   AND R.request_user_id = "' . $userID . '"
                                       GROUP BY V.vendor_name';
            if($v1)
            {
                $select .= ' HAVING V.vendor_name="'.$v1.'"';
            }
            Log::info('Query for Add to cart Sub total: '.$select);

            $query = \DB::select($select);


            $cartProductsAddedByUser = $this->getCartProductsAddedByUser($location_id,$userID);
            $cartProductsAddedByUser = collect($this->calculateProductTotalAccordingToProductType($cartProductsAddedByUser));
            $cartLineTotal = $cartProductsAddedByUser->groupBy('vendor_id')->map(function ($row) {
                $row->total = $row->sum('lineTotal');
                $row->lineTotal = $row->sum('lineTotal');
                return $row;
            });

            $amt_short_message="";
            foreach ($query as $row)
            {
                $row = array(
                    'vendor_name' => $row->vendor_name,
                    'vendor_id' => $row->vendor_id,
                    'vendor_min_order_amt' => \CurrencyHelpers::formatPrice($row->min_order_amt, Order::ORDER_PERCISION, false),
                    'vendor_total' => \CurrencyHelpers::formatPrice($cartLineTotal[$row->vendor_id]->lineTotal, Order::ORDER_PERCISION, false),
                    'cart_items' => $row->cart_items,
                    'total'=> $cartLineTotal[$row->vendor_id]->total,
                    'amt_short' => \CurrencyHelpers::formatPrice($row->amt_short, Order::ORDER_PERCISION, false)
                );

                $array[] = $row;


                if($row['amt_short'] > 0)
                {
                    $amt_short_message  .= $data['amt_short_message'].$row['vendor_name'].' order is short by $'.$row['amt_short'].'. ';
                }

                $data['shopping_cart_total'] = ($data['shopping_cart_total'] + $row['total']);
                $data['total_cart_items'] += $row['cart_items'];

            }
            $data['shopping_cart_total'] = \CurrencyHelpers::formatPrice($data['shopping_cart_total'], Order::ORDER_PERCISION, false);
            $data['amt_short_message']=$amt_short_message;
            if(isset($array))
            {
                $data['subtotals'] = $array;
            }
            else
            {
                $data['empty']="";
            }
            // SHOPPING CART TOTALS (SHOWN ABOVE CART) END

            // NEW PRODUCTS (SHOWN ABOVE STORE) START
            $today = date('Y-m-dd');
            //$this->load->library('dateoperations');

            /// USE SELECTED LOCATION TO GET LOCATION NAME
            $query = \DB::select('SELECT location_name_short FROM location WHERE id='.$location_id.'');
            if (count($query) == 1)
            {
                $data['title_2'] = 'Cart - '.$query[0]->location_name_short;
            }

            return $data;
        }

    }
    public static function destroy($ids)
    {

        // We'll initialize a count here so we will return the total number of deletes
        // for the operation. The developers can then check this number as a boolean
        // type value or get this total count of records deleted for logging, etc.
        $count = 0;



        // We will actually pull the models from the database table and call delete on
        // each of them individually so that their events get fired properly with a

        $selected_location=\Session::get('selected_location');
        $update=array('status_id' => '2');

        foreach ($ids as $rid) {
            \DB::update("update  requests set status_id=2 where id='".$rid."' AND location_id =".$selected_location);

        $count++;
        }


        return $count;
    }

    /**
     * calculates products total according to product type defined in configuration
     * @param array $data
     * @return array
     */
    public function calculateProductTotalAccordingToProductType(array $data){
        $module = new OrderController();
        $pass = \FEGSPass::getMyPass($module->module_id, '', false, true);
        $casePriceOrders = explode(",",@$pass['calculate price according to case price']->data_options);
        foreach($data as $product){

            $product = is_array($product)?(object)$product:$product;

            if(in_array($product->prod_type_id,$casePriceOrders)){
                $product->lineTotal = $product->case_price * $product->qty;
            }
            else{
                Product::calculateLineTotalForUnitPrice($product);
            }
        }
        return $data;
    }
    function getCartProductsAddedByUser($location_id,$userID){

        $cartData =  self::where("requests.location_id",$location_id)
            ->select('products.prod_type_id', 'products.unit_price','products.case_price','products.vendor_id','requests.qty')
            ->where('requests.request_user_id', $userID)
            ->where('requests.status_id',4)->join("products",'requests.product_id','=','products.id')->get()->all();
            return $cartData;
    }
}
