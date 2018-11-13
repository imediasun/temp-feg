<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Library\FEGDBRelationHelpers;
use App\Models\location;
use App\Models\Ordertyperestrictions;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Null_;
use Validator, Input, Redirect,Image;
use App\Models\ReservedQtyLog;
use App\Models\Locationgroups;
class ProductController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    protected $sortMapping = [];
    protected $sortUnMapping = [];
    public $module = 'product';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Product();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'product',
            'pageUrl' => url('product'),
            'return' => self::returnUrl()
        );
        $this->sortMapping = ['vendor_id' => 'vendor.vendor_name', 'prod_type_id' => 'O.order_type', 'prod_sub_type_id' => 'T.type_description',"img"=>"img"];
        $this->sortUnMapping = ['vendor.vendor_name' => 'vendor_id', 'O.order_type' => 'prod_type_id', 'T.type_description' => 'prod_sub_type_id',"img"=>"img"];


    }
    function returnUrl()
    {
        $pages = (isset($_GET['page']) ? $_GET['page'] : '');
        $sort = (isset($_GET['sort']) ? $_GET['sort'] : '');
        $order = (isset($_GET['order']) ? $_GET['order'] : '');
        $rows = (isset($_GET['rows']) ? $_GET['rows'] : '');
        $search = (isset($_GET['search']) ? $_GET['search'] : '');
        $v1 = (isset($_GET['v1']) ? $_GET['v1'] : '');
        $v2 = (isset($_GET['v2']) ? $_GET['v2'] : '');
        $v3 = (isset($_GET['v3']) ? $_GET['v3'] : '');

        if(strpos($search,"in_development:equal") == false){
            $search .="in_development:equal:0|";
        }
        $appends = array();
        if ($pages != '') $appends['page'] = $pages;
        if ($sort != '') $appends['sort'] = $sort;
        if ($order != '') $appends['order'] = $order;
        if ($rows != '') $appends['rows'] = $rows;
        if ($search != '') $appends['search'] = $search;
        $url = "";
        foreach ($appends as $key => $val) {
            $url .= "&$key=$val";
        }
        return $url;

    }

    /**
     * @param string $t
     * @return \Illuminate\View\View
     */
    public
    function getExport($t = 'excel')
    {
        global $exportSessionID;
        ini_set('memory_limit', '1G');
        set_time_limit(0);

        $exportId = Input::get('exportID');
        if (!empty($exportId)) {
            $exportSessionID = 'export-' . $exportId;
            \Session::put($exportSessionID, microtime(true));
        }

        $info = $this->model->makeInfo($this->module);
        //$master  	= $this->buildMasterDetail();
        if (method_exists($this, 'getSearchFilterQuery')) {
            $filter = $this->getSearchFilterQuery();
        } else {
            $filter = (!is_null(Input::get('search')) ? $this->buildSearch() : '');
        }

        //$filter 	.=  $master['masterFilter'];
//    $params = array(
//        'params' => ''
//    );
        $sort = isset($_GET['sort']) ? $_GET['sort'] : $this->info['setting']['orderby'];
        $order = isset($_GET['order']) ? $_GET['order'] : $this->info['setting']['ordertype'];
        $params = array(
            'params' => '',
            'sort' => $sort,
            'order' => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0),
            'forExcel' => 1
        );


        $results = $this->model->getRows($params);

        $fields = $info['config']['grid'];
        $rows = $results['rows'];
        $rows = $this->model->setGroupsAndLocations($rows,true);
        if($t == 'excel') {
            $results['rows'] = array_map(function ($row) {
                // changing status only for excel correction
                if ($row->inactive == 0) {
                    $row->inactive = 1;
                } else {
                    $row->inactive = 0;
                }
                return $row;
            }, $results['rows']);
        }
        $extra = array(
            'field' => '',
            'alias' => 'departments',
            'language' =>
                array('id' => ''),
            'label' => '',
            'view' => '1',
            'detail' => '1',
            'sortable' => '1',
            'search' => '1',

            'download' => '1',
            'frozen' => '1',
            'limited' => '',
            'width' => '100',
            'align' => 'left',
            'sortlist' => '0',
            'conn' =>
                array(
                    'valid' => '0',
                    'db' => '',
                    'key' => '',
                    'display' => ''),
            'attribute' =>
                array(
                    'hyperlink' => '',
                    array(
                        'active' => '0',
                        'link' => '',
                        'target' => 'modal',
                        'html' => ''),
                    'image' =>
                        array(

                            'active' => '0',
                            'path' => '',
                            'size_x' => '',
                            'size_y' => '',
                            'html' => ''),
                    'formater' =>
                        array(
                            'active' => '0',
                            'value' => '',


                        )));

        $rows = $this->updateDateInAllRows($rows);
        $rows = array_map(function ($element) {
            if (!empty($element->expense_category) && $element->expense_category > 0) {
                $result = $this->getExpenseCategoryById($element->expense_category);
                $element->expense_category = count($result) > 0 ? $result[0]->expense_category : 0;
            }
            return $element;
        }, $rows);


        $content = array(
            'exportID' => $exportSessionID,
            'fields' => $fields,
            'rows' => $rows,
            'title' => $this->data['pageTitle'],
            'excelExcludeFormatting' => isset($results['excelExcludeFormatting']) ? $results['excelExcludeFormatting'] : []
        );

        if ($t == 'word') {

            return view('sximo.module.utility.word', $content);

        } else if ($t == 'pdf') {

            $pdf = PDF::loadView('sximo.module.utility.pdf', $content);
            return view($this->data['pageTitle'] . '.pdf');

        } else if ($t == 'csv') {

            return view('sximo.module.utility.csv', $content);

        } else if ($t == 'print') {

            return view('sximo.module.utility.print', $content);

        } else {

            return view('sximo.module.utility.excel', $content);
        }
    }

    public function getSearch($mode = 'ajax')
    {

        $this->data['tableForm'] = $this->info['config']['forms'];
        $this->data['tableGrid'] = $this->info['config']['grid'];
        $this->data['searchMode'] = $mode;
        $this->data['typeRestricted'] = ['isTypeRestricted' => false ,'displayTypeOnly' => ''];
        $this->data['excluded_locations'] = $this->getUsersExcludedLocations();

        if($this->model->isTypeRestrictedModule($this->module)){
            if($this->model->isTypeRestricted()){
                $this->data['typeRestricted'] = [
                    'isTypeRestricted' => $this->model->isTypeRestricted(),
                    'displayTypeOnly' => $this->model->getAllowedTypes(),
                ];
            }
        }


        $productTypeExcludedbyLocation = FEGDBRelationHelpers::getExcludedProductTypesOnly();

        if(count($productTypeExcludedbyLocation) > 0){
            $this->data['typeRestricted']['isTypeRestrictedExclude'] =true;
            $this->data['typeRestricted']['excluded'] = $productTypeExcludedbyLocation;
        }



        if ($this->info['setting']['hideadvancedsearchoperators'] == 'true') {
            return view('feg_common.search', $this->data);
        } else {
            return view('sximo.module.utility.search', $this->data);
        }

    }
    public function getModify(){
        $query ="SELECT products.*  FROM `products` WHERE vendor_description REGEXP '[ ]{2,}'";
        $products = DB::select($query);

        foreach($products as $pro){
            $vendor_desc = trim(preg_replace('/\s+/',' ', $pro->vendor_description));
            DB::table('products')
                ->where('id', $pro->id)
                ->update(['vendor_description' => $vendor_desc]);
        }

    }

    /**
     * @param null $customQueryString
     * @return string
     */
    public function getSearchFilterQuery($customQueryString = null) {
        // Filter Search for query
        // build sql query based on search filters


        // Get custom Ticket Type filter value
        $globalSearchFilter = $this->model->getSearchFilters([
            'search_all_fields' => '',
            'upc_barcode' => '',
            'vendor_id' => '',
            'prod_type_id'=>'',
            'prod_sub_type_id'=>'',
            'in_development'=>'',
            'inactive' => '',
        ]);
        $skipFilters = ['search_all_fields'];

        $excludedProductsAndTypes = FEGDBRelationHelpers::getExcludedProductTypeAndExcludedProductIds();
        $excludedProductTypeIdsString   = implode(',', $excludedProductsAndTypes['excluded_product_type_ids']);
        $excludedProductIdsString       = implode(',', $excludedProductsAndTypes['excluded_product_ids']);

        $mergeFilters = [];

        if($excludedProductTypeIdsString != '' ){
            array_push($mergeFilters, [
                "field"     =>  'prod_type_id',
                "operater"  =>  'not_in',
                'value'     =>  $excludedProductTypeIdsString
            ]);
        }

        if($excludedProductIdsString != '' ){
            array_push($mergeFilters, [
                "field"     =>  'id',
                "operater"  =>  'not_in',
                'value'     =>  $excludedProductIdsString
            ]);
        }

       /* Example: $mergeFilters = [
            ["field"=>'prod_type_id',"operater"=>'not_in','value'=>'comma seprated values here'],
            ["field"=>'product_id',"operater"=>'not_in','value'=>'comma seprated values here'],
        ];*/
        extract($globalSearchFilter); //search_all_fields

        // rebuild search query skipping 'ticket_custom_type' filter
        $trimmedSearchQuery = $this->model->rebuildSearchQuery($mergeFilters, $skipFilters, $customQueryString);
        $searchInput = $trimmedSearchQuery;
        if (!empty($search_all_fields)) {
            $searchFields = [
                    'O.order_type',
                    'T.type_description',
                    'vendor.vendor_name',
                    'products.vendor_description',
                    'products.case_price',
                    'products.retail_price',
                    'products.unit_price',
                    'T.type_description',
                    'products.expense_category',
                    'products.sku',
                    'products.size',
                    'products.item_description',
                    'products.ticket_value',
                    'products.details'
                ];
            $searchInput = ['query' => $search_all_fields, 'fields' => $searchFields];
        }


        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null(Input::get('search')) ? '' : $this->buildSearch($searchInput,'not_in');

        $filter .= is_null($trimmedSearchQuery) ? '' : $this->buildSearch($trimmedSearchQuery,'not_in');

        return $filter;
    }
    
    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('product.index', $this->data);
    }

    public function postData(Request $request)
    {

        $prod_list_type = isset($_GET['prod_list_type']) ? $_GET['prod_list_type'] : '';
        $active = isset($_GET['active']) ? $_GET['active'] : '';
        $sub_type = isset($_GET['sub_type']) ? $_GET['sub_type'] : '';


        $module_id = \DB::table('tb_module')->where('module_name', '=', 'product')->pluck('module_id');
        $this->data['module_id'] = $module_id;
        if (Input::has('config_id')) {
            $config_id = Input::get('config_id');
            \Session::put('config_id',$config_id);
        } elseif (\Session::has('config_id')) {
            $config_id = \Session::get('config_id');
        } else {
            $config_id = 0;
        }
        $this->data['config_id'] = $config_id;
        $config = $this->model->getModuleConfig($module_id, $config_id);
        if (!empty($config)) {
            $this->data['config'] = \SiteHelpers::CF_decode_json($config[0]->config);
            \Session::put('config_id', $config_id);
        }
        $sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
        $order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
        // End Filter sort and order for query
        // Filter Search for query
        $filter = $this->getSearchFilterQuery();

        //(!is_null($request->input('search')) ? $this->buildSearch() : '');

        if(strpos($filter,"products.in_development") == false){
        $filter .= ' AND products.in_development = 0 ';
        }
        if(strpos($request->input('search'),'in_development:equal:1') > -1){
        $filter = str_replace("products.in_development = '0'","products.in_development = 1",$filter);
        }
        $filter = str_replace("AND products.in_development = '2'"," ",$filter);

        $sort = !empty($this->sortMapping) && isset($this->sortMapping[$sort]) ? $this->sortMapping[$sort] : $sort;

        $page = $request->input('page', 1);
        $params = array(
            'page' => $page,
            'limit' => (!is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : $this->info['setting']['perpage']),
            'sort' => $sort,
            'order' => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0),

        );
        // Get Query
        if ($prod_list_type) {
            $this->data['product_list_type'] = $prod_list_type;
            $this->data['active_prod'] = $active;
        } else {
            $this->data['product_list_type'] = 'select';
            $this->data['active_prod'] = 0;
        }
        $this->data['sub_type']=$sub_type;
        $results = $this->model->getRows($params, $prod_list_type, $active,$sub_type);
        $params['sort'] = !empty($this->sortUnMapping) && isset($this->sortUnMapping[$sort]) ? $this->sortUnMapping[$sort] : $sort;;

        $rows = $results['rows'];
        $ExpenseCategories = $this->model->allExpenseCategories();
        $rows = $this->model->setGroupsAndLocations($rows);
//die;
        $this->data['ExpenseCategories'] = $ExpenseCategories;

        $productTypeExcludedbyLocation = FEGDBRelationHelpers::getExcludedProductTypesOnly();

        $this->data['productTypeExcludedbyLocation'] = $productTypeExcludedbyLocation;

        foreach ($rows as $index => $data) {
            if ($data->is_reserved == 1) {
                $data->is_reserved = "Yes";

            } else {
                $data->is_reserved = "No";
            }
            if ($data->inactive == 1) {
                $data->inactive = "Yes";

            } else {
                $data->inactive = "No";
            }
            if ($data->hot_item == 1) {
                $data->hot_item = "Yes";

            } else {
                $data->hot_item = "No";
            }
            /*
			$product_type = \DB::select("Select product_type FROM product_type WHERE id = '".$data->prod_type_id ."'");
			$rows[$index]->prod_type_id = (isset($product_type[0]->product_type) ? $product_type[0]->product_type : '');
			$product_sub_type = \DB::select("Select product_type FROM product_type WHERE id = ".$data->prod_sub_type_id ."");
			$rows[$index]->prod_sub_type_id = (isset($product_sub_type[0]->product_type) ? $product_sub_type[0]->product_type : '');

           $vendor = \DB::select("Select vendor_name FROM vendor WHERE id = ".htmlentities($data->vendor_id) ."");
			$rows[$index]->vendor_id = (isset($vendor[0]->vendor_name) ? $vendor[0]->vendor_name : '');
            */
        }
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
            ($results['total'] > 0 ? $results['total'] : '1')));

        $pagination->setPath('product/data');
        if(!empty($sort)) {
            if (in_array($sort, ['excluded_locations_and_groups'])) {
                usort($rows, function ($a, $b) use ($sort, $order) {

                    if ($order == 'asc') {
                        return strcmp($a->$sort, $b->$sort) > 0;
                    } else {
                        return strcmp($a->$sort, $b->$sort) < 0;
                    }
                });

            }
        }
        $this->data['param'] = $params;
        $this->data['rowData'] = $rows;
        // Build Pagination
        $this->data['pagination'] = $pagination;
        // Build pager number and append current param GET
        $this->data['pager'] = $this->injectPaginate();
        // Row grid Number
        $this->data['i'] = ($page * $params['limit']) - $params['limit'];
        // Grid Configuration
        $this->data['tableGrid'] = $this->info['config']['grid'];
        $this->data['tableForm'] = $this->info['config']['forms'];
        $this->data['colspan'] = \SiteHelpers::viewColSpan($this->info['config']['grid']);
        // Group users permission
        $this->data['access'] = $this->access;
        // Detail from master if any
        $this->data['setting'] = $this->info['setting'];
        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
        if ($this->data['config_id'] != 0 && !empty($config)) {
            $this->data['tableGrid'] = \SiteHelpers::showRequiredCols($this->data['tableGrid'], $this->data['config']);
        }
        // Render into template
        return view('product.table', $this->data);

    }


    function getUpdate(Request $request, $id = null)
    {

        if ($id == '') {
            if ($this->access['is_add'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        if ($id != '') {
            if ($this->access['is_edit'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->find($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('products');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;
        $excludedOrderTypesArray = FEGDBRelationHelpers::getExcludedProductTypeAndExcludedProductIds(null, true, false)['excluded_product_type_ids'];
        $this->data['excludedProductTypes'] = implode(',', $excludedOrderTypesArray);

        return view('product.form', $this->data);
    }

    public function getShow($id = null)
    {

        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('products');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('product.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM products ") as $column) {

            if ($column->Field != 'id' && $column!='variation_id')
                $columns[] = $column->Field;

        }

        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO products (" . implode(",", $columns) . ") ";
        $columns[1] = "CONCAT('copy ".mt_rand()." ',vendor_description)";
        $column = str_replace("variation_id"," SUBSTRING(UUID(),1,10) as variation_id ",implode(",", $columns));

        $sql .= " SELECT " .$column. " FROM products WHERE id IN (" . $toCopy . ")";

         \DB::insert($sql);
        $lastInsertId = \DB::getPdo()->lastInsertId();


        for($i=0; $i<count($request->input('ids')); $i++) {
            if($i > 0) {
                $lastInsertId = $lastInsertId + 1;
            }
            $Product = product::find($lastInsertId);
            $type = "negative";

                if ($Product->reserved_qty > 0) {
                    $type = "positive";
                } else if ($Product->reserved_qty < 0) {
                    $type = "negative";
                }
                $ReservedQtyLog = new ReservedQtyLog();
                $reservedLogData = [
                    "product_id" => $Product->id,
                    "adjustment_amount" => ($Product->reserved_qty < 0 ? ($Product->reserved_qty * -1) : $Product->reserved_qty),
                    "adjustment_type" => $type,
                    "variation_id" => $Product->variation_id,
                    "adjusted_by" => \AUTH::user()->id,
                ];
                $ReservedQtyLog->insertRow($reservedLogData, 0);


        }


        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }


    function postSave(Request $request, $id = 0)
    {

        $product = "";
        $rules = $this->validateForm();
        if(!empty($request->product_id) || $id){
            $productID = !empty($request->product_id) ?  $request->product_id:$id;
            $product = product::find($productID);

        }

        $customMessages = [
            'min'=> 'UPC/Barcode can be of 12 character only. Combination of alphabets and digits only.',
            'max'=> 'UPC/Barcode can be of 12 character only. Combination of alphabets and digits only.',
            'regex'=> 'UPC/Barcode can be of 12 character only. Combination of alphabets and digits only.',
            'unique'=> 'UPC/Barcode needs to be unique for each item.',

        ];
        $varients =  ($product) ? $product->getProductVariations()->pluck("id")->toArray() :[0];
        $rules['upc_barcode'] = 'min:12|max:12|regex:/^[a-zA-Z0-9\s]+$/';
        $validator = Validator::make($request->all(), $rules,$customMessages);
        $customValidate =  (strlen(trim($request->input('upc_barcode'))) > 0 ) ? $this->model->ValidateRequest($request->all(), $rules,$customMessages,['id'=>$varients],['upc_barcode' => $request->input('upc_barcode')],'UPC/Barcode can be of 12 character only. Combination of alphabets and digits only.'):$customValidate['error'] = false;

        if ($validator->passes() && !$customValidate['error']) {

            if ($id != 0) {
                $Product = product::find($id);
                $NewReservedQty = $request->input('reserved_qty');
                if ($Product->reserved_qty != $NewReservedQty && $NewReservedQty != '') {
                    $type = "negative";
                    if ($NewReservedQty > $Product->reserved_qty) {
                        $type = "positive";
                        if($Product->reserved_qty_limit < $NewReservedQty) {
                            $Product->updateProduct(['send_email_alert' => 0]);
                            $Product->save();
                        }
                    } else if ($NewReservedQty < $Product->reserved_qty) {
                        $type = "negative";
                    }
                    $NewReservedQty = $NewReservedQty - $Product->reserved_qty;
                    if($NewReservedQty < 0 ){
                        $NewReservedQty = $NewReservedQty * -1;
                    }
                    $ReservedQtyLog = new ReservedQtyLog();
                    $reservedLogData = [
                        "product_id" => $id,
                        "adjustment_amount" => $NewReservedQty,
                        "adjustment_type" => $type,
                        "variation_id" => !empty($Product->variation_id) ? $Product->variation_id:null,
                        "adjusted_by" => \AUTH::user()->id,
                    ];
                    $ReservedQtyLog->insertRow($reservedLogData, 0);
                }
            }
        }else{
            $message = $this->validateListError($validator->getMessageBag()->toArray());
            return response()->json(array(
                'message' => ($customValidate['error']) ? $customValidate['customMessage']:$message,
                'status' => 'error'
            ));
        }

        //to remove the extra spaces im between the string
        $request->vendor_description = trim(preg_replace('/\s+/',' ', $request->vendor_description));

        $vendorDescriptin = $request->vendor_description;
 /*       if(strpos($vendorDescriptin,"&") !=false || strpos($vendorDescriptin,",") !=false || strpos($vendorDescriptin,'"'))
        {
            return response()->json(array(
                'message' => "Item name cannot have & , or \" (ampersand, comma or quotation marks)",
                'status' => 'error'
            ));
        }*/
        if(is_array($request->prod_sub_type_id) && $id == 0)
        {
            if(count(array_unique($request->prod_sub_type_id))<count($request->prod_sub_type_id))
            {
                // Array has duplicates
                return response()->json(array(
                    'message' => "Please Select Unique Combinations of Product Type & Sub Type",
                    'status' => 'error'
                ));
            }
        }
        else
        {
            $type = is_array($request->prod_type_id)?$request->prod_type_id[0]:$request->prod_type_id;
            $subtype = is_array($request->prod_sub_type_id)?$request->prod_sub_type_id[1]:$request->prod_sub_type_id;


            $productName = $request->vendor_description;

            $duplicate = Product::
            where('prod_type_id',$type)
            ->where('prod_sub_type_id',$subtype)
            ->where('sku',$request->sku)
            ->where('id','!=',$id)
            ->where('vendor_description',$productName)
                ->first();
            if(!empty($duplicate))
            {
                return response()->json(array(
                    'message' => "A product with same Product Type & Sub Type already exist",
                    'status' => 'error'
                ));
            }

            $productName = Product::find($id)->vendor_description;

            $duplicate = Product::
            where('prod_type_id', $type)
                ->where('prod_sub_type_id', $subtype)
                ->where('sku', $request->sku)
                ->where('id', '!=', $id)
                ->where('vendor_description', $productName)
                ->first();
            if (!empty($duplicate)) {
                return response()->json(array(
                    'message' => "A product with same Product Type & Sub Type already exist",
                    'status' => 'error'
                ));
            }

        }

        if ($request->hasFile('img'))
        {
            $file = $request->file('img');
            $img = Image::make($file->getRealPath());
            $mime = $img->mime();
            if ($mime == 'image/jpeg') {
                $extension = '.jpg';
            } elseif ($mime == 'image/png') {
                $extension = '.png';
            } elseif ($mime == 'image/gif') {
                $extension = '.gif';
            } else {
                $extension = '';
            }
        }

        unset($request->excluded_locations_and_groups);
        $rules = $this->validateForm();
        $rules['img'] = 'mimes:jpeg,gif,png';
        //$rules['sku'] = 'required';

        $rules['expense_category'] = 'required';


        $request->Product_Type = $request->prod_type_id;
        $request->Vendor = $request->vendor_id;

        $rules['vendor_description'] = 'required';
        $rules['prod_type_id'] = 'required';
        $rules['sku'] = "required";
        $rules['case_price'] = 'required';
        $rules['unit_price'] = 'required';
        $rules['vendor_id'] = 'required';
        $excludedLocationsAndGroups = $request->excluded_locations_and_groups;
        $productTypeExcludedLocationsAndGroups = $request->product_type_excluded_data;
        $productTypeId = $request->prod_type_id;
        unset($request->excluded_locations_and_groups);
        unset($request->product_type_excluded_data);
        $validator = Validator::make($request->all(), $rules);
        $retail_price = $request->get('retail_price');


        $product_categories = $request->get('prod_type_id');
        if ($validator->passes()) {

            if ($id == 0) {
                $data = $this->validatePost('products');
                $data['vendor_description'] = trim(preg_replace('/\s+/',' ', $data['vendor_description']));

            }
            else {
                //for inline editing all fields do not get saved
                $data = $this->validatePost('products',true);
                $data['vendor_description'] = trim(preg_replace('/\s+/',' ', $data['vendor_description']));
                $data['netsuite_description'] = "$id...".$data['vendor_description'];
            }

            if($id == 0 || empty($id)){
                $UniqueID = substr(md5(md5(time()+time())."-".md5(time())),0,10);
                $data['variation_id'] = $UniqueID;
            }
            $postedtoNetSuite = $data['vendor_description'];

            if(strlen( $data['vendor_description'])>53){
                $postedtoNetSuite = mb_substr($data['vendor_description'],0,53);
            }



            if($id>0) {
                $products_combined = $this->model->checkProducts($id);
                $hot_items=0;
                if(!empty($request->input('hot_item')) && $request->input('hot_item')>0){
                    $hot_items = "'1'";
                }else if(!empty($request->input('hot_item')) && $request->input('hot_item')==0){
                    $hot_items = "'0'";
                }else{
                    $hot_items = "null";
                }
                \DB::update("update products set hot_item=$hot_items where id='$id'");

            }

            if (isset($data['inactive'])) {
                if ($data['inactive']) {
                    $data['inactive_by'] = Auth::user()->id;
                } else {
                    $data['inactive_by'] = NULL;
                }
            }


            if(is_array($product_categories) && $id > 0){


                $products_combined = $this->model->checkProducts($id);
                unset($data['is_default_expense_category']);
                unset($data['excluded_locations_and_groups']);
                $data_attached_products= $data;

                foreach($products_combined as $pc){
                    $data['netsuite_description'] = $pc->id."...".$postedtoNetSuite;
                    if($pc->id == $id){
                        $data['prod_type_id'] = $data['prod_type_id'][0];
                        $data['prod_sub_type_id'] = $data['prod_sub_type_id'][1];
                        $data['expense_category'] = $data['expense_category'][1];
                        $data['retail_price'] = $data['retail_price'][1];
                        $data['ticket_value'] = $data['ticket_value'][1];
                        $this->model->insertRow($data, $id);
                    }else{

                        unset($data_attached_products['prod_type_id']);
                        unset($data_attached_products['prod_sub_type_id']);
                        unset($data_attached_products['expense_category']);
                        unset($data_attached_products['retail_price']);
                        unset($data_attached_products['ticket_value']);
                        unset($data_attached_products['inactive']);
                        unset($data_attached_products['inactive_by']);
                        unset($data_attached_products['in_development']);

                        $this->model->insertRow($data_attached_products,$pc->id);
                    }
                    $netsuite_description['netsuite_description'] = $pc->id."...".$postedtoNetSuite;
                    $this->model->insertRow($netsuite_description, $pc->id);

                        $this->insertRelations($excludedLocationsAndGroups,$productTypeExcludedLocationsAndGroups,$pc->id,$productTypeId);
                }
                $isDefaultExpenseCategory = $request->input("is_default_expense_category");
                if ($id > 0 && $isDefaultExpenseCategory > 0) {
                    $this->model->setDefaultExpenseCategory($id);
                }

            }elseif(is_array($product_categories))
            {

                $ids = [];
                $count = 1;
                unset($data['excluded_locations_and_groups']);
                unset($data['product_type_excluded_data']);
                $prodData = $data;
                foreach ($product_categories as $category) {
                    $prodData['retail_price'] = (isset($retail_price[$count]) && !empty($retail_price[$count])) ? $retail_price[$count] : 0;
                    $prodData['ticket_value'] = (isset($data['ticket_value'][$count]) && !empty($data['ticket_value'][$count])) ? $data['ticket_value'][$count] : 0;
                    $prodData['prod_type_id'] = $category;
                    $prodData['prod_sub_type_id'] = (isset($data['prod_sub_type_id'][$count]) && !empty($data['prod_sub_type_id'][$count])) ? $data['prod_sub_type_id'][$count] : 0;
                    $prodData['expense_category'] = (isset($data['expense_category'][$count]) && !empty($data['expense_category'][$count])) ? $data['expense_category'][$count] : 0;
                    $prodData['netsuite_description'] = mb_substr(time()."-".$count."...".$data['vendor_description'],0,60);
                    $count++;
                    /*
                     * commented as per Gabe request on 9/13/2017
                    if($data['prod_type_id'] != 8){
                        $data['retail_price'] = 0.000;
                    }*/

                    $ids[] = $this->model->insertRow($prodData, $id);

                }
                if (isset($ids) && count($ids) > 0) {
                    $Product = product::find($ids[0]);
                    $type = "negative";
                    if ($Product->reserved_qty > 0) {
                        $type = "positive";
                    } else if ($Product->reserved_qty < 0) {
                        $type = "negative";
                    }
                    $ReservedQtyLog = new ReservedQtyLog();
                    $reservedLogData = [
                        "product_id" => $Product->id,
                        "adjustment_amount" => ($Product->reserved_qty < 0 ? ($Product->reserved_qty * -1):$Product->reserved_qty),
                        "adjustment_type" => $type,
                        "variation_id" => $Product->variation_id,
                        "adjusted_by" => \AUTH::user()->id,
                    ];
                    $ReservedQtyLog->insertRow($reservedLogData, 0);
                }
                foreach ($ids as $id)
                {
                    $postedtoNetSuite = $data['vendor_description'];

                    if(strlen( $data['vendor_description'])>53){
                        $postedtoNetSuite = mb_substr($data['vendor_description'],0, 53);
                    }

                    $updates = array();
                    $updates['netsuite_description'] = "$id...".$postedtoNetSuite;
                    if (isset($img)) {

                        $newfilename = $id . '' . $extension;
                        $img_path='./uploads/products/' . $newfilename;
                        $img->save($img_path);
                        $updates['img'] = $newfilename;

                    }
                    $this->model->insertRow($updates, $id);
                    $this->model->setFirstDefaultExpenseCategory($id);

                        $this->insertRelations($excludedLocationsAndGroups,$productTypeExcludedLocationsAndGroups,$id,$productTypeId);
                }

            }
            else
            {


                $products_combined = $this->model->checkProducts($id);
                unset($data['excluded_locations_and_groups']);
                $data_attached_products= $data;
                foreach($products_combined as $pc){
                    if($pc->id == $id){
                        $this->model->insertRow($data, $id);
                    }else{

                        unset($data_attached_products['prod_type_id']);
                        unset($data_attached_products['prod_sub_type_id']);
                        unset($data_attached_products['expense_category']);
                        unset($data_attached_products['retail_price']);
                        unset($data_attached_products['ticket_value']);

                        $this->model->insertRow($data_attached_products,$pc->id);
                    }
                    $postedtoNetSuite = $data['vendor_description'];

                    if(strlen( $data['vendor_description'])>53){
                        $postedtoNetSuite = mb_substr($data['vendor_description'],0,53);
                    }
                    $netsuite_description['netsuite_description'] = $pc->id."...".$postedtoNetSuite;
                    $this->model->insertRow($netsuite_description, $pc->id);


                        $this->insertRelations($excludedLocationsAndGroups,$productTypeExcludedLocationsAndGroups,$pc->id,$productTypeId);
                }
            }

            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));

        } else {

            $message = $this->validateListError($validator->getMessageBag()->toArray());
            return response()->json(array(
                'message' => $message,
                'status' => 'error'
            ));
        }

    }
    public function insertRelations($excludedLocationsAndGroups,$productTypeExcludedLocationsAndGroups,$id,$productTypeId = 0){

        $excludedLocationsAndGroups = is_array($excludedLocationsAndGroups) ? $excludedLocationsAndGroups:[$excludedLocationsAndGroups];
        $productTypeExcludedLocationsAndGroups = is_array($productTypeExcludedLocationsAndGroups) ? $productTypeExcludedLocationsAndGroups:[$productTypeExcludedLocationsAndGroups];

        FEGDBRelationHelpers::destroyCustomRelation(product::class, Locationgroups::class, 1, 0, $id);
        FEGDBRelationHelpers::destroyCustomRelation(product::class, location::class, 1, 0, $id);

        FEGDBRelationHelpers::destroyCustomRelation(Locationgroups::class,product::class,  1, $id,0 );
        FEGDBRelationHelpers::destroyCustomRelation(location::class,product::class, 1, $id, 0);

        FEGDBRelationHelpers::destroyCustomRelation(Ordertyperestrictions::class, Locationgroups::class, 1, 0, $productTypeId);
        FEGDBRelationHelpers::destroyCustomRelation(Ordertyperestrictions::class, location::class, 1, 0, $productTypeId);

        FEGDBRelationHelpers::destroyCustomRelation(Locationgroups::class,Ordertyperestrictions::class,  1, $productTypeId,0 );
        FEGDBRelationHelpers::destroyCustomRelation(location::class,Ordertyperestrictions::class, 1, $productTypeId, 0);


            if (is_array($excludedLocationsAndGroups) && count($excludedLocationsAndGroups) > 0 && $excludedLocationsAndGroups[0] !=null) {

                foreach ($excludedLocationsAndGroups as $excludedLocationsAndGroup) {
                    $splitValue = explode('_', $excludedLocationsAndGroup);
                    if ($splitValue[0] == 'group') {
                        FEGDBRelationHelpers::insertCustomRelation($id, $splitValue[1], product::class, Locationgroups::class, 1);
                    } else {
                        FEGDBRelationHelpers::insertCustomRelation($id, $splitValue[1], product::class, location::class, 1);
                    }
                }
            }

        if (is_array($productTypeExcludedLocationsAndGroups) && count($productTypeExcludedLocationsAndGroups) > 0 && $productTypeExcludedLocationsAndGroups[0] !=null) {

            foreach ($productTypeExcludedLocationsAndGroups as $productTypeExcludedLocationsAndGroup) {
                $splitValue = explode('_', $productTypeExcludedLocationsAndGroup);
                if ($splitValue[0] == 'group') {
                    FEGDBRelationHelpers::insertCustomRelation($productTypeId, $splitValue[1], Ordertyperestrictions::class, Locationgroups::class, 1);
                } else {
                    FEGDBRelationHelpers::insertCustomRelation($productTypeId, $splitValue[1], Ordertyperestrictions::class, location::class, 1);
                }
            }
        }

        }


    public function postDelete(Request $request)
    {

        if ($this->access['is_remove'] == 0) {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_restric')
            ));
        }
        $errorMessages = [];
        $ids = $request->input('ids');
        // delete multipe rows
        if (count($ids) >= 1) {
            $deletedIds = [];
            $errorId = [];
            $variationsErrorId = [];
            foreach ($ids as $id) {
                if (!in_array($id, $deletedIds)) {
                    $productVariation = Product::find($id);
                    $productVariations = $productVariation->getProductVariations();
                    $variations = new Collection();
                    foreach ($productVariations as $productVariation) {
                        if (in_array($productVariation->id, $ids)) {
                            //$variations[] = $productVariation;
                            $variations->add($productVariation);
                        }
                    }
                    //case when user has selected all variations of a product then delete all variations
                    if ($productVariations->count() == $variations->count()) {

                        $variations->each(function ($product) {
                            $product->delete();
                        });
                        foreach ($variations as $variation) {
                            $deletedIds[] = $variation->id;
                        }
                    } else if ($productVariations->count() - 1 == $variations->count()) {

                        //delete all variation including default varation
                        $variations->each(function ($product) {
                            $product->delete();
                        });
                        $remainingItem = $productVariations->diff($variations);
                        $remainingItem->each(function ($product) {
                            $product->is_default_expense_category = 1;
                            $product->save();
                        });
                        foreach ($variations as $variation) {
                            $deletedIds[] = $variation->id;
                        }
                    } else if ($productVariations->count() > $variations->count()) {

                        foreach ($variations as $productVariation) {
                            if ($productVariation->is_default_expense_category == 1) {
                                //Need to test that
                                foreach ($variations as $variation) {
                                    $errorId[] = $variation->id;
                                }
                                if (!in_array($productVariation->id, $variationsErrorId)) {
                                    $variationsErrorId[] = $productVariation->id;
                                    $errorMessages[] = [
                                        'status' => 'error',
                                        'message' => "Selected product variant currently defines the default expense category for this product in the Products API. Please mark a different variant of this product as the default expense category before removing this variant."
                                    ];
                                }

                            }
                        }
                    }
                    if (!in_array($id, $errorId)) {
                        $this->model->destroy([$id]);
                    }
                }
            }

            if (count($errorMessages) > 0) {
                return response()->json($errorMessages);
            }

            /* $hasDefaultExpenseCategory = $this->model->hasDefaultExpenseCategory($id);
             if ($hasDefaultExpenseCategory == true) {
                 return response()->json(array(
                     'status' => 'error',
                     'message' => "Selected product variant currently defines the default expense category for this product in the Products API. Please mark a different variant of this product as the default expense category before removing this variant."
                 ));
             }*/
            //$this->model->destroy($request->input('ids'));

            return response()->json([array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success_delete')
            )]);
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_error')
            ));

        }

    }

    function getUpload($id = NULL)
    {
        $data['img'] = \DB::table('products')->where('id', $id)->pluck('img');
        $data['return'] = "";
        return view('product.upload', $data);
    }
    function getUploadPopup($id = NULL)
    {
        $data['img'] = \DB::table('products')->where('id', $id)->pluck('img');
        $data['return'] = "";
        return view('product.upload-image-popup', $data);
    }

    function postListcsv(Request $request)
    {
        global $exportSessionID;
        ini_set('memory_limit', '1G');
        set_time_limit(0);

        $exportId = Input::get('exportID');
        if (!empty($exportId)) {
            $exportSessionID = 'export-'.$exportId;
            \Session::put($exportSessionID, microtime(true));
        }
        
        $vendor_id = $request->vendor_id;
        $rows = $this->model->getVendorPorductlist($vendor_id);
        $fields = array('Vendor', 'Description', 'Sku','UPC/Barcode', 'Unit Price', 'Item Per Case', 'Case Price', 'Ticket Value', 'Order Type', 'Product Type', 'INACTIVE', 'Reserved Qty');
        $this->data['pageTitle'] = 'ProductList_';
        $content = array(
            'exportID' => $exportSessionID,
            'fields' => $fields,
            'rows' => $rows,
            'type' => 'move',
            'title' => $this->data['pageTitle'],
        );
        return view('product.csvhistory', $content);
    }


    function postUpload(Request $request)
    {

        $files = array('img' => Input::file('img'));
        // setting up rules
        $rules = array('img' => 'required|mimes:jpeg,gif,png'); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($files, $rules);
        $id = Input::get('id');

        if ($validator->fails()) {
            // send back to the page with the input data and errors
            return Redirect::to('product/upload/' . $id)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'Please select an Image..')->withErrors($validator);;

        } else {
            $updates = array();
            $file = $request->file('img');
            $destinationPath = './uploads/products/';
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //if you need extension of the file
            $newfilename = $id . mt_rand() . '.' . $extension;
            $uploadSuccess = $request->file('img')->move($destinationPath, $newfilename);
            if ($uploadSuccess) {
                $relatedProducts = $this->model->checkProducts($id);
                $ids = array_map(function($row){return $row->id;}, $relatedProducts);
                \DB::update('update products set img = "' . $newfilename . '" where id IN(' . implode(",", $ids) .')');
            }
            return Redirect::to('product/upload/' . $id)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');

        }


    }
    function postUploadAjax(Request $request)
    {
        $files = array('img' => Input::file('img'));
        // setting up rules
        $rules = array('img' => 'required|mimes:jpeg,gif,png'); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($files, $rules);
        $id = Input::get('id');

        if ($validator->fails()) {
            $message = $this->validateListError($validator->getMessageBag()->toArray());
            return response()->json(array(
                'message' => $message,
                'status' => 'error'
            ));
        } else {
            $updates = array();
            $file = $request->file('img');
            $destinationPath = './uploads/products/';
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //if you need extension of the file
            $newfilename = $id . mt_rand() . '.' . $extension;
            $uploadSuccess = $request->file('img')->move($destinationPath, $newfilename);
            if ($uploadSuccess) {
                $relatedProducts = $this->model->checkProducts($id);
                $ids = array_map(function($row){return $row->id;}, $relatedProducts);
                \DB::update('update products set img = "' . $newfilename . '" where id IN(' . implode(",", $ids) .')');
            }
            return response()->json(array(
                'message' => "Image has been updated",
                'status' => 'success',
                'imagePath' =>url("/uploads/products/".$newfilename),
                'id' => $id,
            ));
        }
    }
    function getTest()
    {
        $row = \DB::select("select id,img from products where id > 2820");
        $img = "";
        foreach ($row as $r) {
            if (!empty($r->img)) {
                $img = $r->id . ".jpg";
                \DB::update("update products set img='" . $img . "'where id=" . $r->id);
            }
        }
    }

    function postTrigger(Request $request)
    {
        $isActive = $request->get('isActive');
        $productId = $request->get('productId');
        if ($isActive == "true") {
            $update = \DB::update('update products set inactive = 1, inactive_by = ' . Auth::user()->id . ' where id=' . $productId);
        } else {
            $update = \DB::update('update products set inactive = 0, in_development = 0, inactive_by = NULL where id=' . $productId);
        }
        if ($update) {
            return response()->json(array(
                'status' => 'success'
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => 'Some Error occurred in Activation'
            ));
        }
    }

    public function postExclude(Request $request)
    {
        $excludeExport = $request->get('excludeExport');
        $productId = $request->get('productId');
        $relatedProducts = $this->model->checkProducts($productId);
        $ids = array_map(function($row){return $row->id;}, $relatedProducts);

        if ($excludeExport == "true") {
            $update = \DB::update('update products set exclude_export = 1 where id IN(' . implode(',', $ids) . ')');
        }
        else
        {
            $update = \DB::update('update products set exclude_export = 0 where id IN(' . implode(',', $ids) . ')');
        }
        if ($update) {
            return response()->json(array(
                'status' => 'success'
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => 'Some Error occurred while excluding from export'
            ));
        }
    }

    function getExpenseCategory(Request $request)
{
    $order_type_id=$request->get('order_type');
    $product_type_id=$request->get('product_type');
    $expense_category="";
    if(!empty($product_type_id))
    {
        $expense_category=\DB::table('expense_category_mapping')->where('order_type',$order_type_id)->where('product_type',$product_type_id)->pluck('mapped_expense_category');
    }
    else
    {
        $expense_category=\DB::table('expense_category_mapping')->where('order_type',$order_type_id)->pluck('mapped_expense_category');
    }
    return json_encode(array('expense_category'=>$expense_category));
}

    function getExpenseCategoryGroups(){
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
    function getExpenseCategoryAjax(Request $request){

        $expense_category = \DB::select("SELECT expense_category_mapping.id,expense_category_mapping.mapped_expense_category,order_type.`order_type`,CONCAT(mapped_expense_category,' ',GROUP_CONCAT(order_type.`order_type` ORDER BY order_type.`order_type` ASC SEPARATOR ' | ')) as order_type
FROM expense_category_mapping
JOIN order_type ON order_type.id = expense_category_mapping.order_type
WHERE product_type IS NULL
GROUP BY mapped_expense_category");

        $items = ['<option value=""> -- Select  -- </option>'];
        foreach ($expense_category as $category){
            $orderType = $category->order_type;
            $categoryId = $category->mapped_expense_category;
            if ($categoryId == 0) {
                /* $orderType = "N/A";
                 $categoryId = "";*/
            } else {
                $items[] = '<option value="' . $categoryId . '"> ' . $orderType . ' </option>';
            }

        }
        $options = implode("",$items);
        echo $options;
    }

    public function postSetdefaultcategory(Request $request)
    {
        $id = $request->input('productId');
        $isdefaultexp = $request->input('isdefault');
        $searchProduct = Product::find($id);
        $products = $this->model->checkProducts($id);

        if ($isdefaultexp == 0 && count($products) > 1 && $searchProduct->is_default_expense_category == 1) {
            return response()->json(array(
                'status' => 'error',
                'message' => "This product variant currently defines the default expense category for this product in the Products API. Please mark a different variant of this product as the default expense category."
            ));
        } elseif (count($products) == 1) {
            $searchProduct->is_default_expense_category = $isdefaultexp;
            $searchProduct->save();

              $this->model->toggleDefaultExpenseCategory($isdefaultexp,$id);
        } else {
            $this->model->setDefaultExpenseCategory($id);
        }
    }

    public function getExpenseCategoryById($expense_category)
    {
        $sql = "SELECT CONCAT(mapped_expense_category,' ',GROUP_CONCAT(order_type.`order_type` ORDER BY order_type.`order_type` ASC SEPARATOR ' | ')) AS expense_category";
        $sql .= " FROM expense_category_mapping 
                  JOIN order_type
                    ON order_type.id = expense_category_mapping.order_type
                WHERE product_type IS NULL AND mapped_expense_category !=0 AND mapped_expense_category = $expense_category 
                GROUP BY mapped_expense_category";
        $result = \DB::select($sql);
        return $result;
    }

    /**
     * This method accepts the comma separated string of ids of productTypes and returns
     * all of the productSubTypes related to these types for the select box.
     * ---------------------------------------------------------------------------------
     * @return mixed
     */
    public function getProductSubtype(){

        $commaSeparatedProductType =  request()->get('product_type_id');
        $productTypes = [];

        if($commaSeparatedProductType != '')
        {
            if(str_contains($commaSeparatedProductType, ','))
                $productTypes = explode(',', $commaSeparatedProductType);
            else
                array_push($productTypes, $commaSeparatedProductType);

            $productSubtypes = ProductType::select('type_description', 'id')->whereIn('request_type_id', $productTypes)->orderBy('type_description', 'asc')->get();
        }
        else
        {
            $productSubtypes = ProductType::select('type_description', 'id')->orderBy('type_description', 'asc')->get();
        }


        return $productSubtypes;
    }
    public function postUpdateBarcode(Request $request){
        $productId = $request->input('id');
        $product = product::find($productId);
        $barCode = '';
        if($product){
            $barCode = $product->generateBarCode($product->id);
            $product->updateProduct(['upc_barcode'=>$barCode]);
        }
        return response()->json(array(
            'status' => 'success',
            'barcode'=>$barCode,
            'variation_id'=>$product ? $product->variation_id:'',
        ));
    }
    public function postGenerateUniqueBarcode(Request $request){
        $productId = $request->input('id');
        $product = new Product();
        $rendomCount = !empty($productId) ? $productId:$product->totalProductRendomIncreament();
        $barCode = $product->generateBarCode($rendomCount);
        return response()->json(array(
            'status' => 'success',
            'barcode'=>$barCode,
        ));
    }
    public function getLocationAndGroups(Request $request,$id = 0){

        if($id == 0 && empty($request->input('mode'))){
            $locationGroups = Locationgroups::where(function($query){
                1 == 1;
            })->orderBy('name','asc')->get();

            $groupsData = '<optgroup label="Location Groups">';
            foreach($locationGroups as $locationGroup){
                $groupsData .= '<option value="group_'.$locationGroup->id.'">'.$locationGroup->name.'</option>';
            }
            $groupsData .='</optgroup>';
            $locations = location::where('active','=',1)->orderBy('id','asc')->get();
            $locationsData = '<optgroup label="Location">';
            foreach($locations as $location){
                $locationsData .= '<option value="location_'.$location->id.'">'.$location->id.' '.$location->location_name.'</option>';
            }
            $locationsData .='</optgroup>';
            return response()->json(['groups'=>$groupsData,"locations"=>$locationsData]);
        }else{
            $selectedGroups = FEGDBRelationHelpers::getCustomRelationRecords($id,product::class,Locationgroups::class,1,true);
            $selectedLocations = FEGDBRelationHelpers::getCustomRelationRecords($id,product::class,location::class,1,true);
            $productType = $request->input('productType');
            $productTypeId = $request->input('productTypeId',0);
            if(!empty($productType)) {
                $productTypeId = Ordertyperestrictions::where('order_type', $productType)->value('id');
            }

                if($productTypeId > 0){
                    $productTypeSelectedGroups = FEGDBRelationHelpers::getCustomRelationRecords($productTypeId,Ordertyperestrictions::class,Locationgroups::class,1,true);
                    $productTypeSelectedLocations = FEGDBRelationHelpers::getCustomRelationRecords($productTypeId,Ordertyperestrictions::class,location::class,1,true);
                }
            $locationGroups = Locationgroups::where(function($query){
                1 == 1;
            })->orderBy('name','asc')->get();
            $selectValues = [];
            $productTypeSelectedValues = [];
            $groupsData = '<optgroup label="Location Groups">';
            foreach($locationGroups as $locationGroup){

                if($selectedGroups->where('locationgroups_id',$locationGroup->id)->count()){
                    $selectValues[] = 'group_'.$locationGroup->id;
                }
                if($productTypeSelectedGroups->where('locationgroups_id',$locationGroup->id)->count()){
                    $productTypeSelectedValues[] = 'group_'.$locationGroup->id;
                }
                $groupsData .= '<option  value="group_'.$locationGroup->id.'">'.$locationGroup->name.'</option>';
            }
            $groupsData .='</optgroup>';
            $locations = location::where('active','=',1)->orderBy('id','asc')->get();
            $locationsData = '<optgroup label="Location">';
            foreach($locations as $location){

                if($selectedLocations->where('location_id',$location->id)->count()){
                    $selectValues[] = 'location_'.$location->id;
                }
                if($productTypeSelectedLocations->where('location_id',$location->id)->count()){
                    $productTypeSelectedValues[] = 'location_'.$location->id;
                }
                $locationsData .= '<option  value="location_'.$location->id.'">'.$location->id.' '.$location->location_name.'</option>';
            }
            $locationsData .='</optgroup>';

            return response()->json(['groups'=>$groupsData,"locations"=>$locationsData,'selectedValues'=>$selectValues,'productTypeSelectedValues' => $productTypeSelectedValues]);

        }
    }
}
