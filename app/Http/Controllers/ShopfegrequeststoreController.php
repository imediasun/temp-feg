<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Library\FEGDBRelationHelpers;
use App\Models\order;
use App\Models\product;
use App\Models\Shopfegrequeststore;
use App\Models\Addtocart;
use App\Models\Sximo;
use \App\Models\Sximo\Module;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Validator, Input, Redirect, URL;
use App\Models\Ordersetting;

class ShopfegrequeststoreController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    protected $sortMapping = [];
    protected $sortUnMapping = [];
    public $module = 'shopfegrequeststore';
    static $per_page = '10';
    const GREAT_WOLF_LODGE_GROUP = 16;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Shopfegrequeststore();
        $this->addToCartModel = new Addtocart();
        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);
        $this->module_id = Module::name2id($this->module);
        $this->pass = \FEGSPass::getMyPass($this->module_id);

        $this->data = array(
            'pass' => $this->pass,
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => $this->module,
            'pageUrl' => url($this->module),
            'return' => self::returnUrl()
        );
        $this->sortMapping = ['vendor_id' => 'vendor.vendor_name', 'prod_type_id' => 'O.order_type', 'prod_sub_type_id' => 'T.product_type'];
        $this->sortUnMapping = ['vendor.vendor_name' => 'vendor_id', 'O.order_type' => 'prod_type_id', 'T.product_type' => 'prod_sub_type_id'];


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

    public function getSearchFilterQuery($customQueryString = null) {
        // Filter Search for query
        // build sql query based on search filters


        // Get custom Ticket Type filter value
        $globalSearchFilter = $this->model->getSearchFilters([
            'search_all_fields' => '',
            'prod_type_id'=>'',
            'prod_sub_type_id'=>'',
        ]);
        $skipFilters = ['search_all_fields','filterby_label'];

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

        extract($globalSearchFilter); //search_all_fields

        // rebuild search query skipping 'ticket_custom_type' filter
        $trimmedSearchQuery = $this->model->rebuildSearchQuery($mergeFilters, $skipFilters, $customQueryString);
        $searchInput = $trimmedSearchQuery;
        if (!empty($search_all_fields)) {
            $searchFields = [
                'products.id',
                'products.vendor_description',
                'products.item_description',
                'products.size',
                'products.unit_price',
                'products.num_items',
                'products.case_price',
                'products.retail_price',
                'products.expense_category',
                'vendor.vendor_name',
                'products.ticket_value',
                'O.order_type',
                'T.product_type',
                'products.sku'
            ];
            $searchInput = ['query' => $search_all_fields, 'fields' => $searchFields];
        }

        // Filter Search for query
        // build sql query based on search filters
        $filter  = is_null(Input::get('search')) ? '' : $this->buildSearch($searchInput, 'not_in');
        $filter .= is_null($trimmedSearchQuery)  ? '' : $this->buildSearch($trimmedSearchQuery, 'not_in');

        return $filter;
    }
    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('shopfegrequeststore.index', $this->data);
    }

    public function postData(Request $request)
    {
        if($this->model->isTypeRestricted()){
           $request->merge(["order_type"=> $this->model->getAllowedTypes()]);
        }
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'shopfegrequeststore')->pluck('module_id');
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

        // Get order_type search filter value
        $priceRangeFilter = $this->model->getSearchFiltersAsArray('', array('price_range' => ''));
        extract($priceRangeFilter);
        
        // rebuild search query skipping 'price_range' filter
        $trimmedSearchQuery = $this->model->rebuildSearchQuery(null, array('price_range'));

        // Filter Search for query
        // build sql query based on search filters
      //Commented below line to implement single Search field in simple search
      //$filter = is_null(Input::get('search')) ? '' : $this->buildSearch($trimmedSearchQuery);
        $filter = $this->getSearchFilterQuery();

        // add special price range query
        if (!empty($price_range)) {
            $pr1 = '';
            if (isset($price_range['value'])) {
                $pr1 = preg_replace('/[^\d\.\-]|\s/', '', ''.$price_range['value']);
            }            
            $pr2 = '';
            if (isset($price_range['value2'])) {
                $pr2 = preg_replace('/[^\d\.\-]|\s/', '', ''.$price_range['value2']);
            }
            if (is_numeric($pr1) || is_numeric($pr2)) {
                if ($pr2 === '') {
                    $pr2 = $pr1;
                }
                if ($pr1 === '') {
                    $pr1 = $pr2;
                }
                $pr1 = (float)$pr1;
                $pr2 = (float)$pr2;

                $filter .= " AND (
                                (products.unit_price   BETWEEN $pr1 AND $pr2) 
                             OR (products.case_price   BETWEEN $pr1 AND $pr2) 
                             OR (products.retail_price BETWEEN $pr1 AND $pr2) 
                            )";
            }
        }
        
        // Filter Search for query
        //$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');

        $page = $request->input('page', 1);
        $sort = !empty($this->sortMapping) && isset($this->sortMapping[$sort]) ? $this->sortMapping[$sort] : $sort;
        $extraSorts = $sort != 'id' ? [] : [
            'is_new' => 'DESC',
            'hot_item'=>'DESC',
//            'is_backinstock' =>'DESC',
        ];

        $extraSorts = $sort == 'is_favorite' ? []:$extraSorts;
        $params = array(
            'page' => $page,
            'limit' => (!is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : $this->info['setting']['perpage']),
            'sort' => $sort,
            'order' => $order,
            'customSorts' => $extraSorts,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0)
        );

        // Get Query
        $type = $request->get('type');
        $this->data['type'] = $type;
        $active_inactive = $request->get('active_inactive');
        $this->data['active_inactive'] = $active_inactive;
        \Session::put('active_inactive', $active_inactive);
        $order_type = $request->get('order_type');
        $this->data['order_type'] = $order_type;
        $product_type = $request->get('product_type');
        $this->data['product_type'] = $product_type;
        $filteredArray = Sximo::getSearchFilters();
        $this->data['filterBy'] = $filterBy = $request->get('filterBy');
        if(empty($filterBy)){
            $filterBy = !empty($filteredArray['filterby_label']) ? $filteredArray['filterby_label']:'';
        }
        $cond = array('type' => $type, 'active_inactive' => $active_inactive, 'order_type' => $order_type, 'product_type' => $product_type,'filterBy'=>$filterBy);
        $results = $this->model->getRows($params, $cond);
        $params['sort'] = !empty($this->sortUnMapping) && isset($this->sortUnMapping[$sort]) ? $this->sortUnMapping[$sort] : $sort;;

        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;


        if (count($results['rows']) == $results['total']) {
            $params['limit'] = $results['total'];
        }
        if ($results['total'] === 0) {
            $params['limit'] = 1;
        }

        $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
            ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('shopfegrequeststore/data');
        $this->data['param'] = $params;
        $this->data['rowData'] = $results['rows'];
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
        $this->data['cart'] = $this->model->shoppingCart();
        $this->data['isTypeRestricted'] = $this->model->isTypeRestricted();
        $this->data['displayTypesOnly'] = $this->model->getAllowedTypes();
        $productTypeExcludedbyLocation = FEGDBRelationHelpers::getExcludedProductTypesOnly();
        $this->data['typeRestricted']['isTypeRestrictedExclude'] =false;
        $this->data['typeRestricted']['excluded']  = [];
        $this->data['isGreatWolfLodge'] = false;
        if(auth()->user()->group_id == self::GREAT_WOLF_LODGE_GROUP)
            $this->data['isGreatWolfLodge'] = true;

        if(count($productTypeExcludedbyLocation) > 0){
            $this->data['typeRestricted']['isTypeRestrictedExclude'] =true;
            $this->data['typeRestricted']['excluded'] = $productTypeExcludedbyLocation;
        }
        // Render into template
        return view('shopfegrequeststore.table', $this->data);

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

        return view('shopfegrequeststore.form', $this->data);
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
        return view('shopfegrequeststore.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM products ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO products (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM products WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = 0)
    {
        $rules = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('products',true);
            $id = $this->model->insertRow($data, $id);

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

    public function postDelete(Request $request)
    {

        if ($this->access['is_remove'] == 0) {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_restric')
            ));
            die;

        }
        // delete multipe rows
        if (count($request->input('ids')) >= 1) {
            $this->model->destroy($request->input('ids'));

            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success_delete')
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_error')
            ));

        }

    }

    function getRecentlyadded()
    {
        if (false) {
            redirect('fegsys/home', 'refresh');
        } else {
            $this->data['recent_products'] = $this->model->getRecentlyAddedProduct();
            return view('shopfegrequeststore.recentlyAddedProducts', $this->data);
        }
    }

    function getNewGraphicRequest()
    {
        return view('shopfegrequeststore.newgraphicrequest', $this->data);
    }

    function postNewgraphic(Request $request)
    {
        $rules['myInputs'] = 'required';
        $messages = [ 'myInputs.required' => 'Image field is required.' ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->passes()) {
            $item_id = $request->get('item_id');
            $graphics_description = $request->get('graphics_description');
            $graphics_description = str_replace('"', '', $graphics_description);
            $qty = $request->get('qty');
            $date_needed = date("Y-m-d", strtotime($request->get('date_needed')));
            $game_info = $request->get('game_info');
            $locationId = $request->get('location_name');
            $statusId = 1;
            $now = date('Y-m-d');
            $filesnames = $request->get('myInputs');
            if(!empty($filesnames)) {
                $filesnames = implode(',', $filesnames);
              }
            $data = array('location_id' => $locationId, 'request_user_id' => \Session::get('uid'), 'request_date' => $now, 'need_by_date' => $date_needed, 'description' => $game_info . ' - ' . $graphics_description, 'qty' => $qty, 'status_id' => $statusId, 'img' => $filesnames);

            $last_insert_id = $this->model->newGraphicRequest($data);


            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.request_sent_success')
            ));

        } else {
            $message = $this->validateListError($validator->getMessageBag()->toArray());
            return response()->json(array(
                'status' => 'error',
                'message' => $message
            ));
        }

    }

    function getPopupCart($productId = null, $qty = 0)
    {
//        if (\Session::get('gid') == 2)
//        {
//            return response()->json(array(
//                'status' => 'error',
//                'message' => "You don't have permission to perform this task"
//            ));
//        }

        $current_total_cart = \Session::get('total_cart');
        \Session::put('productId', $productId);

        $productExistsInCart = $this->addToCartModel->getCartData($productId);
        $product  = product::find($productId);
        if (!empty($productExistsInCart)) {
            //if ($current_total_cart == $total_cart[0]->total) {
            $existingQty = \DB::select("SELECT qty FROM requests WHERE product_id = $productId AND request_user_id = ".\Session::get('uid')." AND status_id = 4 AND location_id = ".\Session::get('selected_location'));
            $newQty = $existingQty[0]->qty + $qty;
            $unitQty = 0;
            if($product) {
                $merchandiseTypes = order::getMerchandiseTypes();
                if(in_array($product->prod_type_id,$merchandiseTypes)){
                    $unitQty = $newQty * $product->num_items;
                }else {
                    $unitQty = $newQty;
                }
            }
            \DB::update("UPDATE requests SET unit_qty=$unitQty, qty = $newQty WHERE product_id = $productId AND request_user_id = ".\Session::get('uid')." AND status_id = 4 AND location_id = ".\Session::get('selected_location'));
            $message = \Lang::get('core.add_qty_to_cart');
        } else {
            $cartData = $this->addToCartModel->popupCartData($productId, null, $qty);
            $message = \Lang::get('core.add_to_cart');
        }
        $total_cart = $this->addToCartModel->totallyRecordInCart();
        \Session::put('total_cart', $total_cart[0]->total);
        return response()->json(array(
            'status' => 'success',
            'message' => $message,
            'total_cart' => $total_cart[0]->total
        ));
        //return redirect('addtocart')->with(array('productId'=>$productId));

    }

    public function postUploadfiles()
    {
        $last_id = \DB::select("select max(id) as id from new_graphics_request");

        $last_id = $last_id[0]->id + 1;
        $input = \Input::all();
        $rules['file'] = 'mimes:jpeg,gif,png';

        $validation = Validator::make($input, $rules);

        if ($validation->fails()) {
            return \Response::json(array('error'));
        }

        $destinationPath = public_path() . '/uploads/newGraphic'; // upload path

        $extension = \Input::file('file')->getClientOriginalExtension(); // getting file extension
        $fileName = $last_id . "_" . rand(111, 999) . '.' . $extension; // renameing image
        $upload_success = \Input::file('file')->move($destinationPath, $fileName); // uploading file to given path

        if ($upload_success) {
            return $fileName;
        }
    }
}
