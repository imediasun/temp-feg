<?php namespace App\Models;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopfegrequeststoreController;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Groups;
use Illuminate\Support\Facades\Session;
use Log;
use App\Http\Controllers\AddtocartController;
//use App\Http\Controllers\OrderController;

class addtocart extends Sximo
{

    protected $table = 'requests';
    protected $primaryKey = 'id';
    public $passes = '';
    public $timestamps = false;

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {
        $subQueries = self::subQueriesProductsSelect();
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
  products.hot_item,
  V1.vendor_name,
  order_type.order_type,
  product_type.type_description,
  IF(products.reserved_qty = 0, '', products.reserved_qty) AS reserved_qty,
  (products.reserved_qty - requests.qty) AS reserved_difference,
  products.prod_type_id,
  products.prod_sub_type_id,
  $subQueries
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
    public function hasPermission(){
        $module = new ShopfegrequeststoreController();
        $this->passes = \FEGSPass::getPasses($module->module_id, 'module.shopfegrequeststore.special.allowusers/usergroupstosubmitthepurchaserequestinspiteoftheerrormessage', false);
        $userId = \Session::get('uid');
        $groupId = \Session::get('gid');
        $addToCartPermission = $this->passes['Allow users/user groups to submit the purchase request in spite of the error message'];
        $userAllowed = explode(",",$addToCartPermission->user_ids);
        $groupAllowed = explode(",",$addToCartPermission->group_ids);
        $excludeUserIds = explode(",",$addToCartPermission->exclude_user_ids);
        return (in_array($userId,$userAllowed) || in_array($groupId,$groupAllowed)) && !in_array($userId,$excludeUserIds);
    }
    public function getsubmittedRequests($productIds){
        if(!is_array($productIds)){
            $productIds = [$productIds];
        }
        $locationId = \Session::get('selected_location');
        $products = self::join("products",'products.id','=','requests.product_id')
                    ->where("requests.location_id",$locationId)
                    ->whereIn('requests.product_id',$productIds)->where("requests.status_id",'=','1')
                    ->groupBy('requests.product_id')->get();
        if($products->count()){
            $productsNames = "<ul style='padding-left: 17px;margin-bottom: 0px; text-align:left !important;'>";
            $count = $products->count();
            foreach ($products as $key => $request){
                $productsNames .= "<li>".addslashes($request->vendor_description)."</li>";
            }
            $productsNames .= "</ul>";
            return "Another employee at your location has already ordered the following product(s):<br><br> $productsNames <br>Would you like to submit this order request anyway?";
        }else{
            return '';
        }
    }
    /*
     * mergeRequests() method accepts addtocart object
     * this method is used to merge requested item if it already has been ordered by some other user on the same location
     * replacing requested user id with current user id and requested date with current date
     */
    public function mergeRequests($newRequest){
        $now = date('Y-m-d');
        $request = $this->where("product_id",$newRequest->product_id)->where("status_id",1)->where("location_id",\Session::get('selected_location'))->first();
        if($request){
            $request->qty = $request->qty + $newRequest->qty;
            $request->request_user_id = \Session::get('uid');
            $request->request_date = $now;
            unset($request->updated_at);
            $request->save();
            self::where("id",$newRequest->id)->delete();
        }
    }

    public function getNewRequests($productIds = []){
       return $this->whereIn("product_id",$productIds)
            ->where("request_user_id",\Session::get('uid'))
            ->where("status_id",4)
            ->where("location_id",\Session::get('selected_location'));
    }

    public function requestQtyFilterCheck($productIds, $checkSingle = true)
    {
        if(!is_array($productIds)){
            $productIds = [$productIds];
        }
        sort($productIds);
        $productIds = array_values($productIds);

        $products = $this->getProducts($productIds);

        $productIdsWithRequestedQuantitiesList = $this->getRequestObjects($productIds)->toArray();
//dd($productIdsWithRequestedQuantitiesList);
        $variants = $this->returnVariantsAgainstRequestedProductIds(array_keys($productIdsWithRequestedQuantitiesList));

//        dd($productIdsWithRequestedQuantitiesList);

        foreach ($variants as $variantItemsArray){
            if(count($variantItemsArray) > 1){
                $product = $products->filter(function($item) use ($variantItemsArray) {
                    return in_array($item->id, $variantItemsArray);
                })->first();
                $reservedQty = $product->reserved_qty;

                $totalRequestedQTYForVariants = 0;
                foreach ($variantItemsArray as $productIds){
                    $totalRequestedQTYForVariants += $productIdsWithRequestedQuantitiesList[$productIds];
                }

                $productsForError = collect([]);
                if($totalRequestedQTYForVariants > $reservedQty){
                    $productsForError = $products->filter(function($item) use ($variantItemsArray) {
                        return in_array($item->id, $variantItemsArray);
                    });
                }
                $errorString = '';
                if($productsForError->count() > 0){
                    dd($productsForError);
                    $errorString .= $this->makeErrorStringForVariants($productsForError);
                    dd($errorString);
                    return $errorString;
                }else{
                    return '';
                }
//                $productsForError =
            }
        }

        $requestsArray = [];

        foreach($productIdsWithRequestedQuantitiesList as $productId=>$requestedQTY){
            $alreadyRequestedQuantity = \DB::table('requests')->where('product_id', $productId)->where('qty', '!=', $requestedQTY)->where('status_id', 1)->sum('qty');
            $reservedQty = \DB::table('products')->where('id', $productId)->pluck('reserved_qty');
            $column = [
                'requests.id',
                'requests.product_id',
                'products.vendor_description',
                \DB::raw('products.reserved_qty as productQty'),
                \DB::raw($alreadyRequestedQuantity.' as alreadyRequestedQTY'),
                \DB::raw(($reservedQty - $alreadyRequestedQuantity).' as remainingQTY'),
                'requests.request_user_id',
                'requests.location_id'
            ];
            $requestss = $this->select($column)->
            join('products', 'products.id', '=', 'requests.product_id')
                ->groupBy("requests.product_id")
                ->where("requests.product_id", $productId);

            if($checkSingle)
                $requestss = $requestss->whereIn("requests.status_id", [4]);
            else
                $requestss = $requestss->whereIn("requests.status_id", [1,4]);

            $requestss = $requestss->where("requests.location_id", \Session::get('selected_location'))
                ->where('products.allow_negative_reserve_qty', '=', 0)
                ->where('products.is_reserved', '=', 1);

//            if($checkSingle)
//                $requestss = $requestss->having('productQty', '<', $requestedQTY);
//            else
                $requestss = $requestss->having('remainingQTY', '<', $requestedQTY);

            if($requestss->first()){
                $requestsArray[] = $requestss->first();
            }
        }
//        dd($requestsArray);
        $requestsArray = collect($requestsArray);
        return $requestsArray;
    }

    private function makeErrorStringForVariants($products){
        $productsNames = "<ul style='padding-left: 17px;margin-bottom: 0px; text-align:left !important;'>";
        foreach ($products as $request) {
            $productsNames .= "<li>" . addslashes($product->vendor_description) . " | Reserve Qty = ".$product->productQty." | Already Requested Qty = ".$request->alreadyRequestedQTY." | Remaining Qty = ".$request->remainingQTY."</li>";
        }
        $productsNames .= "</ul>";
        //return redirect('/addtocart')->with('messagetext', "You are unable to submit request as following product(s) doesn't allow the negative reserved quantity: $productsNames Please remove product(s) or adjust quantity before submitting the request.")->with('msgstatus', 'error');
        return $qtyCheckMessage = [
            'messagetext' => "Your request cannot be submitted because there is not enough reserve qty to allow the purchase.<br /><br /> $productsNames <br />Please reduce the amount requested for purchase below or contact the Merchandise Team.",
            'showError' => $requestQtyCheck->count() > 0,
        ];
    }

    /**
     * calculates products total according to product type defined in configuration
     * @param array $productIds
     * @return Collection
     * */
    private function getRequestObjects($productIds){
        $column = [
            'requests.product_id',
            \DB::raw('requests.qty as requestedQTY'),
        ];
        $requests = $this->select($column)->
        join('products', 'products.id', '=', 'requests.product_id')
            ->groupBy("requests.product_id")
            ->whereIn("requests.product_id", $productIds)
            ->whereIn("requests.status_id", [4])
            ->where("requests.request_user_id", \Session::get('uid'))
            ->where("requests.location_id", \Session::get('selected_location'))
            ->where('products.allow_negative_reserve_qty', '=', 0)
            ->where('products.is_reserved', '=', 1)
            ->lists('requestedQTY', "requests.product_id");
        return $requests;
    }
}
