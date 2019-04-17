<?php namespace App\Models;

use App\Http\Requests\Request;
use App\Library\FEG\System\FEGSystemHelper;
use App\Library\FEGDBRelationHelpers;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Log;

class product extends Sximo  {
	
	protected $table = 'products';
	protected $primaryKey = 'id';
    protected $guarded = [];

	public function __construct() {
		parent::__construct();
		
	}

    /**
     * testing git
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function(product $model){
            Log::info("------------Product has been saved---------------");
            Log::info("------------Product ID: ".$model->id."---------------");
            if($model->is_reserved == 1) {
                if ($model->reserved_qty > $model->reserved_qty_limit) {
                    Log::info("-----------Product email alert flag has been updated to true------------");
                    $model->send_email_alert = 0;
                }
            }
            return $model;
        });
        static::saved(function(product $model){
            FEGSystemHelper::updateProductMeta($model);
        });

        static::deleted(function ($model){
            VendorProductTrack::where(['product_id'=>$model->id,'vendor_id'=>$model->vendor_id])->delete();
        });
    }

    function orderedProduct()
    {
        return $this->hasMany("App\Models\OrderedContent");
    }

	public static function querySelect(  ){

	    $supQuries = self::subQueriesSelect();

        return " SELECT
  products.*,
  O.order_type       AS `prod_type`,
  vendor.vendor_name AS `vendor`,
  IF(products.hot_item = 1,CONCAT('',products.vendor_description,' **HOT ITEM**'), products.vendor_description) AS `prod_description`,
  TRUNCATE(products.case_price/num_items,5) AS `unit_pricing`,
  T.type_description AS `product_type`,
  IF(products.inactive = 1,'NOT AVAIL.',CONCAT('Add to Cart')) AS `add`,
  CONCAT('Details')  AS `addldetails`,
  products.id        AS `product_id`,
  IF(products.retail_price = 0.00,TRUNCATE(products.case_price/num_items,5),products.retail_price) AS `retail_price`,
  O.order_type       AS prod_type_id,
  T.type_description AS prod_sub_type_id,
  '' as excluded_locations_and_groups,
  '' as product_type_excluded_data,
  (reserved_qty/num_items) as reserved_qty_cases,
  $supQuries
FROM `products`
  LEFT JOIN vendor
    ON (products.vendor_id = vendor.id)
  LEFT JOIN order_type O
    ON (O.id = products.prod_type_id)
  LEFT JOIN product_type T
    ON (T.id = products.prod_sub_type_id) ";
	}

    public static function querySelectAPI(){

        $id = self::getDefaultExpenseCategoryQuery("expns_p.id", "id");
        $item_description = self::getDefaultExpenseCategoryQuery("expns_p.item_description", "item_description");
        $netsuite_description = self::getDefaultExpenseCategoryQuery("expns_p.netsuite_description", "netsuite_description");
        $expense_category = self::getDefaultExpenseCategoryQuery("expns_p.expense_category", "expense_category");
        $is_default_expense_category = self::getDefaultExpenseCategoryQuery("expns_p.is_default_expense_category", "is_default_expense_category");

        $sql = " SELECT
                " . $id . ",
                  products.sku,
                  products.vendor_description,
                  " . $item_description . ",
                  " . $netsuite_description . ",
                  products.size,
                  products.details,
                  products.num_items,
                  products.vendor_id,
                  products.unit_price,
                  products.case_price,
                  products.retail_price,
                  products.ticket_value,
                  products.prod_type_id,
                  products.prod_sub_type_id,
                  products.is_reserved,
                  products.reserved_qty,
                  products.min_order_amt,
                  products.img,
                  products.inactive,
                  products.inactive_by,
                  products.eta,
                  products.in_development,
                  products.limit_to_loc_group_id,
                  products.date_added,
                  products.hot_item,
                  products.created_at,
                  products.updated_at,
                  " . $expense_category . ",
                  products.exclude_export,
                  products.allow_negative_reserve_qty,
                  products.reserved_qty_limit,
                  " . $is_default_expense_category . ",
                  GROUP_CONCAT(O.order_type)          AS `prod_type`,
                  GROUP_CONCAT(vendor.vendor_name)    AS `vendor`,
                  GROUP_CONCAT(IF(products.hot_item = 1,CONCAT('',products.vendor_description,' **HOT ITEM**'), products.vendor_description)) AS `prod_description`,
                  GROUP_CONCAT(TRUNCATE(products.case_price/num_items,5)) AS `unit_pricing`,
                  GROUP_CONCAT(T.type_description)    AS `product_type`,
                  GROUP_CONCAT(products.id)           AS `product_id`,
                  GROUP_CONCAT(IF(products.retail_price = 0.00,TRUNCATE(products.case_price/num_items,5),products.retail_price)) AS `retail_price`,
                  GROUP_CONCAT(O.order_type)          AS prod_type_id,
                  GROUP_CONCAT(T.type_description)    AS prod_sub_type_id,
                  GROUP_CONCAT(ticket_value)          AS ticket_value,
                  GROUP_CONCAT(inactive)              AS inactive
                FROM `products`
                  LEFT JOIN vendor
                    ON (products.vendor_id = vendor.id)
                  LEFT JOIN order_type O
                    ON (O.id = products.prod_type_id)
                  LEFT JOIN product_type T
                    ON (T.id = products.prod_sub_type_id)  ";
        return $sql;
    }

    public static function getDefaultExpenseCategoryQuery($column, $alt_name)
    {

          return   $sql ="IFNULL((SELECT
      " . $column . " 
      FROM products expns_p
       WHERE expns_p.sku = products.sku
       AND expns_p.vendor_id = products.vendor_id
       AND expns_p.case_price = products.case_price
       AND expns_p.vendor_description = products.vendor_description
       AND expns_p.is_default_expense_category = 1 LIMIT 1),products.$alt_name) AS ".$alt_name;


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

    /**
     * @return string
     */

    public static function queryGroupAPI(){
        return " GROUP BY products.vendor_description, products.sku, products.vendor_id, products.case_price ";
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
            'global'	=> 1,
            'vendor_id'=>'',
            'case_price'=>'',
            'sku'=>'',
            'vendor_description'=>'',
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
        if(!empty($vendor_id)){
            $select .= " AND products.vendor_id='$vendor_id'";
        }
        if(!empty($case_price)){
            $select .= " AND products.case_price='$case_price'";
        }
        if(!empty($vendor_description)){
            $select .= " AND products.vendor_description='$vendor_description'";
        }
        if(!empty($sku)){
            $select .= " AND products.sku='$sku'";
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
    public static function getMergeRows( $args,$cond=null,$active=null,$sub_type=null, $is_api=false)
    {

        $table = with(new static)->table;
        $key = with(new static)->primaryKey;

        extract( array_merge( array(
            'page' 		=> '0' ,
            'limit'  	=> '0' ,
            'sort' 		=> '' ,
            'order' 	=> '' ,
            'params' 	=> '' ,
            'global'	=> 1,
            'exculdeProducts'=>''

        ), $args ));

        $sql ='SELECT
 DISTINCT order_contents.product_id,orders.api_created_at
FROM orders
  JOIN order_contents
    ON orders.id = order_contents.order_id
WHERE orders.is_api_visible = 1
    AND orders.api_created_at >= DATE_SUB(NOW(),INTERVAL 1 DAY) ';

        if(!empty($exculdeProducts)){
            $sql .= " AND order_contents.product_id NOT IN($exculdeProducts)";
        }

        $results =  \DB::select($sql);
        return $results;

    }


    public function allExpenseCategories()
    {
        $sql = "SELECT
  expense_category_mapping.mapped_expense_category,
     IF(mapped_expense_category=0,0,CONCAT(mapped_expense_category,' ',GROUP_CONCAT(order_type.`order_type` ORDER BY order_type.`order_type` ASC SEPARATOR ' | '))) AS expense_category_field
   FROM expense_category_mapping
     JOIN order_type
       ON order_type.id = expense_category_mapping.order_type 
   WHERE product_type IS NULL AND expense_category_mapping.mapped_expense_category > 0 GROUP BY expense_category_mapping.mapped_expense_category ";
        $result = DB::select($sql);
        return $result;
    }

    public function checkProducts($id){
        $product = DB::table('products')->where(['id'=>$id])->first();
        $products = DB::table('products')->where(['vendor_description' => $product->vendor_description, 'sku' => $product->sku, 'case_price' => $product->case_price])->get();
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

    public function toggleDefaultExpenseCategory($state, $id)
    {
        $item = self::find($id);
        if ($state) {
            $item->is_default_expense_category = 1;
        } else {
            $item->is_default_expense_category = 0;
        }
        $item->save();
    }

    public function updateProduct($attributes = array(), $override_inactive = false)
    {

        if (empty($attributes)) {
            return false;
        }
        $items = $this->getProductVariations();
        foreach ($items as $item) {
            $updates = $attributes;
            if ($override_inactive and isset($updates['inactive']) and $updates['inactive'] == 0) {
                $updates['inactive'] = ($item->inactive_by) ? 1 : 0;
            }
            $item->update($updates);
        }
        return true;

    }

    /**
     * @param bool $excludeSelf
     * @return Collection
     */
    public function getProductVariations($excludeSelf = false)
    {
        $items = self::where(['vendor_description' => $this->vendor_description, 'sku' => $this->sku, 'case_price' => $this->case_price]);
        if($excludeSelf){
            $items = $items->where('id','!=',$this->id);
        }
        return $items->get();
    }

    public function getAutoComplete($term, $vendorId, $excludeProductsIds){
        $products = self::select(DB::raw("*,LOCATE('$term',vendor_description) AS pos"))
            ->where('vendor_description', 'like', "%$term%")
            ->where('inactive', 0)
            ->groupBy('vendor_description')
            ->orderBy('pos')
            ->orderBy('vendor_description');

        if (!empty($vendorId)) {
            $products = $products->where('vendor_id', $vendorId);
        }

        if (!empty($excludeProductsIds)) {
            $products = $products->whereNotIn('id', $excludeProductsIds);
        }

        return $products->take(10)->get();
    }
    
    public static function array_remove_object(&$array, $value, $prop)
    {
        return array_filter($array, function($a) use($value, $prop) {
            return $a->$prop !== $value;
        });
    }

    public function master()
    {
        $query = self::where([
            'vendor_description' => $this->vendor_description,
            'sku' => $this->sku,
            'case_price' => $this->case_price,
            'is_default_expense_category' => 1,
        ]);

        return $query->first();
    }

        public static function calculateLineTotalForUnitPrice($product){
            $price = $product->unit_price <= 0 ? $product->case_price : $product->unit_price;
            $product->lineTotal = $product->qty * $price;
        }
        /*
         * generateBarCode() method will generate a unique Alpha numaric string
         */
        public function generateBarCode($id=0){
            $id = $id > 0 ? $id : mt_rand(10000,500000);
            $uniqueCode = str_shuffle(\SiteHelpers::encryptID($id,false,false,mt_rand(10000,5009999)));
            $uniqueCode = strlen($uniqueCode) > 12 ? substr($uniqueCode,0,12):$uniqueCode;
            return $uniqueCode;
        }
    public function totalProductRendomIncreament(){
        $productcount = self::all()->count();
        return $productcount + mt_rand(10000,999999);
    }

    public function getRequiredFields($inDevelopment = 0){
        $rules = [];
        if($inDevelopment) {
            $rules=[
                'vendor_description' => 'required',
                'prod_type_id' => 'required',
                'vendor_id' => 'required',
                'expense_category' => 'required'
            ];
        }else{
            $rules=[
                'vendor_description' => 'required',
                'prod_type_id' => 'required',
                'sku' => "required",
                'case_price' =>'required',
                'unit_price' => 'required',
                'vendor_id' => 'required',
                'expense_category' => 'required'
            ];
        }
        return $rules;
    }

    /**
     * @param Collection $variants
     * @param $productType
     * @return Collection
     */
    public static function filterVariationsByType(Collection $variants, $productType){
        $filteredProducts = new Collection();
        foreach($variants as $product){
            if($product->prod_type_id == $productType){
                $filteredProducts->add($product);
            }
        }
        return $filteredProducts;
    }

    public function setGroupsAndLocations($rows,$exportData = 0)
    {

        $dataArray = [];
        foreach ($rows as $row) {
            $locationGroup = $locations = '';
            $selectedGroups = FEGDBRelationHelpers::getCustomRelationRecords($row->id, self::class, Locationgroups::class, 1, true, false)->pluck('locationgroups_id');
            $selectedLocations = FEGDBRelationHelpers::getCustomRelationRecords($row->id, self::class, location::class, 1, true, false)->pluck('location_id');

            $productTypeId = Ordertyperestrictions::where('order_type',$row->prod_type_id)->value('id');
            $productTypeSelectedGroups = FEGDBRelationHelpers::getCustomRelationRecords($productTypeId,Ordertyperestrictions::class,Locationgroups::class,1,true, false)->pluck('locationgroups_id');
            $productTypeSelectedLocations = FEGDBRelationHelpers::getCustomRelationRecords($productTypeId,Ordertyperestrictions::class,location::class,1,true, false)->pluck('location_id');

            if ($selectedGroups->count() > 0) {
                $locationGroup = Locationgroups::select(\DB::raw('group_concat(name) as names'))->whereIn('id', $selectedGroups->toArray())->get();
            }
            if ($selectedLocations->count() > 0) {
                $locations = location::select(\DB::raw('group_concat(location_name) as location_name'))->where('active',1)->whereIn('id', $selectedLocations->toArray())->get();
            }
            $productTypeLocationGroup = $productTypeLocations = "";
            if ($productTypeSelectedGroups->count() > 0) {
                $productTypeLocationGroup = Locationgroups::select(\DB::raw('group_concat(name) as names'))->whereIn('id', $productTypeSelectedGroups->toArray())->get();
            }
            if ($productTypeSelectedLocations->count() > 0) {
                $productTypeLocations = location::select(\DB::raw('group_concat(location_name) as location_name'))->where('active',1)->whereIn('id', $productTypeSelectedLocations->toArray())->get();
            }
            $data = '';
            if (!empty($locationGroup[0]->names)) {
                $data = $locationGroup[0]->names;
            }
            if (!empty($locations[0]->location_name)) {
                if (!empty($data)) {
                    $data .= "," . $locations[0]->location_name;
                } else {
                    $data .= $locations[0]->location_name;
                }
            }
            $productTypedata = '';
            if (!empty($productTypeLocationGroup[0]->names)) {
                $productTypedata = $productTypeLocationGroup[0]->names;
            }
            if (!empty($productTypeLocations[0]->location_name)) {
                if (!empty($productTypedata)) {
                    $productTypedata .= "," . $productTypeLocations[0]->location_name;
                } else {
                    $productTypedata .= $productTypeLocations[0]->location_name;
                }
            }
            if (!empty($data)) {
                if($exportData == 0) {
                    $data = str_replace(',', '<br>', $data);
                }else{
                    $data = str_replace(',', ', ', $data);
                }
                $row->excluded_locations_and_groups = $data;
            }

            if (!empty($productTypedata)) {
                if($exportData == 0) {
                    $productTypedata = str_replace(',', '<br>', $productTypedata);
                }else{
                    $productTypedata = str_replace(',', ', ', $productTypedata);
                }
                $row->product_type_excluded_data = $productTypedata;
            }
            $dataArray[] = $row;
        }
        return $dataArray;
    }
    public static function subQueriesSelect(){
       $productLabelNewDays = (object) \FEGHelp::getOption('product_label_new', '0', false, true, true);
        $productLabelBackinstockDays = (object) \FEGHelp::getOption('product_label_backinstock', '0', false, true, true);

        $productSubQuery = ' (SELECT COUNT(*) FROM products NP WHERE DATE(NP.created_at) >= (CURRENT_DATE - INTERVAL '.$productLabelNewDays->option_value.' DAY) AND NP.id = products.id) as is_new, ';
        $productSubQuery .= ' (SELECT COUNT(*) FROM products NP1 WHERE DATE(NP1.activated_at) >= (CURRENT_DATE - INTERVAL '.$productLabelBackinstockDays->option_value.' DAY) AND NP1.id = products.id) as is_backinstock ';
        return $productSubQuery;
    }

    /**
     * @param $request
     * @param $id
     * @return array
     */
    public function checkDuplicateProductTypes($request,$id){
        if(is_array($request->prod_sub_type_id))
        {
            if (FEGSystemHelper::isArrayCombinationUnique($request->prod_type_id,$request->prod_sub_type_id)){
                // Array has duplicates
                return [
                    'message' => "Please Select Unique Combinations of Product Type & Sub Type",
                    'status' => 'error'
                ];
            }

            $ItemIds = [];
            foreach($request->itemId as $item){
                if(!empty($item) && $item > 0){
                    $ItemIds[] =  $item;
                }
            }
            $type = [];
            foreach($request->prod_type_id as $item){
                if(!empty($item) && $item > 0){
                    $type[] =  $item;
                }
            }
            $subtype = [];
            foreach($request->prod_sub_type_id as $item){
                if(!empty($item) && $item > 0){
                    $subtype[] =  $item;
                }
            }

            $productName = $request->vendor_description;

            $duplicate = Product::
            whereIn('prod_type_id',$type)
                ->whereIn('prod_sub_type_id',$subtype)
                ->where('sku',$request->sku)
                ->whereNotIn('id',$ItemIds)
                ->where('vendor_description',$productName)
                ->first();
            if(!empty($duplicate))
            {
                return [
                    'message' => "A product with same Product Type & Sub Type already exist",
                    'status' => 'error'
                ];
            }

            $productName = Product::find($id)->vendor_description;


            $duplicate = Product::
            whereIn('prod_type_id', $type)
                ->whereIn('prod_sub_type_id', $subtype)
                ->where('sku', $request->sku)
                ->whereNotIn('id',$ItemIds)
                ->where('vendor_description', $productName)
                ->first();

            if (!empty($duplicate)) {
                return [
                    'message' => "A product with same Product Type & Sub Type already exist",
                    'status' => 'error'
                ];
            }

        }
    }

    /**
     * @param $request
     * @param $id
     * @param $userId
     */
    public function updateReservedQty($request , $id,$userId){

        $product = self::find($id);
        $NewReservedQty = $request->input('reserved_qty');
        if ($product->reserved_qty != $NewReservedQty && $NewReservedQty != '') {
            $type = "negative";
            $reason = "Manual adjustment";
            if(!empty($request->input('reserved_qty_reason'))){
                $reason .='<br>'.$request->input('reserved_qty_reason');
            }
            if ($NewReservedQty > $product->reserved_qty) {
                $type = "positive";
                if($product->reserved_qty_limit < $NewReservedQty) {
                    $product->updateProduct(['send_email_alert' => 0]);
                    $product->save();
                }
            } else if ($NewReservedQty < $product->reserved_qty) {
                $type = "negative";
            }
            $NewReservedQty = $NewReservedQty - $product->reserved_qty;
            if($NewReservedQty < 0 ){
                $NewReservedQty = $NewReservedQty * -1;
            }
            $ReservedQtyLog = new ReservedQtyLog();
            $reservedLogData = [
                "product_id" => $id,
                "adjustment_amount" => $NewReservedQty,
                "adjustment_type" => $type,
                "reserved_qty_reason" => $reason,
                "variation_id" => !empty($product->variation_id) ? $product->variation_id:null,
                "adjusted_by" => $userId,
            ];
            $ReservedQtyLog->insertRow($reservedLogData, 0);
        }
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
            ->where('import_vendors.is_imported','=','0')->groupBy('import_vendors.vendor_id')->get();

        return $vendors;
    }

    /**
     * @param int $isConverted
     * @param bool $isGroupBy
     * @return mixed
     */
    public function getMerchandiseItems($isConverted = 0,$isGroupBy = false)
    {
        $columns = [
            'products.*',
        ];
        $items = self::select($columns)->join('order_type','order_type.id','=','products.prod_type_id')
            ->where('products.is_reserved','=',1)->where('products.is_converted','=',$isConverted)
            ->whereIn('products.prod_type_id',[7,8,6,17,22,24,27]);
        if($isGroupBy){
            $items->groupby('products.vendor_description');
            $items->groupby('products.sku');
            $items->groupby('products.case_price');
        }
       $itemsQuery =  $items->get();

        return $itemsQuery;
    }

    /**
     * @param $items
     */
    public function convertReservedQty($items){
        $prevQty = [];
        if($items->count() > 0){
            foreach($items as $item){
                $prevQty[$item->id] = $item->reserved_qty;
                $product  = self::where([
                    'vendor_description' => $item->vendor_description,
                    'sku' => $item->sku,
                    'case_price' => $item->case_price,
                    'is_converted' => 0,
                ])->update([
                    'reserved_qty' => ($item->reserved_qty * $item->num_items),
                    'is_converted' => 1,
                ]);
            }
            $this->insertItemLog($prevQty);
        }
    }

    /**
     * @description insertItemLog() method was written only for qty conversion script
     */
    public function insertItemLog($prevQty = []){
        $items = $this->getMerchandiseItems(1,false);
        foreach ($items as $item){

            $ReservedQtyLog = new ReservedQtyLog();
            $reservedLogData = [
                "product_id" => $item->id,
                "adjustment_amount" => isset($prevQty[$item->id])?$prevQty[$item->id]:0,
                "adjustment_type" => 'negative',
                "reserved_qty_reason" => '---System Generated Log--- <br> Remove all reserved Qty before converting reserved Qty from Cases to Units.',
                "variation_id" => !empty($item->variation_id) ? $item->variation_id:null,
                "adjusted_by" => Session::get('uid'),
            ];
            $ReservedQtyLog->insertRow($reservedLogData, 0);

            $reservedLogData = [
                "product_id" => $item->id,
                "adjustment_amount" => $item->reserved_qty,
                "adjustment_type" => 'positive',
                "reserved_qty_reason" => '---System Generated Log--- <br> Reserved Product Qty was converted from Case Qty to Unit Qty.',
                "variation_id" => !empty($item->variation_id) ? $item->variation_id:null,
                "adjusted_by" => Session::get('uid'),
            ];
            $ReservedQtyLog->insertRow($reservedLogData, 0);

        }
    }

}
