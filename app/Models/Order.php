<?php namespace App\Models;

use App\Http\Controllers\OrderController;
use App\Library\FEG\System\FEGSystemHelper;
use App\Models\Feg\System\Options;
use App\Models\Sximo\Module;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ordertyperestrictions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Log;
use Illuminate\Support\Facades\File;
use App\Search\Searchable;
use App\Repositories\Orders\OrdersRepository;
use App\Repositories\Orders\ElasticsearchOrdersRepository;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;
class order extends Sximo
{
    use SoftDeletes;
    use Searchable;

    protected $casts = [
        'tags' => 'json',
    ];


    protected $table = 'orders';
    protected $primaryKey = 'id';
    const OPENID1 = 1, OPENID2 = 3, OPENID3 = 4, FIXED_ASSET_ID = 9, CLOSEID1 = 2, CLOSEID2 = 5;
    const ORDER_PERCISION = 5;
    const ORDER_TYPE_PART_GAMES = 1;
    const ORDER_VOID_STATUS = 9;
    const ORDER_INSTALLED_AND_RETURNED_STATUS = 6;
    const ORDER_CLOSED_STATUS = [2,6];
    const ORDER_TYPE_TICKET_TOKEN_UNIFORM = [4,22,23,24,25,26];
    const ORDER_TYPE_REDEMPTION = 7;
    const ORDER_TYPE_INSTANT_WIN_PRIZE = 8;
    const ORDER_TYPE_OFFICE_SUPPLIES = 6;
    const ORDER_TYPE_MARKETING = 17;
    const ORDER_TYPE_UNIFORM = 24;
    const ORDER_TYPE_REPAIR_LABOUR = 3;
    const ORDER_TYPE_ADVANCED_REPLACEMENT = 2;
    const ORDER_TYPE_PRODUCT_IN_DEVELOPMENT = 18;

    const ORDER_DELETED_STATUS = 10;
    const ORDER_ACTIVE_STATUS = 1;

    public function __construct()
    {
        ini_set('memory_limit','1G');
        set_time_limit(0);
        parent::__construct();

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderedContent()
    {
        return $this->hasMany("App\Models\OrderedContent");
    }

    public function orderReceived()
    {
        return $this->hasMany("App\Models\OrderReceived"); //OrderReceived Model Has Many Relation with Order
    }

    public static function boot()
    {
        parent::boot();
        //Commented by Arslan
        // bug identified that deleting event has to call save to reflect column changes
        // strangley restore does not have to call save
        static::deleting(function(Order $model){
            $model->status_id = self::ORDER_DELETED_STATUS;
            $model->deleted_by =  \Session::get('uid');
            $model->save();
        });

        //separating pre delete and post delete functions
        static::deleted(function(Order $model){
            $model->restoreReservedProductQuantities();
        });

        //@todo add statis::restore
        static::restoring(function (Order $model) {
            $model->status_id = self::ORDER_ACTIVE_STATUS;
            $model->deleted_by = null;
            $model->deleteReservedProductQuantities();
        });
    }

    public static function getRows($args, $cond = null) {

        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'extraSorts' => [],
            'customSorts' => [],
            'order' => '',
            'params' => '',
            'global' => 1
        ), $args));

        //dump($args);
        $main_search=null;
        if(isset($_SESSION['order_search'])&& !empty($_SESSION['order_search'])){
            $explode_string=explode('|',$_SESSION['order_search']);
            $second_explode=explode(':',$explode_string[0]);
            $elastic = function (OrdersRepository $repository)  {
                if(isset($_SESSION['order_search'])){
                    $explode_string=explode('|',$_SESSION['order_search']);
                    //dump($explode_string);
                    $result['status_id']=null;
                    $result['is_api_visible']=null;
                    $result['invoice_verified']=null;
                    foreach($explode_string as $k=>$param){
                        $second_explode=explode(':',$param);
                        switch($second_explode[0]){
                            case 'search_all_fields':
                                $main_search=$second_explode[2];
                                break;
                            case 'status_id':
                                $result['status_id']=$second_explode[2];
                                break;
                            case 'is_api_visible':
                                $result['is_api_visible']=$second_explode[2];
                                break;
                            case 'invoice_verified':
                                $result['invoice_verified']=$second_explode[2];
                                break;

                        }
                        //dump($second_explode);
                    }
                    //$second_explode=explode(':',$explode_string[0]);

                }

                if(empty($main_search)){
                    unset($_SESSION['oredr_search']);
                }
                else{
                    $result['orders'] = $repository->search((string) $main_search);
                   // dump('orders=>',$result['orders']);
                    return $result;
                }
                return false;
            };
            $client = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
            $el=new ElasticsearchOrdersRepository($client);
            $pre_products=$elastic($el);

            if($pre_products['orders']!=null){


                if($pre_products['invoice_verified']) {
                    //dump('intvalinvoice_verified',intval($pre_products['invoice_verified']));
                    $pre_products['orders'] = $pre_products['orders']->where('invoice_verified', intval($pre_products['invoice_verified']));
                }
                if($pre_products['is_api_visible']) {
                    //dump('intvalis_api_visible',intval($pre_products['is_api_visible']));
                $pre_products['orders'] = $pre_products['orders']->where('is_api_visible', intval($pre_products['is_api_visible']));
                }
                if(intval($pre_products['status_id'])!==0){
                    //dump('intvalstatus_id',intval($pre_products['status_id']));
                    $pre_products['orders']=$pre_products['orders']->where('status_id',intval($pre_products['status_id']));
                }

            $products=$pre_products['orders'];
            $total=count($products/*$pre_products['orders']*/);
            $search_total=$total;
            //dump('total1=>',$total);
                $offset = ($page - 1) * $limit;
                if ($offset >= $total && $total != 0 && $limit != 0) {
                    $page = ceil($total/$limit);
                    $offset = ($page-1) * $limit ;
                }




             //dump('offset',$offset);


            if($total>0){
                $products = $products->chunk($limit);/*$pre_products['orders']*/
                $products=$products[$page - 1];
            }
                //dump('products',$products);

        }
        }



        $orderConditional1 = '';
        if (!empty($customSorts)) {
            $customOrderConditionals = [];
            foreach($customSorts as $customSort => $customSortType) {
                $customSortItem = '`'.$customSort.'` '.$customSortType;
                $customOrderConditionals[] = $customSortItem;
            }
            $orderConditional1 = implode(', ', $customOrderConditionals);
            $orderConditional1 = !empty($orderConditional1) ? $orderConditional1.", ":$orderConditional1;
        }

        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY {$orderConditional1} {$sort} {$order} " : '';
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

        Log::info("Total Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $counter_select =\DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}");


        if(!isset($search_total)) {
            $total = count($counter_select);
            if ($table == "img_uploads") {
                $total = "";
            }
        }else{
            //dump('here');
            $total = $search_total;
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


        if((isset($_SESSION['order_search']) && !empty($_SESSION['order_search'])) &&  $main_search){
            //var_dump('ses=>',$_SESSION['order_search']);
            if(isset($products)){
            $result=$products;}
            unset($_SESSION['order_search']);
        }
        else {
            $result = \DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        }
        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }

        //dump('total=>',$total);
        return $results = array('rows' => $result, 'total' => $total);
    }

    public function contents()
    {
        return $this->hasMany('App\Models\OrderContent');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location(){
        return $this->belongsTo(location::class);
    }

    public function vendor(){
        return $this->belongsTo(vendor::class);
    }

    public function restoreReservedProductQuantities(){
        if($this->is_freehand === 1){
            return ;
        }
        Log::info("Delete Event : Before calling adjustReservedProductQuantities");
        $this->adjustReservedProductQuantities();
        Log::info("Delete Event : After calling adjustReservedProductQuantities");
    }

    public function deleteReservedProductQuantities(){
        if($this->is_freehand === 1){
            return ;
        }
        Log::info("Restore Event : Before calling adjustReservedProductQuantities");
        $this->adjustReservedProductQuantities(true);
        Log::info("Restore Event : After calling adjustReservedProductQuantities");

    }

    private function adjustReservedProductQuantities($reduceQuantity = false)
    {
        $orderContents = $this->contents;
        Log::info("adjustReservedProductQuantities => Total Items = ".$orderContents->count());
        $module = new OrderController();
        $pass = \FEGSPass::getMyPass($module->module_id, '', false, true);
        $order_types = [];
        if(!empty($pass['calculate price according to case price'])) {
            $order_types = explode(',',$pass['calculate price according to case price']->data_options);
        }

        foreach ($orderContents as $orderContent) {

            $orderedProduct = $orderContent->product;

            if ($orderedProduct && $orderedProduct->is_reserved == 1) {

                if ($reduceQuantity) {

                    if($orderContent->is_broken_case == 1 && in_array($this->order_type_id,$order_types)){

                        $orderContent->qty = $orderContent->qty;

                    }elseif($orderContent->is_broken_case == 0 && in_array($this->order_type_id,$order_types)){
                        $orderContent->qty =  $orderContent->qty * $orderContent->qty_per_case;
                    }else{
                        $orderContent->qty = $orderContent->qty;
                    }

                    Log::info("claiming qty from product because order is restoring");
                    if ($orderedProduct->allow_negative_reserve_qty == 0 && $orderedProduct->reserved_qty < $orderContent->qty) {
                        throw new \Exception("Product does not have sufficient reserved quantities");
                    }
                    $reserved_qty =  $orderedProduct->reserved_qty - $orderContent->qty;



                    $reservedLogData = [
                        "product_id" => $orderContent->product_id,
                        "order_id" => $orderContent->order_id,
                        "adjustment_amount" => $orderContent->qty,
                        "variation_id"=>$orderedProduct->variation_id,
                        "adjustment_type" => "negative",
                        "reserved_qty_reason" => 'Order restored',
                        "adjusted_by" => \AUTH::user()->id,
                    ];


                    $reservedQtyLog = new ReservedQtyLog();
                    $reservedQtyLog->insert($reservedLogData);
                    $updates = ['reserved_qty' => $reserved_qty];
                    if (!$orderedProduct->allow_negative_reserve_qty and $reserved_qty == 0) {
                        $updates['inactive'] = 1;
                    }
                    $orderedProduct->updateProduct($updates, true);

                }
                else
                {
                    //This part is all working
                    Log::info("Putting back qty to product because order is deleting");

                    if($orderContent->is_broken_case == 1 && in_array($this->order_type_id,$order_types)){

                        $orderContent->qty = $orderContent->qty;

                    }elseif($orderContent->is_broken_case == 0 && in_array($this->order_type_id,$order_types)){
                        $orderContent->qty =  $orderContent->qty * $orderContent->qty_per_case;
                    }else{
                        $orderContent->qty = $orderContent->qty;
                    }

                    $reserved_qty =  $orderedProduct->reserved_qty + $orderContent->qty;

                    $reservedLogData = [
                        "product_id" => $orderContent->product_id,
                        "order_id" => $orderContent->order_id,
                        "adjustment_amount" => $orderContent->qty,
                        "variation_id"=>$orderedProduct->variation_id,
                        "adjustment_type" => "positive",
                        "reserved_qty_reason" => "Order removed",
                        "adjusted_by" => \AUTH::user()->id,
                    ];


                    $reservedQtyLog = new ReservedQtyLog();
                    $reservedQtyLog->insert($reservedLogData);

                    $updates = ['reserved_qty' => $reserved_qty];

                    if ($reserved_qty > 0) {
                        $updates['inactive'] = 0;
                    }
                    $orderedProduct->updateProduct($updates, true);

                }
            }
        }
    }

    public function canRestoreAllReservedProducts(){
        if(empty($this->contents) || $this->is_freehand === 1){
            return true;
        }
        foreach ($this->contents as $orderContent) {
            $orderedProduct = $orderContent->product;
            if(!empty($orderedProduct) && $orderedProduct->is_reserved == 1 && $orderedProduct->allow_negative_reserve_qty == 0 &&
                $orderedProduct->reserved_qty < $orderContent->qty){
                return false;
            }
        }

        return true;
    }

    public static function querySelect()
    {

        return "  SELECT orders.*,L.location_name,V.vendor_name,U.username,OT.order_type,OS.status,YN.yesno FROM orders
                LEFT OUTER JOIN location L ON orders.location_id=L.id
                LEFT OUTER JOIN vendor V ON orders.vendor_id=V.id
                LEFT OUTER JOIN users U ON orders.user_id=U.id
                LEFT OUTER JOIN order_type OT ON orders.order_type_id=OT.id
                LEFT OUTER JOIN order_contents OC ON orders.id=OC.order_id
                LEFT OUTER JOIN order_status OS ON orders.status_id=OS.id
                LEFT OUTER JOIN yes_no YN ON orders.is_partial=YN.id";
    }

    public static function getProductInfo($id){

        $select ="SELECT IF(order_contents.sku IS null OR order_contents.sku = '', products.sku,order_contents.sku) as sku, order_contents.qty,order_contents.item_name,order_contents.total FROM order_contents
        LEFT OUTER JOIN products ON products.id=order_contents.product_id
        WHERE order_id = ".$id;
        $result = \DB::select($select );
        return $result;
    }

    public static function processApiData($json,$param=null)
    {
        if(!empty($json)){
            return self::addOrderItems($json);
        }
        return $json;
    }

    public static function queryWhere($cond = null)
    {
        $return = " Where";
        switch (strtoupper($cond)) {
            case 'ALL':
                $return .= " orders.id IS NOT NULL";
                break;
            case 'OPEN':
                $return .= " orders.status_id IN(" . self::OPENID1 . "," . self::OPENID2 . "," . self::OPENID3 . ") AND orders.order_type_id !=" . self::FIXED_ASSET_ID ;
                break;
            case 'FIXED_ASSET':
                $return .= " orders.order_type_id = " . self::FIXED_ASSET_ID;
                break;
            case 'CLOSED':
                $return .= "  orders.status_id IN(" . self::CLOSEID1 . "," . self::CLOSEID2 . ")";
                break;
            default:
                $return .= " orders.id IS NOT NULL";
        }
        $module_id = Module::name2id('order');
        $pass = \FEGSPass::getMyPass($module_id);
        if(empty($pass['Can remove order']))
        {
            $return .= " AND orders.deleted_at is null ";
        }
        if($cond == 'only_api_visible')
        {
            $return .= " AND is_api_visible = 1 And api_created_at IS NOT NULL";
        }
        $selfObject = new self();
        if($selfObject->isUserInExcludedOrders()){
            $return .= " AND po_number NOT IN(".$selfObject->getExcludedOrderPoNumbers().") ";
        }

        return $return;
    }

    public static function truncatePoNotes($poNotes,$length=300)
    {
        //$poNotes = preg_replace("/[\r\n]+/", " ", $poNotes);
        $poNotes = str_replace(["\r\n"]," ",$poNotes);
        $poNotes = preg_replace('/\t+/', '', $poNotes);
        $poNotes = preg_replace('/\n+/', '', $poNotes);
        if(empty($poNotes) || strlen($poNotes) < $length){
            return $poNotes;
        }
        return \CurrencyHelpers::truncateLongText($poNotes, $length);
    }

    public static function addOrderItems($data){
        $orders = [];
        //extract order id for query to order_contents order_id in (1,2,3)
        foreach($data as &$record){
            $orders[] = $record['id'];
            $record['po_notes'] = self::truncatePoNotes($record['po_notes']);
            $record['items'] = [];
        }
        if(empty($orders)){
            return $data;
        }

        $module = new OrderController();
        $pass = \FEGSPass::getMyPass($module->module_id, '', false, true);
        $order_types = "";
        if(!empty($pass['calculate price according to case price'])) {
            $order_types = $pass['calculate price according to case price']->data_options;
        }
        $condition = '';
      /*  if($order_types != ''){
            $condition = "IF(ORD.order_type_id IN($order_types), O.case_price/O.qty, O.price/O.qty) AS price,";
        }*/

        $query = "SELECT O.*,ORD.order_type_id,$condition IF(O.product_id=0,O.sku,P.sku)AS sku FROM order_contents O LEFT OUTER JOIN products P ON O.product_id=P.id INNER JOIN orders ORD ON ORD.id = O.order_id WHERE O.order_id IN (".implode(',',$orders).")";

        // $query = "SELECT O.*,IF(O.product_id=0,O.sku,P.sku)AS sku FROM order_contents O LEFT OUTER JOIN products P ON O.product_id=P.id WHERE O.order_id IN (".implode(',',$orders).")";
        $result = \DB::select($query);
        //all order contents place them in relevent order
        $order_types = explode(",",$order_types);
        foreach($result as $item){
            $orderId = $item->order_id;

            if(in_array($item->order_type_id,$order_types) && (!empty($item->qty_per_case) && $item->qty_per_case>0)){
                $item->price = \CurrencyHelpers::formatPriceAPI(($item->case_price / $item->qty_per_case), self::ORDER_PERCISION, false);
                $item->case_price = \CurrencyHelpers::formatPriceAPI($item->case_price, self::ORDER_PERCISION, false);
                $item->qty = $item->is_broken_case == 1 ? $item->qty:$item->qty * $item->qty_per_case;
            }else{
                $item->price = \CurrencyHelpers::formatPriceAPI($item->price, self::ORDER_PERCISION, false);
                $item->case_price = \CurrencyHelpers::formatPriceAPI($item->case_price, self::ORDER_PERCISION, false);
            }
          /*  $orderId = $item->order_id;
            $item->price = \CurrencyHelpers::formatPrice($item->price, 3, false);
            $item->case_price = \CurrencyHelpers::formatPrice($item->case_price, 3, false);*/
            if(!empty($item->po_notes)) {
                if (strlen($item->po_notes) > 300) {
                    $item->po_notes = \CurrencyHelpers::truncateLongText($item->po_notes, 300);

                }
            }
            foreach($data as &$record){
                if($record['id'] == $orderId){
                    break;
                }
            }
            unset($item->upc_barcode);
            $record['items'][] = (array)$item;
        }
        return $data;
    }

    public static function getExportRows($args, $cond = null) {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        $global = $limit = $order = $sort = $page = $createdTo = $updatedTo = "";
        $params = [];

        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'order' => '',
            'params' => '',
            'global' => 1
        ), $args));


        $offset = ($page - 1) * $limit;
        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';
        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY {$sort} {$order} " : '';

        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if ($global == 0)
            $params .= " AND {$table}.entry_by ='" . \Session::get('uid') . "'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        $select = self::querySelect();

        /*

        */

        if ($cond != null) {
            $select .= self::queryWhere($cond);
        } else {
            $select .= self::queryWhere();
        }

        if(!empty($createdFrom)){
            $select .= " AND DATE(created_at) BETWEEN '$createdFrom' AND '$createdTo'";
        }

        if(!empty($updatedFrom)){

            if(!empty($cond)){
                $select .= " OR DATE(updated_at) BETWEEN '$updatedFrom' AND '$updatedTo'";
            }
            else{
                $select .= " AND DATE(updated_at) BETWEEN '$updatedFrom' AND '$updatedTo'";
            }

        }

        if(!empty($order_type_id)){
            $select .= " AND order_type_id='$order_type_id'";
        }
        if(!empty($status_id)){
            $select .= " AND status_id='$status_id'";
        }

        Log::info($select . " {$params} ". self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        $result = \DB::select($select . " {$params} ". self::queryGroup() ." {$orderConditional}  {$limitConditional} ");
        foreach($result as  &$rs){
            $results = self::getProductInfo($rs->id);
            $info = '';
            foreach($results as $r){
                if(!isset($r->sku)){
                    $sku = " (SKU: No Data) ";
                }else{
                    $sku = " (SKU: ".$r->sku.")";
                }

                $info = $info .'('.$r->qty.') '.$r->item_name.' '.\CurrencyHelpers::formatPrice($r->total).$sku. ';';
            }
            $rs->productInfo = $info;
        }

        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }

        $counter_select = \DB::select("SELECT COUNT(orders.id) as cnt FROM orders {$orderConditional}  {$limitConditional}");
        $total = $counter_select[0]->cnt;

        //$total = $limit;
        if($table=="img_uploads")
        {
            $total="";
        }
        return $results = array('rows' => $result, 'total' => $total);
    }

    public static function queryGroup()
    {
        return "GROUP BY orders.id  ";
    }

    public function getOrderQuery($order_id, $mode = null,$pass=null)
    {
        $case_price_categories = [];
        if(isset($pass['calculate price according to case price']))
        {
            $case_price_categories = explode(',',$pass['calculate price according to case price']->data_options);
        }
        $case_price_if_no_unit_categories = [];
        if(isset($pass['use case price if unit price is 0.00']))
        {
            $case_price_if_no_unit_categories = explode(',',$pass['use case price if unit price is 0.00']->data_options);
        }
        $data['order_content_id'] = 0;
        $data['requests_item_count'] = 0;
        $data['receivedItemsArray']=0;
        $data['order_loc_id'] = '0';
        $data['order_vendor_id'] = '';
        $data['order_type'] = '';
        $data['order_company_id'] = '';
        $data['order_location_id'] = '';
        $data['received_date']="";
        $data['received_by']="";
        // $data['order_location_name'] = '';
        $data['orderItemsPriceArray']="";
        $data['order_freight_id'] = '';
        $data['orderDescriptionArray'] = '';
        $data['orderPriceArray'] = '';
        $data['orderQtyArray'] = '';
        $data['brokenCaseArray'] = '';
        $data['OriginalCasePriceArray'] = '';
        $data['OriginalUnitPriceArray'] = '';
        $data['orderProductIdArray'] = '';
        $data['itemNameArray'] = "";
        $data['skuNumArray'] = "";
        $data['qtyPerCase'] = "";
        $data['itemCasePrice'] = "";
        $data['itemRetailPrice'] = "";
        $data['gameIdsArray']="";
        $data['orderRequestIdArray'] = '';
        $data['requests_item_count'] = '';
        $data['today'] = $this->get_local_time();
        $data['order_total'] = '0.00';
        $data['alt_address'] = "";
        $data['po_1'] = '0';
        $data['po_2'] = date('mdy');
        $data['po_3'] = $this->increamentPO();
        $data['po_notes'] = '';
        $data['prefill_type'] = "";
        $where_in_expression = "";
        $data['alt_name'] = $data['alt_street'] = $data['alt_city'] = $data['alt_state'] = $data['alt_zip'] = $data['shipping_notes'] = "";
        if ($order_id != 0 && $mode != (substr($mode, 0, 3) == 'SID')) {
            $order_query = \DB::select('SELECT location_id,vendor_id, date_ordered,order_total,alt_address,order_type_id,company_id,freight_id,po_notes,po_number FROM orders WHERE id = ' . $order_id );
            if (count($order_query) == 1) {
                $data['order_loc_id'] = $order_query[0]->location_id;
                $data['order_vendor_id'] = $order_query[0]->vendor_id;
                $data['order_location_id'] = $order_query[0]->location_id;

                //   $data['order_location_name'] = $order_query[0]->location_name;
                $data['order_type'] = $order_query[0]->order_type_id;
                $data['order_company_id'] = $order_query[0]->company_id;
                $data['order_freight_id'] = $order_query[0]->freight_id;
                $data['today'] = $mode == 'clone' ? $this->get_local_time('date'):$order_query[0]->date_ordered;
                $data['po_notes'] = $order_query[0]->po_notes;
                $data['order_total'] = $order_query[0]->order_total;
                $data['alt_address'] = $order_query[0]->alt_address;
            }
            $data['prefill_type'] = 'clone';
            $content_query = \DB::select('SELECT  O.id as order_content_id,O.upc_barcode,
            if((g.game_name is null or g.game_name = ""),gt.game_title,g.game_name) as game_name, O.product_description AS description,O.price AS price,O.qty AS qty, 
            O.product_id,O.item_name,O.case_price,P.retail_price, if(O.product_id=0,O.sku,P.sku) as sku,O.item_received as item_received,O.game_id,O.qty_per_case, O.is_broken_case  
												FROM order_contents O LEFT JOIN products P ON P.id = O.product_id
												  LEFT JOIN game g ON g.id = O.game_id
												  left join game_title gt on gt.id = g.game_title_id
												  WHERE O.order_id = ' . $order_id);


            if ($content_query) {

                foreach ($content_query as $row) {
                    $data['requests_item_count'] = $data['requests_item_count'] + 1;
                    $receivedItemsArray[]=$row->item_received;
                    $orderDescriptionArray[] = $row->description;
                    $orderPriceArray[] = \CurrencyHelpers::formatPrice($row->price, self::ORDER_PERCISION, false);
                    if(in_array($data['order_type'],$case_price_categories))
                    {
                        $orderItemsPriceArray[] = \CurrencyHelpers::formatPrice($row->case_price, self::ORDER_PERCISION, false);
                    }
                    elseif(in_array($data['order_type'],$case_price_if_no_unit_categories))
                    {
                        $orderItemsPriceArray[] = ($row->price == 0.00) ? \CurrencyHelpers::formatPrice($row->case_price, self::ORDER_PERCISION, false) : \CurrencyHelpers::formatPrice($row->price, Order::ORDER_PERCISION, false);
                    }
                    else
                    {
                        $orderItemsPriceArray[] = \CurrencyHelpers::formatPrice($row->price, self::ORDER_PERCISION, false);
                    }
                    $orderQtyArray[] = $row->qty;
                    $brokenCaseArray[] = $row->is_broken_case;
                    $orderProductIdArray[] = $row->product_id;
                    $orderitemnamesArray[] = $row->item_name;
                    $OriginalCasePrice[] = $row->case_price;
                    $OriginalUnitPrice[] = $row->price;
                    $skuNumArray[] = $row->sku;
                    $qtyPerCase[] = $row->qty_per_case;
                    $orderitemcasepriceArray[] = \CurrencyHelpers::formatPrice($row->case_price, self::ORDER_PERCISION, false);
                    $orderUpcBarcodeArray[] = $row->upc_barcode;
                    $orderretailpriceArray[] = \CurrencyHelpers::formatPrice($row->retail_price, self::ORDER_PERCISION, false);
                    $ordergameidsArray[] = $row->game_id;
                    $ordergamenameArray[] = $row->game_name;

                    $orderContentIdArray[] = $row->order_content_id;
                    //  $prod_data[]=$this->productUnitPriceAndName($orderProductIdArray);
                }
                $order_received_query=\DB::select('select date_received,received_by from order_received where order_id='.$order_id);
                if($order_received_query)
                {
                    foreach($order_received_query as $r) {
                        $data['received_date'] =$r->date_received;
                        $data['received_by'] = $r->received_by;
                    }
                }
                $data['orderDescriptionArray'] = $orderDescriptionArray;
                $data['OriginalCasePriceArray'] = $OriginalCasePrice;
                $data['OriginalUnitPriceArray'] = $OriginalUnitPrice;
                $data['orderPriceArray'] = $orderPriceArray;
                $data['orderQtyArray'] = $orderQtyArray;
                $data['brokenCaseArray'] = $brokenCaseArray;
                $data['skuNumArray'] = $skuNumArray;
                $data['qtyPerCase'] = $qtyPerCase;
                $data['orderUpcBarcodeArray'] = $orderUpcBarcodeArray;
                $data['orderProductIdArray'] = $orderProductIdArray;
                $data['gamenameArray'] = $ordergamenameArray;
                /*     if(count($prod_data)!=0) {
                         foreach ($prod_data as $d) {
                             $item_name_array[] = $d['vendor_description'];
                             $item_case_price[] = $d['case_price'];
                         }
                     }*/

                $data['itemNameArray'] = $orderitemnamesArray;
                $data['itemCasePrice'] = $orderitemcasepriceArray;
                $data['itemRetailPrice']=$orderretailpriceArray;
                $data['gameIdsArray']=$ordergameidsArray;
                $data['receivedItemsArray']=$receivedItemsArray;
                $data['orderItemsPriceArray'] = isset($orderItemsPriceArray)?$orderItemsPriceArray:"";

                $data['order_content_id'] = $orderContentIdArray;

                $poArr = array("", "", "");
                if (isset($data['po_number'])) {
                    $poArr = explode("-", $data['po_number']);
                    $data['po_1'] = $poArr[0];
                }
            }
            if ($mode == 'edit') {
                $data['today'] = $order_query[0]->date_ordered;
                $data['po_notes'] = $order_query[0]->po_notes;
                $data['po_number'] = $order_query[0]->po_number;

                if (isset($data['po_number'])) {
                    $poArr = explode("-", $data['po_number']);
                    $data['po_1'] = $poArr[0];
                    $data['po_2'] = isset($poArr[1]) ? $poArr[1] : "";
                    $data['po_3'] = isset($poArr[2]) ? $poArr[2] : "";
                }
                if (isset($data['alt_address'])) {
                    $altAddr = explode('|', $data['alt_address']);
                    $data['alt_name'] = isset($altAddr[0]) ? $altAddr[0] : "";
                    $data['alt_street'] = isset($altAddr[1]) ? $altAddr[1] : "";
                    $data['alt_city'] = isset($altAddr[2]) ? $altAddr[2] : "";
                    $data['alt_state'] = isset($altAddr[3]) ? $altAddr[3] : "";
                    $data['alt_zip'] = isset($altAddr[4]) ? $altAddr[4] : "";
                    $data['shipping_notes'] = isset($altAddr[5]) ? $altAddr[5] : "";
                }

                $data['prefill_type'] = 'edit';
            }
            if (isset($data['alt_address'])) {
                $altAddr = explode('|', $data['alt_address']);
                $data['alt_name'] = isset($altAddr[0]) ? $altAddr[0] : "";
                $data['alt_street'] = isset($altAddr[1]) ? $altAddr[1] : "";
                $data['alt_city'] = isset($altAddr[2]) ? $altAddr[2] : "";
                $data['alt_state'] = isset($altAddr[3]) ? $altAddr[3] : "";
                $data['alt_zip'] = isset($altAddr[4]) ? $altAddr[4] : "";
                $data['shipping_notes'] = isset($altAddr[5]) ? $altAddr[5] : "";
            }
            $data['today'] = ($mode) && $mode != 'clone' ? $order_query[0]->date_ordered : $this->get_local_time('date');
        } elseif (substr($mode, 0, 3) == 'SID') {
            $item_count = substr_count($mode, '-');
            $SID_string = $mode;
            $data['SID_string'] = $SID_string;
            for ($i = 1; $i < $item_count; $i++) {
                $pos1 = strpos($SID_string, '-');
                $SID_string = substr($SID_string, $pos1 + 1);
                $pos2 = strpos($SID_string, '-');
                ${
                    'SID' . $i
                } = substr($SID_string, 0, $pos2);

                $where_in_expression = $where_in_expression . ${'SID' . $i} . ',';

                $query = \DB::select('SELECT R.qty,
											  P.case_price,
											  P.unit_price,
											  P.sku,
											  P.retail_price,
											  P.vendor_id,
											  P.vendor_description,
											  P.item_description,
											  R.product_id,
											  R.location_id,
											  L.company_id,
											  P.prod_type_id,
											  P.num_items,
										  TRUNCATE(SUM(R.qty*P.case_price),5) AS total,
									   CONCAT(P.vendor_description," (SKU-",P.sku,")",IF(R.notes = "", "", CONCAT(" **note: ",R.notes,"**"))) AS description
										 FROM requests R
									LEFT JOIN products P ON P.id = R.product_id
									LEFT JOIN location L ON L.id = R.location_id
										WHERE R.id = ' . ${'SID' . $i} . '');
                \DB::table('requests')->where('id', ${'SID' . $i})->update(['blocked_at'=>date('Y-m-d H:i:s')]);
                if (count($query) == 1) {

                    $data['order_loc_id'] = $query[0]->location_id;
                    $data['order_company_id'] = $query[0]->company_id;
                    $data['order_vendor_id'] = $query[0]->vendor_id;
                    $data['order_location_id'] = $query[0]->location_id;
                    // $data['order_location_name'] = $query[0]->location_name;
                    $data['order_type'] = $query[0]->prod_type_id;
                    $data['order_total'] = $query[0]->total;
                    $data['po_2'] = date('mdy');
                    $data['po_3'] = $this->increamentPO();
                    $data['po_notes'] = "";
                    //$this->data['id']=1806;
                    $data['prefill_type'] = "";
                    $data['order_freight_id'] = "";

                    $orderDescriptionArray[] = $query[0]->description;
                    $orderPriceArray[] = \CurrencyHelpers::formatPrice($query[0]->unit_price, Order::ORDER_PERCISION, false);
                    $orderQtyArray[] = $query[0]->qty;
                    $brokenCaseArray[] = 0;
                    $skuNumArray[] = $query[0]->sku;
                    $qtyPerCase[] = $query[0]->num_items;
                    $orderProductIdArray[] = $query[0]->product_id;
                    //   $prod_data = $this->productUnitPriceAndName($query[0]->product_id);
                    $item_name_array[] = $query[0]->vendor_description;
                    $item_case_price[] = \CurrencyHelpers::formatPrice($query[0]->case_price, Order::ORDER_PERCISION, false);
                    $item_retail_price[]= \CurrencyHelpers::formatPrice($query[0]->retail_price, Order::ORDER_PERCISION, false);
                    $orderRequestIdArray[] = ${'SID' . $i};
                }

                $data['orderDescriptionArray'] = $orderDescriptionArray;
                $data['orderPriceArray'] = $orderPriceArray;
                $data['orderQtyArray'] = $orderQtyArray;
                $data['brokenCaseArray'] = $brokenCaseArray;
                $data['itemRetailPriceArray']=$item_retail_price;
                $data['orderProductIdArray'] = $orderProductIdArray;
                $data['orderRequestIdArray'] = $orderRequestIdArray;
                $data['itemNameArray'] = $item_name_array;
                $data['skuNumArray'] = $skuNumArray;
                $data['qtyPerCase'] = $qtyPerCase;
                $data['itemCasePrice'] = $item_case_price;
                $data['requests_item_count'] = $item_count-1;
                $data['today'] = date('m/d/y');
            }
            $data['prefill_type'] = 'SID';
        }
        $data['where_in_expression'] = substr($where_in_expression, 0, -1);

        return $data;
    }

    function getPoNumber($po_full,$location_id=0)
    {
        if($location_id != 0) {
            if($this->isPOAvailable($po_full))
            {
                $this->createPOTrack($po_full,$location_id);
                $po=explode('-',$po_full);
                return $po[2];
            }
            else
            {

                $po_increamented=$this->increamentPO($location_id);
                $po=explode('-',$po_full);
                $po[2]=$po_increamented;
                $po_full=implode('-',$po);
                $this->createPOTrack($po_full,$location_id);
                return $po_increamented;

            }
        }
        else{
            return 1;
        }
    }

    function isPOAvailable($po_full){
        $po = PoTrack::where('po_number', $po_full)->first();
        return $po ? false : true;
    }

    function createPOTrack($po_full,$location_id) {
        $count = explode('-', $po_full);
        $data = [
            'po_number' => $po_full,
            'location_id' => $location_id,
            'sort' => $count[2]
        ];
        return PoTrack::create($data);
    }

    public function get_local_time($type = null)
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $dayText = date('D');

        $yearmonthday = $year . '-' . $month . '-' . $day;
        if ($type = 'date') {
            return $yearmonthday;
        }
    }

    function getOrderReceipt($order_id)
    {
        $where_in_expression = '';
        $order_description = '';
        $total = '';
        $data['order_vendor_name'] = '';
        $data['order_id'] = $order_id;
        $data['location_id'] = '';
        $data['user_id'] = \Session::get('uid');
        if (!empty($order_id)) {
            $query = \DB::select('SELECT  O.order_type_id,O.order_description,O.request_ids,O.po_number,O.location_id,O.order_total,O.status_id,O.date_received,
                     O.notes,O.added_to_inventory,O.tracking_number,V.vendor_name,U.username FROM orders O LEFT JOIN vendor V ON V.id = O.vendor_id
                     LEFT JOIN users U ON U.id = O.user_id
                      
                      WHERE O.id = ' . $order_id . '');
            if (count($query) == 1) {
                $data['order_type'] = $query[0]->order_type_id;
                $data['po_number'] = $query[0]->po_number;
                $data['location_id'] = $query[0]->location_id;
                $data['order_status_id'] = $query[0]->status_id;
                $data['order_notes'] = $query[0]->notes;
                $data['added_to_inventory'] = $query[0]->added_to_inventory;
                $data['order_total'] = $query[0]->order_total;
                $data['order_user_name'] = $query[0]->username;
                $order_description = $query[0]->order_description;
                $data['description'] = str_replace(' | ', "<br>", $order_description);
                $data['vendor_name'] = $query[0]->vendor_name;
                $data['item_count'] = '';
                $data['date_received']=$query[0]->date_received;
                $data['tracking_number']=$query[0]->tracking_number;
                $data['status_id']=$query[0]->status_id;
                $orderContent = OrderContent::where('order_id',$order_id)->get();
                $i = 1;
                foreach ($orderContent as $item){
                    $data['product_id_' . $i] = $item->product_id;
                    $data['order_qty_' . $i] = $item->qty;
                    $data['order_description_' . $i] = $item->product_description;
                    $data['order_price_' . $i] = $item->case_price;
                    $i++;
                }
            }
            //  $data['status_options'] = $this->create_all_options_list('order_status','id','status','','id','YES','');
            $data['game_options'] = $this->create_game_options('CONCAT("Add to ",game_title.game_title," | ",game.id)', 'WHERE game.location_id = "' . $data['location_id'] . '" AND game.sold=0 AND game_title.game_type_id = 3', 'ORDER BY game_title.game_title', 'Inventory for Loc. #' . $data['location_id']);
            $data['today'] = $this->get_local_time('date');
            $data['title'] = 'Order Receipt';
            return $data;
        } else {
            \Redirect::to('orders');
        }
    }

    public function create_game_options($customField, $customWhere, $customOrderBy, $customBlankField)
    {
        $query = \DB::select('SELECT game.id AS gid, ' . $customField . ' AS game_title FROM game LEFT JOIN game_title ON game.game_title_id = game_title.id ' . $customWhere . ' ' . $customOrderBy);

        foreach ($query as $row) {
            $game[$row->gid] = $row->game_title;
        }

        if (strpos($customBlankField, 'Inventory') !== FALSE) {
            $game[''] = 'Add to ' . $customBlankField;
        } else {
            $game[''] = 'Select Game';
        }
        return $game;
    }

    function receiveOrder($request)
    {


    }

    function increamentPO_bk($location=0,$count=0, $datemdy = "")
    {
        $today = empty($datemdy) ? date('mdy'): $datemdy;
        if($location != 0) {

            $po = \DB::select("select po_number from po_track where po_number like '%-$today-%' and location_id=" . $location . " order by po_number");
            if($count == 0 ) {
                $count = count($po) + 1;

            }
            else
            {

                $count = $count +1;

            }
            $po_new=$location."-".$today."-".$count;

            if($this->isPOAvailable($po_new))
            {
                $this->createPOTrack($po_new,$location);

            }
            else
            {
                //echo "$location:$count";
                //die('here...');
                $count = $this->increamentPO($location,$count);
            }
        }
        else
        {
            $count=1;;
        }
        return $count;
    }

    function increamentPO($location=0,$count=0, $datemdy = ""){
        $today = empty($datemdy) ? date('mdy'): $datemdy;
        if($location != 0) {

            $poData = \DB::select("select sort from po_track where po_number like '%-$today-%' and location_id=" . $location . " order by sort");

            $poData = array_map(function ($value) {
                return $value->sort;
            }, $poData);

            $total = count($poData);
            if($count == 0 ) {
                $count = $total + 1;
            }
            else{
                $count = $count + 1;
            }

            if ($total != 0) {
                for ($i = 1; $i <= $total; $i++) {
                    if (!in_array($i, $poData)) {
                        $count = $i;
                        break;
                    }
                }
            }

            $po_new = $location . "-" . $today . "-" . $count;
            if ($this->isPOAvailable($po_new)) {
                $this->createPOTrack($po_new, $location);
            } /*else {
                $count = $this->increamentPO($location);
            }*/

        }else{
            $count=1;
        }
        return $count;
    }

    function getVendorEmail($vendor_id)
    {
        $vendor_email = \DB::select("SELECT email, email_2 from vendor WHERE id=" . $vendor_id);
        if(empty($vendor_email[0]->email_2)){
            return $vendor_email[0]->email;
        }else{
            return $vendor_email[0]->email.','.$vendor_email[0]->email_2;
        }
    }

    function productUnitPriceAndName($prod_id)
    {
        $row = \DB::select('SELECT vendor_description,case_price from products where id=' . $prod_id);
        if ($row) {
            $data = array('vendor_description' => $row[0]->vendor_description, 'case_price' => $row[0]->case_price);
            return $data;
        }
    }

    public static function getComboselect($params, $limit = null, $parent = null)
    {
        $tableName = $params[0];
        if ($tableName == 'location') {
            return parent::getUserAssignedLocation($params, $limit, $parent);
        } else {
            return parent::getComboselect($params, $limit, $parent);
        }
    }

    public function getUnitPriceAttribute(){
        return Sximo::parseNumber($this->attributes['unit_price']);
        //return number_format($this->attributes['unit_price'],3); //causing problem with inputs
    }

    public function getCasePriceAttribute(){
        return Sximo::parseNumber($this->attributes['case_price']);
        //return number_format($this->attributes['case_price'],3); //causing problem with inputs
    }

    public static function isClonable($id, $data = null) {

    }

    public static function isEditable($id, $data = null) {

    }

    public static function isReceivable($id, $data = null) {

    }

    public static function isPartiallyReceived($id, $data = null) {
        $partial = false;
        if (self::isVoided($id, $data)){
            return $partial;
        }
        $record = \DB::select('SELECT
            SUM(qty) as total_items,
            (SUM(qty)-SUM(item_received)) as remaining_items 
            FROM order_contents
            WHERE order_id ='.$id.
            " GROUP BY order_id");
        $partial = !empty($record) &&
            $record[0]->remaining_items > 0 &&
            $record[0]->remaining_items < $record[0]->total_items;
        return $partial;
    }

    public static function isClosed($id, $data = null) {
        if (!empty($data) && (isset($data->status_id))||(isset($data['status_id']))) {
            $statusId = is_object($data) ? $data->status_id : $data['status_id'];
        }
        else {
            $statusId = self::where('id', $id)->value('status_id');
        }
        $isClosed = in_array($statusId, self::ORDER_CLOSED_STATUS);
        return $isClosed;
    }

    public static function isVoided($id, $data = null) {
        if (!empty($data)) {
            $statusId = is_object($data) ? $data->status_id : $data['status_id'];
        }
        else {
            $statusId = self::where('id', $id)->value('status_id');
        }
        $isVoided = $statusId == self::ORDER_VOID_STATUS;
        return $isVoided;
    }

    public static function isFreehand($id, $data = null) {
        if (!empty($data)) {
            $freehand = is_object($data) ? $data->is_freehand : $data['is_freehand'];
        }
        else {
            $freehand = self::where('id', $id)->value('is_freehand');
        }
        $isFreeHand = !empty($freehand);
        return $isFreeHand;
    }

    public static function isApiableFromType($id, $data = null) {
        $data = null;
        if (!empty($data)) {
            $oType = is_object($data) ? $data->order_type_id : $data['order_type_id'];
        }
        else {
            $oType = self::where('id', $id)->value('order_type_id');
        }
        $isApiable = Ordertyperestrictions::isApiable($oType);
        return $isApiable;
    }

    public static function isApiable($id, $data = null, $ignoreVoid = false) {
        return !self::isFreehand($id, $data) && self::isApiableFromType($id, $data) &&
            ($ignoreVoid || !self::isVoided($id, $data));
    }

    public static function isApified($id, $data = null) {
        if (!empty($data)) {
            $api = is_object($data) ? $data->is_api_visible : $data['is_api_visible'];
        }
        else {
            $api = self::where('id', $id)->value('is_api_visible');
        }
        $isApified = !empty($api);
        return $isApified;
    }

    public static function apified($id, $isUnset = false) {
        if (self::isApiable($id, null, true)) {
            $now = date("Y-m-d H:i:s");
            $setValue = $isUnset ? 0 : 1;
            $updateData = ['is_api_visible' => $setValue];
            if (self::isApified($id)) {
                $updateData['api_updated_at'] = $now;
            }
            else {
                $updateData['api_created_at'] = $now;
            }
            \DB::update("UPDATE order_received SET api_created_at = '$now' WHERE order_id = $id");

            $model = self::where('id', $id)->update($updateData);

            FEGSystemHelper::updateMetaFromOrder($id, ['posted_to_api_at' => $now]);

            return $model;
        }
        return false;
    }

    public static function voidify($id) {
        $now = date("Y-m-d H:i:s");
        $updateData = ['status_id' => self::ORDER_VOID_STATUS];
        if (self::isApified($id)) {
            $updateData['api_updated_at'] = $now;
        }
        $updateData['updated_at'] = $now;
        return self::where('id', $id)->update($updateData);
    }

    /**
     *
     * @return bool
     */
    public function isFullyReceived(){
        $orderedQty = $this->contents->sum('qty');
        $receivedQty = $this->orderReceived->sum('quantity');
        if ($orderedQty == $receivedQty) {
            return true;
        }
        return false;
    }

    /**
     * Conditions for Post to NetSuite Button being visible:
    1) order fully received;
    2) order status = Closed;
    3) Invoice Verified = Yes
     * Post to Netsuite button will NOT be visible for freehand orders, ever.
     * After an order has been posted to netsuite, it cannot be edited nor received against.
     * @param $id
     * @param null $data
     * @return bool
     */
    public static function canPostToNetSuit($id, $data = null){
        $order_qty = \DB::select("SELECT SUM(qty) as qty FROM order_contents WHERE order_id=$id");
        $received_qty = \DB::select("SELECT SUM(quantity) as qty FROM order_received WHERE order_id=$id AND deleted_at IS NULL");
        if(empty($data)){
            $data = self::find($id)->toArray();
        }
        else
        {
            $data = (array)$data;
        }
        //remap status id to status value
        if(isset($data['status_value'])){
            $data['status_id'] = $data['status_value'];
        }
        if(!self::isClosed($id,$data) || (isset($data['invoice_verified'])) && $data['invoice_verified'] == 0){
            return false;
        }
        if(!empty($received_qty)){
            if($received_qty[0]->qty == $order_qty[0]->qty){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function cloneOrder($id, $data = null, $options = array()) {

        $options = array_merge([
            'skipReceipts' => true,
            'skipItems' => false,
            'resetDate' => null,
            'resetApiable' => true
        ], $options);

        $nowTimestamp = strtotime("now");
        $now = date("Y-m-d", $nowTimestamp);
        $nowStamp = date("Y-m-d H:i:s", $nowTimestamp);

        if (empty($data)) {
            $data = self::find($id)->toArray();
        }
        $locationId = $data['location_id'];
        unset($data['id']);
        if (!empty($options['resetApiable'])) {
            unset($data['is_api_visible']);
            unset($data['api_created_at']);
            unset($data['api_updated_at']);
        }
        unset($data['updated_at']);
        unset($data['created_at']);
        unset($data['date_received']);
        if (!empty($options['resetDate'])) {
            $nowStamp = $options['resetDate'];
            $nowTimestamp = strtotime($nowStamp);
            $now = date('Y-m-d', $nowTimestamp);
        }

        $data['date_ordered'] = $now;
        $data['po_number'] = self::generateNewPONumber($locationId, $nowStamp);
        $obj = with(new self);
        $newID = $obj->insertRow($data);
        \Log::info($options);
        if (empty($options['skipItems'])) {
            \Log::info("Not skipping items");
            $itemReceived = empty($options['skipReceipts'])? 'oc.item_received' : '0';
            $sql = "INSERT INTO order_contents
                      ( order_id,
                        request_id,
                        product_id,
                        product_description,
                        price,
                        qty,
                        game_id,
                        item_name,
                        case_price,
                        total,
                        item_received,
                        sku,
                        created_at
                      )
                    SELECT $newID,
                        oc.request_id,
                        oc.product_id,
                        oc.product_description,
                        oc.price,
                        oc.qty,
                        oc.game_id,
                        oc.item_name,
                        oc.case_price,
                        oc.total,
                        $itemReceived,
                        oc.sku,
                        NOW()
                    FROM order_contents AS oc
                    WHERE oc.order_id=$id";

            \Log::info($sql);
            $affected = \DB::insert($sql);
        }
        else {
            \Log::info("skipping items");
        }
        if (empty($options['skipReceipts'])) {
            \Log::info("NOT skipping receipts");
            $sql = "INSERT INTO order_received
                        (order_id,
                        order_line_item_id,
                        quantity,
                        received_by,
                        date_received,
                        created_at,
                        notes,
                        status)
                    SELECT $newID,
                        orc.order_line_item_id,
                        orc.quantity,
                        orc.received_by,
                        orc.date_received,
                        NOW(),
                        orc.notes,
                        orc.status
                        
                    FROM order_received AS orc
                    WHERE orc.order_id=$id";

            $affected = \DB::insert($sql);
        }
        else {
            \Log::info("skipping receipts");
        }

        return $newID;

    }

    public static function generateNewPONumber($location, $date, $count = 0) {
        $obj = with(new static);
        $datemdy = date("mdy", strtotime($date));
        do {
            $poCount = $obj->increamentPO($location, $count, $datemdy);
            $poNumber = "$location-$datemdy-$poCount";
            $count++;
        } while(self::where('po_number', $poNumber)->count() > 0);

        return $poNumber;
    }

    public static function relateOrder($rType, $originalOrderID, $targetOrderID) {

        $typeIDs = \FEGHelp::getEnumTable('orders_relation_types', 'relation_name', 'id');
        $oOrderData = self::find($originalOrderID);
        $tOrderData = self::find($targetOrderID);
        $nowDateTime = date("Y-m-d H:i:s");
        $now = \DateHelpers::formatDate($nowDateTime);
        $oPO = $oOrderData->po_number;
        $tPO = $tOrderData->po_number;
        $now = \DateHelpers::formatDate(date("Y-m-d H:i:s"));
        if ($rType == 'replace') {
            // $originalOrderID => new order which replaces the old
            // $targetOrderID => old order which has been replaced by the $originalOrderID
            $data =[[
                'order_id' => $originalOrderID,
                'related_order_id' => $targetOrderID,
                'relation_id' => $typeIDs['replaces'],
                'relation_note' => \FEGHelp::stringBuilder(\Lang::get('core.templates.order_replaces'), [$tPO, $now]),
            ],[
                'order_id' => $targetOrderID,
                'related_order_id' => $originalOrderID,
                'relation_id' => $typeIDs['replaced by'],
                'relation_note' => \FEGHelp::stringBuilder(\Lang::get('core.templates.order_replaced_by'), [$oPO, $now]),
            ]];
            \DB::table('orders_relations')->insert($data);
        }
    }

    public static function getOrderRelationships($id) {
        $notes = [];
        $data = \DB::table('orders_relations')->where("order_id", $id)->get();
        if (!empty($data)) {
            foreach($data as $row) {
                $notes[] = $row->relation_note;
            }
        }
        return $notes;
    }

    public function setOrderStatus()
    {
        $OrderedQty = $this->orderedContent->sum('qty');
        $ItemReceived = $this->orderedContent->sum('item_received'); // This is a test comment
        if ($ItemReceived > 0 && $ItemReceived < $OrderedQty) {
            $this->status_id = 1;
            $this->is_partial = 1;
        }
    }

    public function setOrderStatusPost($request_qty){
        $total_qty = $this->contents->sum('qty');
        $received_qty = $this->orderReceived->sum('quantity');
        $new_qty = $request_qty - $total_qty;
        $final_qty = $new_qty + $total_qty;

        if ($received_qty and $final_qty != $received_qty) {
            $this->status_id = 1;
            $this->is_partial = 1;
        } elseif(!$received_qty or($final_qty == $received_qty and $this->status_id != '2')) {
            $this->status_id = 1;
            $this->is_partial = 0;
        } elseif ($final_qty == $received_qty and $this->status_id != '1'){
            $this->status_id = 2;
            $this->is_partial = 0;
        }
    }

    public function updateRequest(array $request_ids){
        pendingrequest::whereIn('id', $request_ids)->update([
            'status_id' => 2,
            'process_user_id' => Auth::user()->id,
            'process_date' => $this->get_local_time('date'),
            'blocked_at' => null,
        ]);
    }
    public function getExcludedOrderPoNumbers(){
            $po_numbers = FEGSystemHelper::getOption('excluded_orders_from_groups');
            $array = FEGSystemHelper::split_trim($po_numbers);
            $string_po = [];
            foreach ($array as $arr){
            $string_po[] = "'".$arr."'";
            }
            $po_numbers= implode(",",$string_po);
            return $po_numbers;
        }
    public function getExcludedOrderSpecifiedGroups(){
        $userGroups = FEGSystemHelper::getOption('excluded_orders_groups');
        $array = FEGSystemHelper::split_trim($userGroups);
        $string_group = [];
        foreach ($array as $arr){
            $string_group[] = $arr;
        }
        $excludedGroup= implode(",",$string_group);
        return $excludedGroup;
    }
    public function isUserInExcludedOrders(){
    $userGroups = !empty($this->getExcludedOrderSpecifiedGroups()) ? explode(",",$this->getExcludedOrderSpecifiedGroups()):[];
    return in_array(\Session::get('gid'),$userGroups);
    }
    public function isOrderReceived(){
        if($this->contents && $this->orderReceived && $this->is_freehand && $this->location){
        if ($this->contents->sum('qty') == $this->orderReceived->sum('quantity') && $this->is_freehand == 0 && in_array($this->location->debit_type_id,location::DEBIT_TYPES)) {
            return  true;
        } else {
            return false;
        }
        }
    }

    /**
     * check if order is eligible for DPL button
     */
    public function isDPLAble(){

        $orderFullyReceived = $this->isOrderReceived();
        $redemptionPrizeProducts = $this->filterRedemptionTypeProducts();
        return $orderFullyReceived && !$redemptionPrizeProducts->isEmpty();
    }

    public function getUnitOfMeasurementForOrderType(){
        $module = new OrderController();
        $pass = \FEGSPass::getMyPass($module->module_id, '', false, true);
        $order_types = $pass['calculate price according to case price']->data_options;
        $order_types = explode(",", $order_types);
        if (in_array($this->order_type_id, $order_types)) {
            return 'CASE';
        }
            return "EACH";

    }

    /**
     * will get all order contents and filter only products which are
     * 1. redemption price
     * 2. Any of its variation is redemption prize
     */
    public function filterRedemptionTypeProducts(){
        $orderContents = $this->contents;
        $redemptionPrizeProducts = Product::filterVariationsByType($orderContents, Order::ORDER_TYPE_REDEMPTION);
        $otherProducts = $orderContents->diff($redemptionPrizeProducts);
        foreach($otherProducts as $orderContent){
            $product = $orderContent->product;
            $variants = !is_null($product) ? $product->getProductVariations(true) : collect();
            if(!$variants->isEmpty()){
                //if any of product variation is redemption prize then add that order content into collection
                $variantsWithRedemptionPrize = Product::filterVariationsByType($variants, Order::ORDER_TYPE_REDEMPTION);
                if(!$variantsWithRedemptionPrize->isEmpty()){
                    //important: overriding orderContent.prod_type_id for showing abbrevation in scoa
                    $orderContent->prod_type_id = Order::ORDER_TYPE_REDEMPTION;
                    $firstProduct = $variantsWithRedemptionPrize->first();
                    $orderContent->ticket_value = $firstProduct ? $firstProduct->ticket_value : 0;
                    $redemptionPrizeProducts->add($orderContent);
                }
            }
        }
        return $redemptionPrizeProducts;
    }

    /**
     * @return array
     */
    public static function getMerchandiseTypes(){
        $module = new OrderController();
        $pass = \FEGSPass::getMyPass($module->module_id, '', false, true);
        $order_types = $pass['calculate price according to case price']->data_options;
        $order_types = explode(",", $order_types);
        return !empty($order_types) ? $order_types:[];
    }

    /**
     * @param $locationId
     * @param $productTypeId
     * @param bool $isActiveItemsOnly
     * @param string $onlyActiveItemQuery
     * @return string
     */
    public function getManualGenerateDplQuery($locationId, $productTypeId, $isActiveItemsOnly = false, $onlyActiveItemQuery = "")
    {
        $groupBy = " GROUP BY OC.item_name,OC.sku,OC.case_price ";

        if($isActiveItemsOnly){
            $groupBy = " GROUP BY P.vendor_description,P.sku,P.case_price ";
            $onlyActiveItemQuery .= " AND (P.inactive = 0 OR P.inactive = '' OR P.inactive IS NULL) ";
        }
        $sql = 'SELECT
                  O.id            AS Order_id,
                  OC.id            AS Order_Content_id,
                  OC.product_id            AS Product_id,
                  V.vendor_name,
                  OC.item_name,
                  OC.sku,
                  OC.is_broken_case,
                  OC.prod_type_id,
                  OC.qty_per_case,
                  PT.order_type,
                  OC.qty,
                  OC.item_received,
                  OC.upc_barcode,
                  OC.case_price,
                  OC.price,
                  P.ticket_value,
                  P.upc_barcode as product_upc_barcode,
                  P.img
                FROM orders O
                  INNER JOIN order_contents OC
                    ON OC.order_id = O.id
                  INNER JOIN order_type PT
                    ON PT.id = OC.prod_type_id
                  INNER JOIN products P
                    ON P.id = OC.product_id
                  INNER JOIN vendor V
                    ON V.id = P.vendor_id
                WHERE O.location_id = ' . $locationId . '
                    AND OC.prod_type_id = ' . $productTypeId . '
                    AND OC.item_received > 0 
                '.$onlyActiveItemQuery.$groupBy;
        return $sql;
    }

    /**
     * @param $items
     * @param $locationId
     * @param $poNumber
     * @param string $saveFilePath
     * @param string $dplFileName
     * @return array
     */
    public function saveItemsInDplFile($items,$orderTypeId, $locationId, $poNumber, $saveFilePath = '/', $dplFileName = 'manual_dpl_file_generated.dpl')
    {
        $newLine = "\r\n";
        $fileContent = $locationId . " " . $poNumber;
        if (!empty($items)) {
            $fileContent .= $newLine;
            $i = 0;
            $total = count($items);
            foreach ($items as $item) {
                $i++;
                $newLine = $i < $total ? $newLine:'';
                //$fileContent .= $item->vendor_name . '-' . $item->item_name . '-'.$item->sku.$newLine;
              //  $itemId = $item->upc_barcode;
                $itemId = $item->product_upc_barcode;
                $itemName = $this->cleanAndTruncateString($item->item_name);
                $sku = $item->sku;
                $this->order_type_id = $orderTypeId;
                $unitTypeUOM = $this->getUnitOfMeasurementForOrderType();
                $price =($unitTypeUOM == "CASE") ? $price = \CurrencyHelpers::formatPrice($item->case_price/$item->qty_per_case, $decimalPlaces = 5,  false,  '', $dec_point = '.',  false) : $item->price;

                $tickets = $item->ticket_value;
                $qtyPerCase = $item->qty_per_case;

                $orderTypes = [
                    Order::ORDER_TYPE_OFFICE_SUPPLIES => 'OffSuppl',
                    Order::ORDER_TYPE_REDEMPTION => 'RedPrize',
                    Order::ORDER_TYPE_INSTANT_WIN_PRIZE => 'InstWin',
                    Order::ORDER_TYPE_MARKETING => 'Marketing',
                    Order::ORDER_TYPE_UNIFORM => 'Uniforms'
                ];
                $itemName = \SiteHelpers::removeSpecialCharacters($itemName);
                $productType = isset($orderTypes[$item->prod_type_id]) ? $orderTypes[$item->prod_type_id]:$item->prod_type_id;
                $unitTypeUOMUpdated = ((strtolower($unitTypeUOM) == 'case') ? ($item->is_broken_case == 0) ? 'EACH':'EACH':'EACH');
                $quantityReceived = ((strtolower($unitTypeUOM) == 'case') ? ($item->is_broken_case == 0) ? ($item->item_received * $qtyPerCase): $item->item_received : $item->item_received);
                $fileContent .= implode(",",[$itemId, $sku,$tickets, $itemName, $productType, '', '', $price, '','', $quantityReceived]) . $newLine;
            }
        }

        File::put(public_path($saveFilePath) . $dplFileName, $fileContent);
        return ['file_path'=>public_path($saveFilePath) . $dplFileName,'file_name'=>$dplFileName];
    }

    public function cleanAndTruncateString($string, $length = 50)
    {
        $string = str_replace(["&",",",'"'],"",$string);
        return $string; //$this->truncateString($string, $length);
    }

    public function saveItemImagesFromDPL($items,$filePath,$saveToPath)
    {
        foreach ($items as $item){
            if(!empty($item->img)) {

                if (file_exists($filePath . '/' . $item->img)) {

                    $itemName = $this->cleanAndTruncateString($item->item_name);
                    $itemName = \SiteHelpers::removeSpecialCharacters($itemName);
                    $itemName = preg_replace('/[^a-zA-Z0-9\.]/','',$itemName);
                    $sku = $item->sku;
                    $vendorName = $item->vendor_name;
                    $vendorName = preg_replace('/[^a-zA-Z0-9\.]/','',$vendorName);
                    $fileName = $item->img;
                    $extension = mb_substr($fileName, mb_strpos($fileName, '.') + 1, mb_strlen($fileName));
                    $fileNewName = implode('-',[
                        str_replace(' ', '', strtolower(str_replace('/','',trim($vendorName)))),
                        str_replace(' ', '', strtolower(str_replace('/','',trim($itemName)))),
                        str_replace(' ', '', str_replace('/','',trim($sku))),

                    ]).'.'.$extension;
                    \File::copy($filePath . '/' . $fileName, $saveToPath . '/' . $fileNewName);
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function isAllowedToCombineFreehandProductList(){

        $option = Options::where('option_name', 'canCombineOrderContentUsers')->first();
        $canCombineOrderContentUsers = [];
        if($option){
            $canCombineOrderContentUsers =  explode(',', $option->option_value);
        }

        $option = Options::where('option_name', 'canCombineOrderContentGroups')->first();
        $canCombineOrderContentGroups = [];
        if($option){
            $canCombineOrderContentGroups =  explode(',', $option->option_value);
        }

        return (in_array(Session::get('uid'),$canCombineOrderContentUsers) || in_array(Session::get('gid'),$canCombineOrderContentGroups));

    }

    /**
     * @param $items
     * @param $productId
     * @return int
     */
    public function getMatchedElement($items, $productId)
    {
        if ($items) {
            foreach ($items as $item) {
                if ($item['product_id'] == $productId) {
                    return $item['is_broken_case'];
                }
            }
        } else {
            return 0;
        }
        return 0;
    }
}